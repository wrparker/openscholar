<?php
/**
 * Created by PhpStorm.
 * User: bbalakrishnan
 * Date: 8/17/15
 * Time: 11:24 AM
 */
define('DRUPAL_ROOT', "/Applications/MAMP/openscholar/www");

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


$result = db_select('spaces_overrides', 'spaces_overrides')
    ->distinct()
    ->fields('spaces_overrides', array('id'))
    ->condition('object_id', 'secondary-menu')
    ->condition('object_type', 'menus')
    ->condition('value', '%biocv%', 'LIKE')
    ->execute();

$count = $result->rowCount();
while($id = $result->FetchField()) {
  $vsite_obj = vsite_get_vsite($id);

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
        "mlid" => $menu['mlid'], // Replace the current item.
      );

      $vsite_name = vsite_get_purl($vsite_obj);

      if ($type == "biocv") {
        $bio_node = db_select('url_alias', 'url_alias')
            ->fields('url_alias', array('source'))
            ->condition('alias', $vsite_name.'/biocv%', 'LIKE')
            ->orderby('alias', 'ASC')
            ->execute()
            ->fetchCol();

        $bio_node = substr((string)$bio_node[0], 5);
        if (!intval($bio_node)) {
          echo "Unable to find BioCV node for site $id \n";
          continue;
        }

        $new_menu['link_path'] = 'node/' . $bio_node;
        $mlid = vsite_menu_menu_link_save($new_menu, $id);
      }
      elseif ($type == "biocv/cv") {
        $cv_node = db_select('url_alias', 'url_alias')
           ->fields('url_alias', array('source'))
           ->condition('alias', $vsite_name.'/biocv/cv%', 'LIKE')
            ->orderby('alias', 'ASC')
            ->execute()
            ->fetchCol();

        $cv_node = substr((string)$cv_node, 5);
        if (!intval($cv_node)) {
          echo "Unable to find CV node for site $id \n";
          continue;
        }

        $new_menu['link_path'] = 'node/' . $cv_node;
        $mlid = vsite_menu_menu_link_save($new_menu, $id);
      }
    }
    vsite_menu_cache_clear('secondary-menu', $id);
  }
}
?>