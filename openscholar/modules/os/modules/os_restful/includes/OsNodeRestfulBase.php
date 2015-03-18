<?php

class OsNodeRestfulBase extends RestfulEntityBaseNode {

  /**
   * Overrides \RestfulDataProviderEFQ::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        \RestfulInterface::GET => 'getList',
        \RestfulInterface::HEAD => 'getList',
        \RestfulInterface::POST => 'createEntity',
        \RestfulInterface::DELETE => 'deleteEntity',
      ),
      '^(\d+,)*\d+$' => array(
        \RestfulInterface::GET => 'viewEntities',
        \RestfulInterface::HEAD => 'viewEntities',
        \RestfulInterface::PUT => 'putEntity',
        \RestfulInterface::PATCH => 'patchEntity',
        \RestfulInterface::DELETE => 'deleteEntity',
      ),
    );
  }

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    if ($this->getBundle()) {
      $public_fields['vsite'] = array(
        'property' => OG_AUDIENCE_FIELD,
        'process_callbacks' => array(
          array($this, 'vsiteFieldDisplay'),
        ),
      );
    }

    $public_fields['body'] = array(
      'property' => 'body',
      'sub_property' => 'value',
    );

    if (field_info_instance($this->getEntityType(), 'field_upload', $this->getBundle())) {
      $public_fields['files'] = array(
        'property' => 'field_upload',
        'process_callbacks' => array(
          array($this, 'fileFieldDisplay'),
        ),
      );
    }

    return $public_fields;
  }

  /**
   * Display the id and the title of the group.
   */
  public function vsiteFieldDisplay($value) {
    return array('title' => $value[0]->title, 'id' => $value[0]->nid);
  }

  /**
   * Process the time stamp to a text.
   */
  public function dateProcess($value) {
    return format_date($value[0]);
  }

  /**
   * Process the file field.
   */
  public function fileFieldDisplay($files) {
    $return = array();

    foreach ($files as $file) {
      $return[] = array(
        'fid' => $file['fid'],
        'filemime' => $file['filemime'],
        'name' => $file['filename'],
        'uri' => $file['uri'],
        'url' => file_create_url($files['uri']),
      );
    }

    return $return;
  }

  public function propertyValuesPreprocess($property_name, $value, $public_field_name) {
    // Get the field info.
    $field_info = field_info_field($property_name);

    switch ($field_info['type']) {
      case 'link_field':
        return array('url' => $value);
    }

    // Return the value as is.
    return parent::propertyValuesPreprocess($property_name, $value, $public_field_name);
  }

}
