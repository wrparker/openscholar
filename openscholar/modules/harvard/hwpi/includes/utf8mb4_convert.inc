<?php
/**
 * Allows for converting all databases to another charset.
 */
class DrupalCharsetConverter {
  /**
   * Character set.
   * @var string
   */
  protected $charset = 'utf8mb4';
  /**
   * Collation.
   * @var string
   */
  protected $collation = 'utf8mb4_general_ci';
  /**
   * The current database connection for all actions.
   *
   * @var DatabaseConnection
   */
  protected $connection;
  /**
   * Set limit for batch jobs
  */ 
  protected $start = 0;
  protected $limit = 0;
  public function __construct($charset = NULL, $collation = NULL) {
    if ($charset) {
      $this->charset = $charset;
    }
    if ($collation) {
      $this->collation = $collation;
    }
  }
  /**
   * Set the active database connection.
   *
   * @param DatabaseConnection $connection
   *   The database connection as retrieved by Database::getConnection().
   */
  public function setConnection(DatabaseConnection $connection) {
    $this->connection = $connection;
  }
  /**
   * Convert the MySQL drupal databases character set and collation.
   *
   * @param array $databases
   *   The Drupal 7 database info array.
   */
  public function convert(array $databases, $start = 0, $limit = 0) {
    $success = FALSE;
    $this->start = $start;
    $this->limit = $limit;
    foreach ($databases as $database_key => $database_values) {
      foreach ($database_values as $target => $database) {
        // Skip slave databases, multiple databases within a single target,
        // and any non-MySQL databases.
        if ($target === 'slave' || !isset($database['driver']) || strpos($database['driver'], 'mysql') !== 0) {
          continue;
        }
        // Connect to next database.
        $connection = Database::getConnection($target, $database_key);
        $this->setConnection($connection);
        // Check the database type is mysql.
        $db_type = $connection->databaseType();
        // Skip if not MySQL.
        if ($db_type !== 'mysql') {
          continue;
        }
        if ($this->charset == 'utf8mb4' && !$connection->utf8mb4IsSupported()) {
          continue;
        }
        if ($this->start == 0) {
          // For each database:
          $this->convertDatabase($database['database']);
        }
        // For each table in the database.
        $this->convertTables();
        $success = TRUE;
      }
    }
    return $success;
  }
  /**
   * @param string
   *   Database name.
   * @param string $charset
   *   (Optional) The character set.
   * @param string $collation
   *   (Optional) The collation.
   *
   * @return bool
   *   success|failure.
   */
  public function convertDatabase($database_name, $charset = NULL, $collation = NULL) {
    $sql = "ALTER DATABASE `" . $database_name . "` CHARACTER SET = :charset COLLATE = :collation;";
    return $this->connection->query($sql, array(
      ':charset' => $charset ? $charset : $this->charset,
      ':collation' => $collation ? $collation : $this->collation,
    ));
  }
  /**
   * Converts all the tables defined by drupal_get_schema().
   *
   * @param string $charset
   *   (Optional) The character set.
   * @param string $collation
   *   (Optional) The collation.
   *
   * @return bool
   *   success|failure.
   */
  public function convertTables($charset = NULL, $collation = NULL) {
    // For each table:
    // Deal only with Drupal managed tables.
    $schema = drupal_get_schema();
    $table_names = array_keys($schema);
    sort($table_names);
    $length = ($this->limit > 0 ? $this->limit : count($table_names));
    //foreach ($table_names as $table_name) {
    for ($i = $this->start; $i < ($this->start + $length); $i++) {
      if (!$this->connection->schema()->tableExists($table_names[$i])) {
        continue;
      }
      $this->convertTable($table_names[$i], $charset, $collation);
    }
  }
  /**
   * Converts a table to a desired character set and collation.
   *
   * @param string $table_name
   *  The database table name.
   * @param string $charset
   *   (Optional) The character set.
   * @param string $collation
   *   (Optional) The collation.
   *
   * @return bool
   *   success|failure.
   */
  public function convertTable($table_name, $charset = NULL, $collation = NULL) {
    $this->connection->query("ALTER TABLE {" . $table_name . "} ROW_FORMAT=DYNAMIC ENGINE=INNODB");
    $sql = "ALTER TABLE {" . $table_name . "} CHARACTER SET = :charset COLLATE = :collation";
    $result = $this->connection->query($sql, array(
      ':charset' => $charset ? $charset : $this->charset,
      ':collation' => $collation ? $collation : $this->collation,
    ));
    $this->convertTableFields($table_name, $charset, $collation);
    $this->connection->query("OPTIMIZE TABLE {" . $table_name . "}");
    return $result;
  }
  /**
   * Converts a table's field to a desired character set and collation.
   *
   * @param string $table_name
   *  The database table name.
   * @param string $charset
   *   (Optional) The character set.
   * @param string $collation
   *   (Optional) The collation.
   *
   * @return bool
   *   success|failure.
   */
  public function convertTableFields($table_name, $charset = NULL, $collation = NULL) {
    $results = $this->connection->query("SHOW FULL FIELDS FROM {" . $table_name . "}")->fetchAllAssoc('Field');
    $charset = $charset ? $charset : $this->charset;
    $collation = $collation ? $collation : $this->collation;
    foreach ($results as $row) {
      // Skip fields that don't have collation, as they are probably int or similar.
      // or if we are using that collation for this field already save a query
      // or is not binary.
      if (!$row->Collation || $row->Collation === $collation || strpos($row->Collation, '_bin') !== FALSE) {
        continue;
      }
      $default = '';
      if ($row->Default !== NULL) {
        $default = 'DEFAULT ' . ($row->Default == "CURRENT_TIMESTAMP" ? "CURRENT_TIMESTAMP" : ":default");
      }
      elseif ($row->Null == 'YES' && $row->Key == '') {
        if ($row->Type == 'timestamp') {
          $default = 'NULL ';
        }
        $default .= 'DEFAULT NULL';
      }
      $sql = "ALTER TABLE {" . $table_name . "}
              MODIFY `" . $row->Field . "` " .
              $row->Type . " " .
              "CHARACTER SET :charset COLLATE :collation " .
              ($row->Null == "YES" ? "" : "NOT NULL ") .
              $default . " " .
              $row->Extra . " " .
              "COMMENT :comment";
      $params = array(
        ':charset' => $charset,
        ':collation' => $collation,
        ':comment' => $row->Comment,
      );
      if (strstr($default, ':default')) {
        $params[':default'] = $row->Default;
      }
      $this->connection->query($sql, $params);
    }
  }
}