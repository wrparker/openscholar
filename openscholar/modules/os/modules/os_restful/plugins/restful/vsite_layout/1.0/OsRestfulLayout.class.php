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

    $controller = $this->space->controllers->{$this->objectType};
    $settings = $controller->get($this->object->object_id);

    // Merge blocks.
    foreach ($settings['blocks'] as $delta => &$block) {
      if (empty($this->object->blocks[$delta])) {
        continue;
      }

      $block = array_merge($settings['blocks'][$delta], $this->object->blocks[$delta]);
    }

    $controller->set($this->object->object_id, $settings);

    return $settings;
  }

  /**
   * Creating a space override.
   */
  public function createSpace() {
    // Check group access.
    $this->checkGroupAccess();

    // Validate the object from the request.
    $this->validate();

    // Set up the blocks layout.
    ctools_include('layout', 'os');
    $contexts = array(
      $this->object->object_id,
      'os_public',
    );

    $blocks = os_layout_get_multiple($contexts, FALSE, TRUE);

    os_layout_set($this->object->object_id, $blocks, $this->space);
  }

  public function deleteSpace() {

  }
}
