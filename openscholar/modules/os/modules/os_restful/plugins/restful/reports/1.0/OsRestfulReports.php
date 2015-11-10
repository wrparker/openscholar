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
    
    $function = "get_$name_string_report";
    if (method_exists($this, $function) && isset($request['columns'])) {
			$this->setPublicFields(array_merge($this->getPublicFields(), $request['columns']));
    	$this->reportName = $name_string;
    	$output = $this->$function();
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
   * Throwing exception easily.
   * @param $message
   *   The exception message.
   * @throws RestfulBadRequestException
   */
  public function throwException($message) {
    throw new \RestfulBadRequestException($message);
  }
}