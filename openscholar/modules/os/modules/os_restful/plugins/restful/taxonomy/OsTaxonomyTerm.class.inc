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

  public function entityValidate(\EntityMetadataWrapper $wrapper) {
    parent::entityvalidate($wrapper);

    if (!$relation = og_vocab_relation_get(taxonomy_vocabulary_machine_name_load($this->request['vocab'])->vid)) {
      throw new \RestfulBadRequestException("The vocabulary isn't relate to any group.");
    }

    // Set the space object.
    spaces_set_space(vsite_get_vsite($relation->gid));

    if (!vsite_og_user_access('administer taxonomy')) {
      throw new \RestfulBadRequestException("The vocabulary isn't relate to any group.");
    }
  }
}
