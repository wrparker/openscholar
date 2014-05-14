<?php

/**
 * @file updae_user_permission.php
 *
 * Updating overriding sites permissions.
 *
 * @code
 * Using the script: os scr folder_path/update_user_permission.php
 * Using the script with a given NID: os scr folder_path/update_user_permission.php --id=10403
 * Using the script with a given number of batches: os scr folder_path/update_user_permission.php --batch=20
 * @encode
 */

if (!drupal_is_cli()) {
  // The file is not reachable via web browser.
  return;
}

$batch = drush_get_option('batch', 250);
$memory_limit = drush_get_option('memory_limit', 500);
$id = drush_get_option('id', 1);

// Define terms for each role.
$admin_permissions = array(
  'administer taxonomy',
  'delete terms',
  'edit terms',
  'create slideshow_slide content',
  'update own slideshow_slide content',
  'update any slideshow_slide content',
  'delete own slideshow_slide content',
  'delete any slideshow_slide content',
);

$content_editor_permissions = array(
  'create slideshow_slide content',
  'update own slideshow_slide content',
  'update any slideshow_slide content',
  'delete own slideshow_slide content',
);

$basic_member_permission = array(
  'create slideshow_slide content',
  'update own slideshow_slide content',
  'delete own slideshow_slide content'
);

// Count how much site we need to iterate.
$query = new EntityFieldQuery();
$max = $query
  ->entityCondition('entity_type', 'node')
  ->fieldCondition(OG_DEFAULT_ACCESS_FIELD, 'value', 1)
  ->propertyCondition('nid', $id, '>=')
  ->range(0, $batch)
  ->count()
  ->execute();

$i = 0;

while ($max > $i) {
  // Collect the node in batches.
  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', 'node')
    ->fieldCondition(OG_DEFAULT_ACCESS_FIELD, 'value', 1)
    ->propertyCondition('nid', $id, '>=')
    ->propertyOrderBy('nid', 'ASC')
    ->range(0, $batch)
    ->execute();

  if (empty($result['node'])) {
    return;
  }

  $nodes = node_load_multiple(array_keys($result['node']));

  foreach ($nodes as $node) {
    $roles = og_roles('node', $node->type, $node->nid);

    $vsite_admin_rid = array_search('vsite admin', $roles);
    $content_editor_rid = array_search('content editor', $roles);
    $basic_member_rid = array_search('vsite user', $roles);

    og_role_grant_permissions($vsite_admin_rid, $admin_permissions);
    og_role_grant_permissions($content_editor_rid, $content_editor_permissions);
    og_role_grant_permissions($basic_member_rid, $basic_member_permission);

    $params = array(
      '@nid' => $node->nid,
      '@title' => $node->title,
    );

    drush_log(dt($i + 1 . '\ @max) Processing the permission for the vsite @title(nid: @nid).', $params), 'success');

    // The script taking to much memory. Stop it and display message.
    if (round(memory_get_usage()/1048576) >= $memory_limit) {
      return drush_set_error('OS_ACTIVITY OUT_OF_MEMORY', dt('Stopped before out of memory. Last node ID was @nid', array('@nid' => $node->nid)));
    }

    $i++;
  }
}
