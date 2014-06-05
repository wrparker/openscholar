<?php

class OsImporterBlogValidator extends NodeValidate {

  public function getFieldsInfo() {
    return parent::getFieldsInfo() +  array(
      'body' => array(
        'preprocess' => array(
          array($this, 'preprocessText'),
        ),
      ),
    );
  }
}
