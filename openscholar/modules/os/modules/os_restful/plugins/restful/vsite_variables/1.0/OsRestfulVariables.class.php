<?php

/**
 * @file
 * Contains \OsRestfulLayout
 */

class OsRestfulVariables extends OsRestfulSpaces {

  protected $validateHandler = 'variables';
  protected $objectType = 'variable';

  /**
   * Verify the user have access to the manage layout.
   */
  public function checkGroupAccess() {
    parent::checkGroupAccess();
    $account = $this->getAccount();

    if (user_access('administer group', $account)) {
      return TRUE;
    }

    if ($this->group->author->getIdentifier() != $account->uid) {
      // The current user can't manage boxes.
      $this->throwException('You are not authorised to manage the variables of the group.');
    }
  }

  /**
   * Updating a given space override.
   *
   * type: PUT
   * values: {
   *  vsite: 2,
   *  object_id: vsite_head_link_rel_author,
   *  value: 1
   * }
   */
  public function updateSpace() {
    return $this->createUpdateVariable();
  }

  /**
   * Creating a space override.
   *
   * type: POST
   * values: {
   *  vsite: 2,
   *  object_id: vsite_head_link_rel_author,
   *  value: 1
   * }
   */
  public function createSpace() {
    return $this->createUpdateVariable();
  }

  /**
   * Create and update are the same actions. Invoke them in a single method
   * that will be invoked by the rest call type.
   */
  private function createUpdateVariable() {
    // Check group access.
    $this->checkGroupAccess();

    $controller = $this->space->controllers->{$this->objectType};
    $controller->set($this->object->object_id, $this->object->value);
    return array(
      'name' => $this->object->object_id,
      'value' => $controller->get($this->object->object_id),
    );
  }

  /**
   * In order to delete a widget from the layout your REST call should be:
   * type: DELETE
   *
   * values: {
   *  vsite: 2,
   *  object_id: vsite_head_link_rel_author,
   * }
   */
  public function deleteSpace() {
    // Check group access.
    $this->checkGroupAccess();

    $controller = $this->space->controllers->{$this->objectType};
    $controller->del($this->object->object_id);
  }

  /**
   * Keep a list of the object we don't want to show.
   *
   * @return array
   *   Array of variables names.
   */
  private function blackList() {
    return array(
      'spaces_features', 'robotstxt', 'os_mailchimp_vsite_api_key',
      'os_ga_google_analytics_id', 'vsite_private', 'cp_admin_menu',
      'vsite_domain_shared', 'vsite_head_link_rel_author',
      'vsite_head_link_rel_publisher', 'spaces_preset_og', 'os_menus',
    );
  }

  /**
   * Filtering the variables we don't want to show.
   */
  protected function queryForListFilter(\SelectQuery $query) {
    parent::queryForListFilter($query);

    $query->condition('object_id', $this->blackList(), 'IN');

    if (!empty($this->path)) {
      $query->condition('object_id', $this->path);
    }
  }

  /**
   * Don't show all the variables but only the one who passed in the address.
   */
  public function getSpace() {
    if (empty($this->path)) {
      $this->throwException('You must provide the id of the variable.');
    }

    return parent::getSpace();
  }

}
