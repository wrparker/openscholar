<?php

/**
 * Migrate "Appointed" terms.
 */
class OsMigrateAppointedTaxonomyTerm extends OsMigrate {

  public $dependencies = array(
    'OsMigratePersonalNode',
  );

  public $entityType = 'taxonomy_term';
  public $bundle = 'appointed';

  public $ogVocab = TRUE;
}
