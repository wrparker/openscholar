<?php

class FeedNodeRestfulBase extends OsNodeRestfulBase {

  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    $fields['field_url'] = array(
      'property' => 'field_url',
      'required' => TRUE,
    );

    return $fields;
  }

}
