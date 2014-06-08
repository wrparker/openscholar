<?php
/**
 * Required: title, url.
 */
class OsImporterLinkValidator extends NodeValidate {

  public function getFieldsInfo() {
    return parent::getFieldsInfo() +  array(
      'body' => array(
        'preprocess' => array(
          array($this, 'preprocessText'),
        ),
      ),
      'field_semester' => array(
        'validators' => array(
          array($this, 'validationSemester'),
        ),
      ),
      'field_offered_year' => array(
        'preprocess' => array(
          array($this, 'preprocessOfferedYear'),
        ),
      ),
    );
  }
}
