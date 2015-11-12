<?php

/**
 * @file
 * Contains \OsRestfulUserReport
 */
class OsRestfulUserReport extends \OsRestfulReports {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return array(
      'username' => array(
        'property' => 'name',
      ),
      'mail' => array(
        'property' => 'mail',
      ),
      'vsite_name' => array(
        'property' => 'title',
      ),
    );
  }

  public function get_role_report() {
    $results = $this->getQueryForList()->execute();
    $return = array();

    foreach ($results as $result) {
      $return[] = $this->mapDbRowToPublicFields($result);
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function getQueryForList() {
    $query = $this->getQuery();

    $query->addField('n', 'title', 'title');
    $query->addField('n', 'uid', 'owner');
    $query->addField('n', 'nid', 'vsite_id');
    $query->innerJoin("og_membership", "og", "og.entity_type = 'user' AND og.etid = users.uid");
    $query->innerJoin("node", "n", "og.entity_type = 'user' AND og.etid = users.uid AND og.gid = n.nid");
    $query->innerJoin("og_users_roles", "ogur", "ogur.uid = users.uid AND ogur.gid = og.gid");
    $query->groupBy("users.uid, n.title");

    $fields = $this->getPublicFields();
    if(isset($fields['role_name'])) {
      $query->addField('ogr', 'name', 'role_name');
      $query->innerJoin("og_role", "ogr", "ogr.group_bundle = n.type AND ogr.group_type = 'node' AND ogur.rid = ogr.rid");
    }

    $this->queryForListSort($query);
    $this->queryForListFilter($query);
    $this->queryForListPagination($query);
    $this->addExtraInfoToQuery($query);

    return $query;
  }


  /**
   * Overriding the query list filter method
   */
  protected function queryForListFilter(\SelectQuery $query) {
    parent::queryForListFilter($query);

    if ($this->keywordString) {
      $query->condition("users.mail", '%' . db_like($this->keywordString) . '%', "LIKE");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function mapDbRowToPublicFields($row) {
    $new_row = array(
      'username' => $row->name,
      'mail' => $row->mail,
      'vsite_name' => $row->title,
    );
    if (isset($row->role_name)) {
      if (strpos($row->role_name, "vsite") !== FALSE) {
        if ($row->uid == $row->owner) {
          $new_row['role_name'] = "Site Owner";
        }
        else {
          $new_row['role_name'] = "Administrator";
        }
      }
      else {
        $new_row['role_name'] = ucfirst($row->role_name);
      }
    }
    return $new_row;
  }

}