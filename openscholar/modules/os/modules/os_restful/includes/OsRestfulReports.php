<?php

/**
 * @file
 * Contains \OsRestfulReports
 */
abstract class OsRestfulReports extends \OsRestfulDataProvider {

  /**
   * @var string
   *
   * The name of the report being requested.
   */
  protected $reportName = '';

  /**
   * @var string
   *
   * The string to search for within user fields.
   */
  protected $keywordString = '';

  /**
   * @var string
   *
   * The type of users to show.
   */
  protected $userRoles = '';

  /**
   * @var array
   *
   * The fields within which to do a keyword search.
   */
  protected $keywordFields = array();

  /**
   * Overrides \RestfulDataProviderEFQ::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        // If they don't pass report name then display nothing.
        \RestfulInterface::GET => 'index',
        \RestfulInterface::HEAD => 'index',
        \RestfulInterface::POST => 'create',
      ),
      '^.*$' => array(
        \RestfulInterface::GET => 'getReport',
        \RestfulInterface::HEAD => 'getReport',
        \RestfulInterface::PUT => 'replace',
        \RestfulInterface::PATCH => 'update',
        \RestfulInterface::DELETE => 'remove',      
        ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function access() {
    $account = $this->getAccount();
//     return user_access('adminsiter reports', $account);
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function index() {
    $this->throwException('You must provide the name of the report.');
    return $return;
  }

  /**
   * View a report.
   *
   * @param string $name_string
   *  the name of the report you would like to retrieve.
   */
  public function getReport($name_string) {
    $output = array();
    $request = $this->getRequest();
    $function = "get_{$name_string}_report";

    // if additional public fields have been requested, add them
    // then call function for the requested report
    if (method_exists($this, $function)) {
      if(isset($request['columns'])) {
        $new_public_fields = array();
        foreach ($request['columns'] as $column) {
          $new_public_fields[$column] = array("property" => $column);
        }
        $this->setPublicFields(array_merge($this->getPublicFields(), $new_public_fields));
      }
      if (isset($request['keyword']) && isset($request['kfields'])) {
        $this->keywordString = $request['keyword'];
        $this->keywordFields = $request['kfields'];
      }
      $this->reportName = $name_string;
      $output = $this->{$function}();
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function update($id, $full_replace = FALSE) {
    $this->notImplementedCrudOperation(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function create() {
    $this->notImplementedCrudOperation(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function remove($id) {
    $this->notImplementedCrudOperation(__FUNCTION__);
  }

  /**
   * Overriding the query list filter method
   */
  protected function queryForListFilter(\SelectQuery $query) {
    parent::queryForListFilter($query);

    // add in keyword search on requested fields
    if ($this->keywordString) {
      $keyword_or = db_or();
      foreach ($this->keywordFields as $field) {
        $keyword_or->condition($field, '%' . db_like($this->keywordString) . '%', "LIKE");
       }
      $query->condition($keyword_or);
    }

    // limit by role, if needed
    // radio buttons: only site owners, site owners/admins, all users
    if ($this->userRoles == "admins") {
      $query->condition("ogr.name", '%' . db_like("vsite") . '%', "LIKE");
    }
  }

  /**
   * {@inheritdoc}
   *
   * plus remove whitespace
   */
  public function mapDbRowToPublicFields($row) {
    $new_row = parent::mapDbRowToPublicFields($row);
    foreach($new_row as $id => $column) {
      $new_row[$id] = trim($column);
    }
    return $new_row;
  }

  /**
   * Throwing exception easily.
   * @param $message
   *   The exception message.
   * @throws RestfulBadRequestException
   */
  public function throwException($message) {
    throw new \RestfulBadRequestException($message);
  }
}