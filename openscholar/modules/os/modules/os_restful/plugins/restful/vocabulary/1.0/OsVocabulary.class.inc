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

    $fields['vsite'] = array(
      'property' => 'vid',
      'process_callbacks' => array(
        array($this, 'processVsite'),
      ),
    );

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function entityValidate(\EntityMetadataWrapper $wrapper) {
    if (empty($this->request['vsite'])) {
      throw new \RestfulBadRequestException('You need to provide vsite ID.');
    }

    if (!$vsite = vsite_get_vsite($this->request['vsite'])) {
      throw new \RestfulBadRequestException('The is not vsite with the provided ID.');
    }

    parent::entityValidate($wrapper);
  }

  /**
   * Get the group ID for the vocabulary using the og vocab relationship.
   *
   * @param $vid
   *   The vocabulary ID.
   *
   * @return
   *   The group ID.
   */
  protected function processVsite($vid) {
    $relation = og_vocab_relation_get($vid);

    return $relation->gid;
  }

  /**
   * {@inheritdoc}
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {
    // todo: handle later.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function checkPropertyAccess($op, $public_field_name, EntityMetadataWrapper $property_wrapper, EntityMetadataWrapper $wrapper) {
    // Always return TRUE for properties.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function queryForListFilter(\EntityFieldQuery $query) {
    // Display vocabs from the current vsite.
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
