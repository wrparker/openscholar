<?php

/**
 * Force sync update on a batch of profiles
 *
 * @param string $host_entity_id_pairs - this is a batch of entity host pairs
 * 
 */
function os_profiles_batch_force_sync_update($host_entity_id_pairs) {

  foreach ($host_entity_id_pairs as $host_entity_id_pair) {

    $entity_id = $host_entity_id_pair[0];
    $host = $host_entity_id_pair[1];

    // Now trigger a re-sync of the node data by notifying the listeners
    $node = node_load($entity_id);
    $uuid = $node->field_uuid[LANGUAGE_NONE][0]['value'];

    $destination = "http://" . $host;
    $arguments = array(
      'action' => 'update',
      'UUID' => $uuid,
    );

    os_profiles_manage_synced_profiles($destination, $arguments);
  }
}

