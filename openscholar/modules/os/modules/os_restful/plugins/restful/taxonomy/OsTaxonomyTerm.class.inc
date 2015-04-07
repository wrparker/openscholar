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
   * {@inheritdoc}
   */
  public function getBundle() {
    if ($this->path) {
      $wrapper = entity_metadata_wrapper('taxonomy_term', $this->path);
      return $wrapper->vocabulary->machine_name->value();
    }

    return $this->bundle;
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

    if (!$vsite = vsite_get_vsite($this->request['vsite'])) {
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
    if (!$this->getRelation($wrapper->value())) {
      // The vocabulary is not relate to any group.
      throw new \RestfulBadRequestException("The vocabulary isn't relate to any group.");
    }

    parent::entityvalidate($wrapper);
  }

  /**
   * {@inheritdoc}
   */
  protected function isValidEntity($op, $entity_id) {
    // The entity is valid since it's already been filtered in
    // self::queryForListFilter() and the access is checked in
    // self::checkEntityAccess().
    return true;
  }

  /**
   * Overrides RestfulEntityBaseTaxonomyTerm::checkEntityAccess().
   */
  protected function checkEntityAccess($op, $entity_type, $entity) {

    if (!$relation = $this->getRelation($entity)) {
      return FALSE;
    }

    if ($op == 'view') {
      return TRUE;
    }

    // We need this in order to alter the global user object.
    $this->getAccount();

    spaces_set_space(vsite_get_vsite($relation->gid));

    if (!vsite_og_user_access('administer taxonomy')) {
      throw new \RestfulBadRequestException("You are not allowed to create terms.");
    }
  }

  /**
   * Get the vocabulary relation from request.
   *
   * @return mixed
   *   OG vocab relation.
   */
  private function getRelation($entity) {
    $vocab = empty($entity->vocabulary_machine_name) ? $this->request['vocab'] : $entity->vocabulary_machine_name;
    $this->bundle = $vocab;
    return og_vocab_relation_get(taxonomy_vocabulary_machine_name_load($vocab)->vid);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkPropertyAccess($op, $public_field_name, EntityMetadataWrapper $property_wrapper, EntityMetadataWrapper $wrapper) {
    if ($op != 'view') {
      $relation = $this->getRelation(taxonomy_term_load($this->path));
      spaces_set_space(vsite_get_vsite($relation->vid));
      return vsite_og_user_access('administer taxonomy');
    }

    return parent::checkPropertyAccess($op, $public_field_name, $property_wrapper, $wrapper);
  }
}
