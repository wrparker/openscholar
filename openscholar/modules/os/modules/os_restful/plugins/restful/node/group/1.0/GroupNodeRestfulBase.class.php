<?php

class GroupNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/group Get
   * @apiVersion 0.1.0
   * @apiName Group
   * @apiGroup Group
   *
   * @apiDescription
   * OpenScholar uses [Organic Groups](http://drupal.org/project/og) in order to have content attached to vsite.
   * Actually, each vsite is a group. This give us the options to override
   * site wide settings(roles and permission).
   *
   * There are 3 types of groups: personal, project and department when personal
   * can have sub-sites.
   *
   * @apiSampleRequest api/group
   *
   * @apiParam {Number} id The group ID
   *
   * @apiSuccess {Number}   id          The publication ID.
   * @apiSuccess {String}   label       Registration Date.
   * @apiSuccess {Object[]} users       The group users.
   * @apiSuccess {Integer}  users.id    The user ID.
   * @apiSuccess {Integer}  users.name  The user name.
   * @apiSuccess {String}   preset      The group preset.
   * @apiSuccess {String}   purl        The group persistent URL.
   * @apiSuccess {String}   type        The group type(i.e node type).
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    unset($public_fields['body']);

    $public_fields['users'] = array(
      'property' => 'nid',
      'process_callbacks' => array(
        array($this, 'getGroupUsers'),
      ),
    );

    $public_fields['preset'] = array(
      'property' => 'preset',
    );

    $public_fields['purl'] = array(
      'property' => 'domain',
    );

    $public_fields['type'] = array(
      'property' => 'type',
    );

    return $public_fields;
  }

  /**
   * @param EntityFieldQuery $query
   *
   * Overriding the query list filter. Since this is a group a handler we need
   * to select nodes of 3 types: personal, project, department AKA group.
   */
  public function queryForListFilter(\EntityFieldQuery $query) {
    parent::queryForListFilter($query);

    $query->propertyCondition('type', array('personal', 'project', 'department'), 'IN');
  }

  /**
   * Return all the users for this group.
   */
  public function getGroupUsers($value) {
    $query = new EntityFieldQuery();
    $results = $query
      ->entityCondition('entity_type', 'user')
      ->fieldCondition(OG_AUDIENCE_FIELD, 'target_id', $value)
      ->execute();

    $list = array();

    if (empty($results['user'])) {
      return $list;
    }

    $accounts = user_load_multiple(array_keys($results['user']));

    foreach ($accounts as $account) {
      $list[] = array(
        'uid' => $account->uid,
        'name' => $account->name,
      );
    }

    return $list;
  }

  /**
   * {@inheritdoc}
   */
  protected function setPropertyValues(EntityMetadataWrapper $wrapper, $null_missing_fields = FALSE) {
    $request = $this->getRequest();
    self::cleanRequest($request);
    $wrapper->type->set($request['type']);

    parent::setPropertyValues($wrapper, $null_missing_fields);
    $id = $wrapper->getIdentifier();

    if (!$space = vsite_get_vsite($id)) {
      return;
    }

    // Set the preset on the object.
    if ($request['preset']) {
      $space->controllers->variable->set('spaces_preset_og', $request['preset']);
    }

    if ($purl = $wrapper->domain->value()) {
      $modifier = array(
        'provider' => 'spaces_og',
        'id' => $id,
        'value' => $purl,
      );
      purl_save($modifier);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access() {
    if (!parent::access()) {
      return;
    }

    $account = $this->getAccount();
    $item = explode("/", $_GET['q']);

    $gid = end($item) ? NULL : $item['page_arguments'][2];
    $access_callback = $gid ? 'og_user_access' : 'user_access';
    $access_arguments = $gid ? array('node', $gid, 'administer users', $account) : array('administer users', $account);

    return call_user_func_array($access_callback, $access_arguments);
  }

  /**
   * @api {post} api/group Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Group
   *
   * @apiDescription Create a group entry.
   *
   * @apiParam {Number} id The group ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/group/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Group
   *
   * @apiDescription Delete a group entry.
   *
   * @apiParam {Number} id The group ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/group/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Group
   *
   * @apiDescription Update a group entry.
   *
   * @apiParam {Number} id The group ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
