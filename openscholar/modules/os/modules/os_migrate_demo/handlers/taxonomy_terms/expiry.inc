<?php

/**
 * Migrate "Expiry" terms.
 */
class OsMigrateExpiryTaxonomyTerm extends OsMigrate {

  public $dependencies = array(
    'OsMigratePersonalNode',
  );

  public $entityType = 'taxonomy_term';
  public $bundle = 'expiry';

  public $ogVocab = TRUE;
}
