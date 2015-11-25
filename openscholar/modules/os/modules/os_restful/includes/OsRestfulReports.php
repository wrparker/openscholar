<?php

/**
 * @file
 * Contains \OsRestfulReports
 */
abstract class OsRestfulReports extends \OsRestfulDataProvider {

  /**
   * Determines the number of items that should be returned when viewing lists.
   *
   * @var int
   */
  protected $range = 20;

  /**
   * @var string
   *
   * The string to search for
   */
  protected $keywordString = '';

  /**
   * @var array
   *
   * The fields within which to do a keyword search
   */
  protected $keywordFields = array();

  /**
   * Overrides \RestfulDataProviderEFQ::controllersInfo()
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
        \RestfulInterface::POST => 'getReport',
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
//     return user_access('access reports', $account);
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
   * Display the report
   *
   * @param string $name_string
   *  the name of the report you would like to retrieve
   */
  public function getReport($name_string) {
    $output = array();
    $request = $this->getRequest();
    $function = "get_{$name_string}_report";

    if (method_exists($this, $function)) {
      // if additional public fields have been requested, add them
      if(isset($request['columns'])) {
        $new_public_fields = array();
        foreach ($request['columns'] as $column) {
          $new_public_fields[$column] = array("property" => $column);
        }
        $this->setPublicFields(array_merge($this->getPublicFields(), $new_public_fields));
      }
      // if keyword search is involved, set appropriate properties
      if (isset($request['keyword']) && isset($request['kfields'])) {
        $this->keywordString = $this->cleanArrayParameter($request['keyword']);
        $this->keywordFields = $this->cleanArrayParameter($request['kfields']);
      }
      // set range if it's passed (even if it's 0)
      if (isset($request['range'])) {
        $this->setRange($request['range']);
      }
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
    if ($this->keywordString && $this->keywordFields) {
      $keyword_or = db_or();
      foreach ($this->keywordFields as $field) {
        $keyword_or->condition($field, '%' . db_like($this->keywordString) . '%', "LIKE");
       }
      $query->condition($keyword_or);
    }
  }

  /**
   * {@inheritdoc}
   *
   * remove whitespace
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

  /**
   * {@inheritdoc}
   */
  protected function queryForListPagination(\SelectQuery $query) {
    list($offset, $range) = $this->parseRequestForListPagination();
    if ($range) {
      $query->range($offset, $range);
    }
  }

  /**
   * {@inheritdoc}
  protected function addExtraInfoToQuery($query) {
    $query->addMetaData('count', $this->getTotalCount());
  }
   */

  /**
   * helper function to clean array report parameters
   */
  private function cleanArrayParameter($param) {
		if (is_array($param)) {
			return array_filter($param);
		}
		else {
			return $param;
		}
  }
}
