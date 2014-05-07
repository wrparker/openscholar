<?php

class spacesOverridesDestination extends MigrateDestinationEntity {
  static public function getKeySchema() {
    return array(
      'id' => array(
        'type' => 'int',
        'unsigned' => TRUE,
        'description' => 'ID of space overrides',
      ),
    );
  }

  /**
   * Basic initialization
   */
  public function __construct() {
    parent::__construct('spaces_overrides', 'context');
  }

  /**
   * Delete a batch of nodes at once.
   *
   * @param $ids
   *  Array of node IDs to be deleted.
   */
  public function bulkRollback(array $ids) {
    $this->prepareRollback($ids);
    $this->completeRollback($ids);

    // todo: Remove the block form the space override.
  }

  /**
   * Returns a list of fields available to be mapped for the spaces overrides
   *
   * @param Migration $migration
   *  Optionally, the migration containing this destination.
   * @return array
   *  Keys: machine names of the fields (to be passed to addFieldMapping)
   *  Values: Human-friendly descriptions of the fields.
   */
  public function fields($migration = NULL) {
    // todo: leave for later.
    $fields = array();
    $fields['is_new'] = t('Option: <a href="@doc">Indicates a new node with the specified id should be created</a>',
      array('@doc' => 'http://drupal.org/node/1349696#is_new'));

    // Then add in anything provided by handlers
    $fields += migrate_handler_invoke_all('Entity', 'fields', $this->entityType, $this->bundle, $migration);
    $fields += migrate_handler_invoke_all('SpacesOverrides', 'fields', $this->entityType, $this->bundle, $migration);

    return $fields;
  }

  /**
   * Import a single spaces overrides.
   *
   * @param $space_override
   *  Node object to build. Prefilled with any fields mapped in the Migration.
   * @param $row
   *  Raw source data object - passed through to prepare/complete handlers.
   * @return array
   *  Array of key fields (id only in this case) of the node that was saved if
   *  successful. FALSE on failure.
   */
  public function import(stdClass $space_override, stdClass $row) {
    ctools_include('layout', 'os');

    // Invoke migration prepare handlers
    $this->prepare($space_override, $row);

    $updating = isset($space_override->id) && !(isset($space_override->is_new) && $space_override->is_new);
    $updating ? $this->numUpdated++ : $this->numCreated++;

    // todo: load the options from somewhere.
    $migration = Migration::currentMigration();
    $method = $this->camelCase($space_override->delta);
    if (method_exists($migration, $method . 'Settings')) {
      $space_override->options = call_user_func(array($migration, $method . 'Settings'));
    }

    $options = array(
      'delta' => $space_override->delta,
      'title' => $space_override->title,
      'description' => $space_override->descritpion,
    );

    // Create the box.
    $box = boxes_box::factory($space_override->delta, $options);
    $box->options = $space_override->options;
    $box->save();

    if (module_exists('spaces') && $space = spaces_get_space()) {
      $space->controllers->boxes->set($box->delta, $box);
    }
    else {
      $box->save();
    }

    // Set this in the correct place.
    $blocks = os_layout_get_multiple(array($space_override->page, 'os_public'), false, true);
    $blocks['boxes-' . $box->delta]['region'] = $space_override->location;

    $blocks['boxes-' . $space_override->delta]['delta'] = $options['delta'];
    $blocks['boxes-' . $space_override->delta]['module'] = 'boxes';

    $vsite = spaces_load('og', $space_override->{OG_AUDIENCE_FIELD}['destid1']);
    $vsite->controllers->context->set($space_override->page . ":reaction:block", array(
      'blocks' => $blocks,
    ));

    $this->complete($space_override, $row);

    return $space_override->{OG_AUDIENCE_FIELD}['destid1'];
  }

  /**
   * Convert the delta into camel case.
   */
  private function camelCase($delta) {
    $words = explode("_", $delta);
    foreach ($words as &$word) {
      $word = ucfirst($word);
    }

    return implode("", $words);
  }
}
