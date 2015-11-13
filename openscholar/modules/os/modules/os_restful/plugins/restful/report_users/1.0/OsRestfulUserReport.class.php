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
    if ($this->userRoles != "owners") {
      $query->innerJoin("node", "n", "og.entity_type = 'user' AND og.etid = users.uid AND og.gid = n.nid");
    }
    else {
          $query->innerJoin("node", "n", "og.entity_type = 'user' AND og.etid = users.uid AND og.gid = n.nid AND users.uid = n.uid");
    }
    $query->innerJoin("og_users_roles", "ogur", "ogur.uid = users.uid AND ogur.gid = og.gid");
    $query->groupBy("users.uid, n.nid");

    $fields = $this->getPublicFields();
    if(isset($fields['role_name'])) {
      $query->addField('ogr', 'name', 'role_name');
      $query->innerJoin("og_role", "ogr", "ogr.group_bundle = n.type AND ogr.group_type = 'node' AND ogur.rid = ogr.rid");
    }
    if(isset($fields['fname'])) {
      $query->addField('ffname', 'field_first_name_value', 'fname');
      $query->leftJoin("field_data_field_first_name", "ffname", "ffname.entity_type = 'user' AND ffname.entity_id = users.uid");
    }
    if(isset($fields['lname'])) {
      $query->addField('flname', 'field_last_name_value', 'lname');
      $query->leftJoin("field_data_field_last_name", "flname", "flname.entity_type = 'user' AND flname.entity_id = users.uid");
    }
    if(isset($fields['latest_content'])) {
      $query->addExpression('0', "latest_content");
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

    // add in keyword search on requested fields
    if ($this->keywordString) {
      $keyword_or = db_or();
      foreach ($this->keywordFields as $field) {
        $keyword_or->condition($field, '%' . db_like($this->keywordString) . '%', "LIKE");
       }
      $query->condition($keyword_or);
    }

    // limit by role, if needed
    // radio buttons: only site owners, site owners/admins, all users
    if ($this->userRoles == "admins") {
      $query->condition("ogr.name", '%' . db_like("vsite") . '%', "LIKE");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function mapDbRowToPublicFields($row) {
    $new_row = parent::mapDbRowToPublicFields($row);

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
    if (isset($row->latest_content)) {
      $query = db_select("node", "n")
               ->condition('n.uid', $row->uid, '=');
       $query->innerJoin('og_membership', 'og', "og.etid = n.nid AND og.gid = '" . $row->vsite_id . "'");
      $query->addExpression('MAX(n.changed)');
      $date = $query->execute()->fetchField();
      if ($date) {
        $new_row['latest_content'] = date('M j, Y h:ia', $date);
      }
      else {
        $new_row['latest_content'] = "";
      }
    }
    return $new_row;
  }

}