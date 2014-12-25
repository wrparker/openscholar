<?php

class OsNodeRestfulBase extends RestfulEntityBaseNode {

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['vsite'] = array(
      'property' => OG_AUDIENCE_FIELD,
    );

    $public_fields['body'] = array(
      'property' => 'body',
      'sub_property' => 'value',
    );

    return $public_fields;
  }

}
