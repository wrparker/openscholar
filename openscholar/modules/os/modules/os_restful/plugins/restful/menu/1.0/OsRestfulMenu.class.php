<?php

/**
 * @file
 * Contains \OsRestfulMenu
 */

class OsRestfulMenu extends \OsRestfulDataProvider {

  /**
   * Return the properties that should be public.
   *
   * @throws \RestfulEntityViewMode
   *
   * @return array
   */
  public function publicFieldsInfo() {
    $fields = array();

    $properties = array(
      'id' => 'mlid',
      'path' => 'link_path',
      'title' => 'link_title',
      'menu' => 'menu_name',
    );

    foreach ($properties as $key => $property) {
      $fields[$key] = array(
        'property' => $property,
      );
    }

    return $fields;
  }
}
