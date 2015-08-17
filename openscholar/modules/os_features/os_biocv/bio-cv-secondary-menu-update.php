<?php
/**
 * Created by PhpStorm.
 * User: bbalakrishnan
 * Date: 8/17/15
 * Time: 11:24 AM
 */

$results = db_select('spaces_overrides')
    ->fields('id')
    ->condition('object_id', 'secondary_menu')
    ->condition('object_type', 'menu')
    ->condition('value', '%biocv%', 'LIKE')
    ->execute();
$result = $query->execute()->fetchField();

foreach ($results as $id) {
{
    //this line is all wrong
    $vsite_obj = vsite_get_vsite($id);
}

// Get secondary menu info for bio & cv links
$secondary_menu = array();
foreach (_vsite_menu_get_menu_links('secondary-menu',  $vsite_obj) as $mlid => $menu_item) {
  if (isset($menu_item['link_path']) && ($menu_item['link_path'] == "biocv" || $menu_item['link_path'] == "biocv/cv")) {
    $secondary_menu[$menu_item['link_path']] = $menu_item;
  }
}

// create new primary menu item(s) for bio/cv page node(s)
if (count($secondary_menu)) {
  foreach ($secondary_menu as $type => $menu) {
    $new_menu = array(
      "menu_name" => 'secondary-menu',
      "router_path" => "node/%",
      "link_title" => $menu['link_title'],
      "options" => $menu['options'],
      "weight" => $menu['weight'],
      "module" => 'system',
      "hidden" => $menu['hidden'],
      "external" => $menu['external'],
      "plid" => $menu['plid'],
    );
    if ($type == "biocv") {
      $new_menu['link_path'] = 'node/' . $bio_node->nid;
      $mlid = vsite_menu_menu_link_save($new_menu, $gid);
    }
    elseif ($type == "biocv/cv") {
      $new_menu['link_path'] = 'node/' . $cv_node->nid;
      $mlid = vsite_menu_menu_link_save($new_menu, $gid);
    }
  }
  vsite_menu_cache_clear('secondary-menu', $gid);
}