<?php

/**
 * Migrate "country" terms.
 */
class OsMigrateCountryTaxonomyTerm extends OsMigrate {

  public $dependencies = array(
    'OsMigratePersonalNode',
  );

  public $entityType = 'taxonomy_term';
  public $bundle = 'country';

  public $ogVocab = TRUE;
}
