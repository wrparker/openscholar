<?php

class OsVocabulary extends \RestfulEntityBase {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    $fields['machine_name'] = array(
      'property' => 'machine_name',
    );

    return $fields;
  }

  protected function checkEntityAccess($op, $entity_type, $entity) {
    return TRUE;
  }

  protected function checkPropertyAccess($op, $public_field_name, EntityMetadataWrapper $property_wrapper, EntityMetadataWrapper $wrapper) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   *
   * Display vocabs from the current vsite.
   */
  protected function queryForListFilter(\EntityFieldQuery $query) {
    if (empty($_GET['vsite'])) {
      throw new \RestfulBadRequestException(t('You need to provide a vsite.'));
    }

    if (!$vsite = vsite_get_vsite($this->request['vsite'])) {
      return;
    }

    module_load_include('inc', 'vsite_vocab', 'includes/taxonomy');
    $vocabs = array_keys(vsite_vocab_get_vocabularies($vsite));

    $query->propertyCondition('vid', $vocabs, 'IN');
  }

}
