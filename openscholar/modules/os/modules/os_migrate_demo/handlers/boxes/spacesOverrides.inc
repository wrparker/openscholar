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
   * Return an options array for node destinations.
   *
   * @param string $language
   *  Default language for nodes created via this destination class.
   * @param string $text_format
   *  Default text format for nodes created via this destination class.
   */
  static public function options($language, $text_format) {
    return compact('language', 'text_format');
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
    // Invoke migration prepare handlers
    $this->prepare($space_override, $row);
    $updating = isset($space_override->id) && !(isset($space_override->is_new) && $space_override->is_new);
    $updating ? $this->numUpdated++ : $this->numCreated++;

    // Define the box.
    $delta = 'box-' . strtolower(str_replace(" ", "-", $space_override->delta));
    $options = array(
      'delta' => $delta,
      'title' => $space_override->description,
      'description' => $space_override->title,
    );

    // Create the box.
    $box = boxes_box::factory($space_override->delta, $options);
    $box->save();
    $blocks['boxes-' . $box->delta]['region'] = $space_override->location;

    // Initialize the module ad the delta.
    if (!array_key_exists($blocks['boxes-' . $box->delta], array('module', 'delta'))) {
      $blocks['boxes-' . $box->delta]['delta'] = $options['delta'];
      $blocks['boxes-' . $box->delta]['module'] = 'boxes';
    }

    $vsite = spaces_load('og', $space_override->og_group_ref['destid1']);
    $vsite->controllers->context->set($space_override->page . ":reaction:block", array(
      'blocks' => $blocks,
    ));

    $this->complete($space_override, $row);
    return TRUE;
  }
}
