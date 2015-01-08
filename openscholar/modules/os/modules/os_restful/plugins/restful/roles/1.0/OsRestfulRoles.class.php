<?php

/**
 * @file
 * Contains \RestfulQueryVariable
 */

class OsRestfulRoles extends \RestfulDataProviderDbQuery implements \RestfulDataProviderDbQueryInterface, \RestfulDataProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return array(
      'rid' => array(
        'property' => 'rid',
      ),
      'name' => array(
        'property' => 'name',
      ),
    );
  }

  /**
   * Overrides RestfulDataProviderDbQuery::create().
   *
   * Verify the uer have permission to invoke this method.
   */
  public function create() {
    if (!user_access('administer permissions', $this->getAccount())) {
      throw new \RestfulForbiddenException('You are not allowed to manage roles.');
    }

    parent::create();
  }

  /**
   * Overrides RestfulDataProviderDbQuery::update().
   *
   * Verify the uer have permission to invoke this method.
   */
  public function update($id, $full_replace = FALSE) {
    if (!user_access('administer permissions', $this->getAccount())) {
      throw new \RestfulForbiddenException('You are not allowed to manage roles.');
    }

    parent::update($id, $full_replace);
  }

  /**
   * Overrides RestfulDataProviderDbQuery::delete().
   *
   * Verify the uer have permission to invoke this method.
   */
  public function delete($path = '', array $request = array()) {
    if (!user_access('administer permissions', $this->getAccount())) {
      throw new \RestfulForbiddenException('You are not allowed to manage roles.');
    }

    parent::delete();
  }

}
