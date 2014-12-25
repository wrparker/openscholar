<?php

class OsNodeRestfulBase extends RestfulEntityBaseNode {

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['vsite'] = array(
      'property' => OG_AUDIENCE_FIELD,
      'process_callbacks' => array(
        array($this, 'vsiteFieldDisplay'),
      ),
    );

    $public_fields['body'] = array(
      'property' => 'body',
      'sub_property' => 'value',
    );

    return $public_fields;
  }

  /**
   * Display the id and the title of the
   */
  public function vsiteFieldDisplay($value) {
    return array('title' => $value[0]->title, 'id' => $value[0]->nid);
  }

}
