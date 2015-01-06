<?php

class GroupNodeRestfulBase extends OsNodeRestfulBase {

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    unset($public_fields['body']);

    $public_fields['users'] = array(
      'property' => 'nid',
      'process_callbacks' => array(
        array($this, 'getGroupUsers'),
      ),
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

    $users = user_load_multiple(array_keys($results['user']));

    foreach ($users as $user) {
      $list[$user->uid] = array(
        'name' => $user->name,
        'email' => $user->mail,
      );
    }

    return $list;
  }

}
