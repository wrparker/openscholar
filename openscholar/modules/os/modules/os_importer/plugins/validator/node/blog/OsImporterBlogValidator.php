<?php

class OsImporterBlogValidator extends NodeValidate {

  public function validate() {
    $fields = $this->getFields();
    $fields['body'] = array('value' => $fields['body']);
    $this->setFields($fields);

    parent::validate();
  }
}
