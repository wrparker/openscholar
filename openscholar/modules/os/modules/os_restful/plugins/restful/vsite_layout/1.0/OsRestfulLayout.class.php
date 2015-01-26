<?php

/**
 * @file
 * Contains \OsRestfulLayout
 */

class OsRestfulLayout extends OsRestfulSpaces {

  protected $validateHandler = 'layouts';
  protected $objectType = 'context';

  /**
   * Verify the user have access to the manage layout.
   */
  public function checkGroupAccess() {
    parent::checkGroupAccess();
    $account = $this->getAccount();

    if (!spaces_access_admin($account, $this->space)) {
      // The current user can't manage boxes.
      $this->throwException("You can't manage layout in this vsite.");
    }
  }

  /**
   * Updating a given space override.
   *
   * type: PUT
   * values: {
   *  vsite: 2,
   *  context: os_front,
   *  blocks: [
   *    os_search_db-site-search: [
   *      region: "sidebar_first"
   *    ]
   *  ]
   * }
   */
  public function updateSpace() {
    // Check group access.
    $this->checkGroupAccess();

    // Validate the object from the request.
    $this->validate();

    ctools_include('layout', 'os');

    $controller = $this->space->controllers->{$this->objectType};
    $settings = $controller->get($this->object->object_id);

    $blocks = os_layout_get($this->object->context, FALSE, FALSE, $this->space);

    // Merge blocks.
    foreach ($blocks as $delta => &$block) {
      if (empty($this->object->blocks[$delta])) {
        continue;
      }

      $block = array_merge($blocks[$delta], $this->object->blocks[$delta]);
    }

    $controller->set($this->object->object_id, $settings);
    os_layout_set($this->object->context, $blocks, $this->space);

    return $blocks;
  }

  /**
   * Creating a space override.
   *
   * type: POST
   * values: {
   *  vsite: 2,
   *  context: os_front,
   *  boxes: [
   *    boxes-1419335380: [
   *      module: "boxes",
   *      delta: "1419335380",
   *      region: "sidebar_second",
   *      weight: 2,
   *      status: 0
   *    ]
   *  ]
   * }
   */
  public function createSpace() {
    // Check group access.
    $this->checkGroupAccess();

    // Validate the object from the request.
    $this->validate();

    // Set up the blocks layout.
    ctools_include('layout', 'os');

    $controller = $this->space->controllers->{$this->objectType};
    $settings = $controller->get($this->object->object_id);

    $settings['blocks'] = array_merge($settings['blocks'], $this->object->blocks);
    $controller->set($this->object->object_id, $settings);
    return $settings['blocks'];
  }

  /**
   * In order to delete a widget from the layout your REST call should be:
   *
   * type: DELETE
   * values: {
   *  vsite: 2,
   *  object_id: os_front:reaction:block,
   *  delta: boxes-1419335380
   * }
   */
  public function deleteSpace() {
    // Check group access.
    $this->checkGroupAccess();
    ctools_include('layout', 'os');

    $controller = $this->space->controllers->{$this->objectType};
    $settings = $controller->get($this->object->object_id);
    unset($settings['blocks'][$this->object->delta]);
    $controller->set($this->object->object_id, $settings);
    return $settings['blocks'];
  }
}
