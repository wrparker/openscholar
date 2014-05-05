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
   * @param $nids
   *  Array of node IDs to be deleted.
   */
  public function bulkRollback(array $nids) {
    $this->prepareRollback($nids);
    $this->completeRollback($nids);

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
    $fields['is_new'] = t('Option: <a href="@doc">Indicates a new node with the specified nid should be created</a>',
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
   *  Array of key fields (nid only in this case) of the node that was saved if
   *  successful. FALSE on failure.
   */
  public function import(stdClass $space_override, stdClass $row) {
//
//    // Updating previously-migrated content?
//    $migration = Migration::currentMigration();
//    if (isset($row->migrate_map_destid1)) {
//      // Make sure is_new is off
//      $node->is_new = FALSE;
//      if (isset($node->nid)) {
//        if ($node->nid != $row->migrate_map_destid1) {
//          throw new MigrateException(t("Incoming nid !nid and map destination nid !destid1 don't match",
//            array('!nid' => $node->nid, '!destid1' => $row->migrate_map_destid1)));
//        }
//      }
//      else {
//        $node->nid = $row->migrate_map_destid1;
//      }
//      // Get the existing vid, tnid so updates don't generate notices
//      $values = db_select('node', 'n')
//        ->fields('n', array('vid', 'tnid'))
//        ->condition('nid', $node->nid)
//        ->execute()
//        ->fetchAssoc();
//      if (empty($values)) {
//        throw new MigrateException(t("Incoming node ID !nid no longer exists",
//          array('!nid' => $node->nid)));
//      }
//      $node->vid = $values['vid'];
//      if (empty($node->tnid)) {
//        $node->tnid = $values['tnid'];
//      }
//    }
//    if ($migration->getSystemOfRecord() == Migration::DESTINATION) {
//      if (!isset($node->nid)) {
//        throw new MigrateException(t('System-of-record is DESTINATION, but no destination nid provided'));
//      }
//      $old_node = node_load($node->nid);
//      if (empty($old_node)) {
//        throw new MigrateException(t('System-of-record is DESTINATION, but node !nid does not exist',
//          array('!nid' => $node->nid)));
//      }
//      if (!isset($node->created)) {
//        $node->created = $old_node->created;
//      }
//      if (!isset($node->vid)) {
//        $node->vid = $old_node->vid;
//      }
//      if (!isset($node->status)) {
//        $node->status = $old_node->status;
//      }
//      if (!isset($node->uid)) {
//        $node->uid = $old_node->uid;
//      }
//    }
//    elseif (!isset($node->type)) {
//      // Default the type to our designated destination bundle (by doing this
//      // conditionally, we permit some flexibility in terms of implementing
//      // migrations which can affect more than one type).
//      $node->type = $this->bundle;
//    }
//
//    // Set some required properties.
//
//    if ($migration->getSystemOfRecord() == Migration::SOURCE) {
//      if (empty($node->language)) {
//        $node->language = $this->language;
//      }
//
//      // Apply defaults, allow standard node prepare hooks to fire.
//      // node_object_prepare() will blow these away, so save them here and
//      // stuff them in later if need be.
//      if (isset($node->created)) {
//        $created = MigrationBase::timestamp($node->created);
//      }
//      else {
//        // To keep node_object_prepare() from choking
//        $node->created = REQUEST_TIME;
//      }
//      if (isset($node->changed)) {
//        $changed = MigrationBase::timestamp($node->changed);
//      }
//      if (isset($node->uid)) {
//        $uid = $node->uid;
//      }
//      node_object_prepare($node);
//      if (isset($created)) {
//        $node->created = $created;
//      }
//      // No point to resetting $node->changed here, node_save() will overwrite it
//      if (isset($uid)) {
//        $node->uid = $uid;
//      }
//    }
//
//    // Invoke migration prepare handlers
//    $this->prepare($node, $row);
//
//    if (!isset($node->revision)) {
//      $node->revision = 0; // Saves disk space and writes. Can be overridden.
//    }
//
//    // Trying to update an existing node
//    if ($migration->getSystemOfRecord() == Migration::DESTINATION) {
//      // Incoming data overrides existing data, so only copy non-existent fields
//      foreach ($old_node as $field => $value) {
//        // An explicit NULL in the source data means to wipe to old value (i.e.,
//        // don't copy it over from $old_node)
//        if (property_exists($node, $field) && $node->$field === NULL) {
//          // Ignore this field
//        }
//        elseif (!isset($node->$field)) {
//          $node->$field = $old_node->$field;
//        }
//      }
//    }
//
//    if (isset($node->nid) && !(isset($node->is_new) && $node->is_new)) {
//      $updating = TRUE;
//    }
//    else {
//      $updating = FALSE;
//    }
//
//    migrate_instrument_start('node_save');
//    node_save($node);
//    migrate_instrument_stop('node_save');
//
//    if (isset($node->nid)) {
//      if ($updating) {
//        $this->numUpdated++;
//      }
//      else {
//        $this->numCreated++;
//      }
//
//      // Unfortunately, http://drupal.org/node/722688 was not accepted, so fix
//      // the changed timestamp
//      if (isset($changed)) {
//        db_update('node')
//          ->fields(array('changed' => $changed))
//          ->condition('nid', $node->nid)
//          ->execute();
//        $node->changed = $changed;
//      }
//
//      // Potentially fix uid and timestamp in node_revisions.
//      $query = db_update('node_revision')
//        ->condition('vid', $node->vid);
//      if (isset($changed)) {
//        $fields['timestamp'] = $changed;
//      }
//      $revision_uid = isset($node->revision_uid) ? $node->revision_uid : $node->uid;
//      if ($revision_uid != $GLOBALS['user']->uid) {
//        $fields['uid'] = $revision_uid;
//      }
//      if (!empty($fields)) {
//        // We actually have something to update.
//        $query->fields($fields);
//        $query->execute();
//        if (isset($changed)) {
//          $node->timestamp = $changed;
//        }
//      }
//      $return = array($node->nid);
//    }
//    else {
//      $return = FALSE;
//    }


    $this->complete($space_override, $row);
    return FALSE;
  }
}
