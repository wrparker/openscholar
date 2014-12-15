<?php

/**
 * @file
 * Contains \OsRestfulSpacesOverrides
 */

class OsRestfulBoxes extends OsRestfulSpaces {

  protected $validateHandler = 'boxes';

  /**
   * Overriding the query list filter method: Exposing only boxes.
   */
  protected function queryForListFilter(\SelectQuery $query) {
    parent::queryForListFilter($query);
    $query->condition('object_type', 'boxes');
  }

  /**
   * Verify the user have access to the manage boxes.
   */
  public function checkGroupAccess() {
    parent::checkGroupAccess();
    $account = $this->getAccount();

    $access = !og_user_access('node', $this->space->id, 'administer boxes', $account) ||
              !og_user_access('node', $this->space->id, 'edit boxes', $account);

    if ($access) {
      // The current user can't manage boxes.
      $this->throwException("You can't manage boxes in this vsite.");
    }
  }

  /**
   * Updating a given space override.
   */
  public function updateSpace() {
    // Check group access.
    $this->checkGroupAccess();

    // Validate the object from the request.
    $this->validate();

    $request = $this->getRequest();
    $space = spaces_load('og', $this->object->vsite);
    $controller = $space->controllers->{$this->object->filter['object_type']};
    $settings = $controller->get($this->object->delta);
    $new_settings = array_merge((array) $settings, $this->object->settings);
    $controller->set($request['delta'], (object) $new_settings);
  }

  /**
   * Creating a space override.
   */
  public function createSpace() {
    // Check group access.
    $this->checkGroupAccess();

    // Validate the object from the request.
    $this->validate();

    $space = spaces_load('og', $this->object->vsite);

    // Creating a new widget.
    $options = array(
      'delta' => time(),
    ) + $this->object->options;

    // Create the box the current vsite.
    $box = boxes_box::factory($this->object->widget, $options);
    $space->controllers->boxes->set($box->delta, $box);
  }

  /**
   * Delete a specific box.
   */
  public function deleteSpace() {
    // Check group access.
    $this->checkGroupAccess();

    $delta = $this->object->object_id;

    ctools_include('layout', 'os');
    os_layout_block_delete('boxes-' . $delta);

    $this->space->controllers->boxes->del($delta);
  }
}
