<?php

class OsRestfulGroupValidator extends EntityValidateBase {

  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    // Remove fields we don't to handle for now.
    unset($fields['group_access'], $fields['og_roles_permissions']);

    FieldsInfo::setFieldInfo($fields['purl'], $this)
      ->setRequired()
      ->setProperty('purl')
      ->addCallback('validateSinglePurl');

    return $fields;
  }

  /**
   * Validating the purl isn't duplicated.
   */
  public function validateSinglePurl($field_name, $value, $wrapper, $property_wrapper) {
    $purl = $wrapper->domain->value();

    $modifier = array(
      'provider' => 'spaces_og',
      'value' => $purl,
      'id' => $wrapper->getIdentifier(),
    );

    if (!purl_validate($modifier)) {
      $this->setError('domain', 'It seems that there is already a vsite with this purl.');
    }
  }

}
