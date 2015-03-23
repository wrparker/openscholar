<?php

class OsTaxonomyTerm extends \RestfulEntityBaseTaxonomyTerm {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    $fields['vocab'] = array(
      'property' => 'vocabulary',
      'process_callbacks' => array(
        array($this, 'processVocab'),
      ),
    );

    return $fields;
  }

  /**
   * Display the name of the vocab from the vocabulary object.
   *
   * @param $value
   *   The vocabulary object.
   *
   * @return mixed
   *   The machine name of the vocabulary.
   */
  protected function processVocab($value) {
    return $value->machine_name;
  }

  /**
   * {@inheritdoc}
   *
   * Display taxonomy terms from the current vsite.
   */
  protected function queryForListFilter(\EntityFieldQuery $query) {

    if (empty($_GET['vsite'])) {
      throw new \RestfulBadRequestException(t('You need to provide a vsite.'));
    }

    if (!$vsite = vsite_get_vsite($_GET['vsite'])) {
      return;
    }

    module_load_include('inc', 'vsite_vocab', 'includes/taxonomy');
    $vocabs = array_keys(vsite_vocab_get_vocabularies($vsite));

    $query->propertyCondition('vid', $vocabs, 'IN');
  }

  /**
   * {@inheritdoc}
   */
  public function entityValidate(\EntityMetadataWrapper $wrapper) {
    if (!$this->getRelation()) {
      // The vocabulary is not relate to any group.
      throw new \RestfulBadRequestException("The vocabulary isn't relate to any group.");
    }

    parent::entityvalidate($wrapper);
  }

  /**
   * Check access to CRUD an entity.
   *
   * @param $op
   *   The operation. Allowed values are "create", "update" and "delete".
   * @param $entity_type
   *   The entity type.
   * @param $entity
   *   The entity object.
   *
   * @return bool
   *   TRUE or FALSE based on the access. If no access is known about the entity
   *   return NULL.
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {

    if (!$relation = $this->getRelation()) {
      return FALSE;
    }

    // We need this in order to alter the global user object.
    $this->getAccount();

    spaces_set_space(vsite_get_vsite($relation->gid));

    if (!vsite_og_user_access('administer taxonomy')) {
      throw new \RestfulBadRequestException("The vocabulary isn't relate to any group.");
    }
  }

  /**
   * Get the vocabulary relation from request.
   *
   * @return mixed
   *   OG vocab relation.
   */
  private function getRelation() {
    return og_vocab_relation_get(taxonomy_vocabulary_machine_name_load($this->request['vocab'])->vid);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkPropertyAccess($op, $public_field_name, EntityMetadataWrapper $property_wrapper, EntityMetadataWrapper $wrapper) {
    if ($public_field_name == 'vocab') {
      return vsite_og_user_access('administer taxonomy');
    }

    return parent::checkPropertyAccess($op, $public_field_name, $property_wrapper, $wrapper);
  }
}
