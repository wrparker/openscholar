<?php

/**
 * @file
 * Contains \OsRestfulSpaces
 */
abstract class OsRestfulSpaces extends \RestfulDataProviderDbQuery implements \RestfulDataProviderDbQueryInterface, \RestfulDataProviderInterface {

  /**
   * @var stdClass
   * The object the controller need to handle.
   */
  protected $object;

  /**
   * @var vsite
   * The space object.
   */
  protected $space;

  /**
   * @var string
   * The string handler.
   */
  protected $validateHandler = '';

  /**
   * @var string
   * Object type: context, boxes etc. etc.
   */
  protected $objectType = '';

  /**
   * @var EntityMetadataWrapper
   *
   * The group wrapper object.
   */
  protected $group;

  /**
   * Overrides \RestfulDataProviderEFQ::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        \RestfulInterface::GET => 'getSpace',
        \RestfulInterface::POST => 'createSpace',
        \RestfulInterface::PUT => 'updateSpace',
        \RestFulInterface::DELETE => 'deleteSpace',
      ),
    );
  }

  abstract public function createSpace();
  abstract public function updateSpace();
  abstract public function deleteSpace();

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return $this->simpleFieldsInfo(array('type', 'id', 'object_id', 'object_type', 'value'));
  }

  /**
   * Simple fields mapping.
   *
   * @param array $fields
   *   The list of the properties.
   * @return array
   *   List of the schema fields.
   */
  public function simpleFieldsInfo($fields = array()) {
    $return = array();
    foreach ($fields as $field) {
      $return[$field] = array('property' => $field);
    }

    return $return;
  }

  /**
   * Overriding the query list filter method: Exposing only boxes.
   */
  protected function queryForListFilter(\SelectQuery $query) {
    parent::queryForListFilter($query);

    if ($this->objectType) {
      $query->condition('object_type', $this->objectType);
    }

    if (!empty($_GET['vsite'])) {
      $query->condition('id', $_GET['vsite']);
    }
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
   * Verify the user's request has access CRUD in the current group.
   */
  public function checkGroupAccess() {
    // Get the clean request.
    $request = $this->getRequest();
    static::cleanRequest($request);
    $this->object = (object)$request;

    if (!$this->space = spaces_load('og', $this->object->vsite)) {
      // No vsite context.
      $this->throwException('The vsite ID is missing.');
    }

    $this->group = entity_metadata_wrapper('node', $this->space->og);

    if (user_access('administer group', $this->getAccount())) {
      return TRUE;
    }
  }

  /**
   * un-serialize the value object.
   */
  public function mapDbRowToPublicFields($row) {
    $row->value = unserialize($row->value);
    return parent::mapDbRowToPublicFields($row);
  }

  /**
   * Override the list method in order to return a specific delta from the
   * space override.
   */
  public function getSpace() {
    $elements = parent::index();

    $request = $this->getRequest();

    foreach ($elements as $element) {
      if ($element['object_id'] == $request['delta']) {
        return array(array($element));
      }
    }

    return $elements;
  }

  /**
   * Validate object.
   *
   * @throws RestfulBadRequestException
   *   Throws exception with the error per object.
   * @return stdClass
   *   The clean request object convert ot a std object.
   */
  public function validate() {
    if (!$handler = entity_validator_get_schema_validator($this->validateHandler)) {
      return;
    }

    $result = $handler->validate($this->object, TRUE);

    $errors_output = array();
    if (!$result) {
      $e = new \RestfulBadRequestException("It's look that you sent a request with bad values.");
      $fields_errors = $handler->getErrors(FALSE);
      foreach ($fields_errors as $field => $errors) {

        foreach ($errors as $error) {
          $errors_output[$field][] = format_string($error['message'], $error['params']);
        }

        $e->addFieldError($field, implode(', ', $errors_output[$field]));
      }

      throw $e;
    }
  }
}
