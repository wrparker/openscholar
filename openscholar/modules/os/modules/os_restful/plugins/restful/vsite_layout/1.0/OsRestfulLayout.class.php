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



    // Check group access.
    $this->checkGroupAccess();

    $this->object->new = FALSE;

    // Validate the object from the request.
    $this->validate();

    $controller = $this->space->controllers->{$this->objectType};
    $settings = $controller->get($this->object->delta);
    if (!count(get_object_vars($settings))) {
      $this->throwException("The delta which you provided doesn't exists");
    }
    $new_settings = array_merge((array) $settings, $this->object->options);
    $controller->set($this->object->delta, (object) $new_settings);

    return $new_settings;
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

    // Set up the blocks layout.
    ctools_include('layout', 'os');
    $contexts = array(
      $this->object->context,
      'os_public',
    );
    $blocks = os_layout_get_multiple($contexts, FALSE, TRUE);

    // todo: get the box.

    // Create the box the current vsite.
//    $box = boxes_box::factory($this->object->widget, $options);
//    $space->controllers->boxes->set($box->delta, $box);

    // Add the block to the region.
//    $blocks['boxes-' . $box->delta]['region'] = $this->object->region;

    if (!array_key_exists($blocks['boxes-' . $box->delta], array('module', 'delta'))) {
      $blocks['boxes-' . $box->delta]['delta'] = $box->delta;
      $blocks['boxes-' . $box->delta]['module'] = 'boxes';
      $blocks['boxes-' . $box->delta]['weight'] = 0;
    }

    $space->controllers->context->set($this->object->context . ":reaction:block", array(
      'blocks' => $blocks,
    ));
  }

  public function deleteSpace() {

  }
}
