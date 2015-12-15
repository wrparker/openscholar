<?php

/**
 * @file
 * Contains \OsRestfulSiteReport
 */
class OsRestfulSiteReport extends \OsRestfulReports {

  /**
   * @var string
   *
   * The timestamp representing the cutoff point for site content updates.
   */
  protected $latestUpdate = '';

  /**
   * @var array
   *
   * The content types that should not be included in the latest updated content search.
   */
  protected $excludedContentTypes = array();

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return array(
      'site_name' => array(
        'property' => 'title',
      ),
      'site_url' => array(
        'property' => 'id',
      ),
      'owner_email' => array(
        'property' => 'owner_email',
      ),
      'installation' => array(
        'property' => 'installation',
      ),
    );
  }

  public function runReport() {
    $request = $this->getRequest();
    $this->latestUpdate = $request['lastupdatebefore'];
    if (isset($request['exclude'])) {
      $this->excludedContentTypes = $request['exclude'];
    }

    $results = $this->getQueryForList()->execute();
    $return = array();

    foreach ($results as $result) {
      $return[] = $this->mapDbRowToPublicFields($result);
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   *
   * add additional fields and table joins
   */
  public function getQueryForList() {
    global $base_url;
    $query = $this->getQuery();
    $fields = $this->getPublicFields();
    $request = $this->getRequest();

    $query->addField('n', 'title');

    if (isset($fields['created']) || isset($request['creationstart']) || isset($request['creationend'])) {
      $query->addField('n', 'created');
      $joinCondition = 'purl.id = n.nid AND provider = :provider';
      $arguments = array(':provider' => 'spaces_og');

      if (!isset($fields['created'])) {
        $fields['created'] = array('property' => 'created');
        $this->setPublicFields($fields);
      }

      if (isset($request['creationstart'])) {
        $joinCondition .= " AND n.created >= UNIX_TIMESTAMP(STR_TO_DATE(:startdate, '%Y%m%d'))";
        $arguments[':startdate'] = $request['creationstart'];
      }
      if (isset($request['creationend'])) {
        $joinCondition .= " AND n.created <= UNIX_TIMESTAMP(STR_TO_DATE(:enddate, '%Y%m%d'))";
        $arguments[':enddate'] = $request['creationend'];
      }
      $query->innerJoin('node', 'n', $joinCondition, $arguments);
    }
    else {
      $query->innerJoin('node', 'n', 'purl.id = n.nid AND provider = :provider', array(':provider' => 'spaces_og'));
    }

    $query->addField('u', 'mail', 'owner_email');
    $query->innerJoin('users', 'u', 'u.uid = n.uid');

    if ($request['includesites'] == "all") {
      $query->addExpression('MAX(content.changed)', 'changed');
      $query->leftJoin('og_membership', 'ogm', "ogm.gid = purl.id AND ogm.group_type = 'node' AND ogm.entity_type = 'node'");
      $query->leftJoin('node', 'content', "ogm.etid = content.nid and content.type NOT IN ('" . implode("','", $this->excludedContentTypes) . "')");
      $query->groupBy('purl.id');
    }
    elseif ($fields['changed'] || $request['includesites'] == "content"){
      $query->addExpression('MAX(content.changed)', 'changed');
      $query->innerJoin('og_membership', 'ogm', "ogm.gid = purl.id AND ogm.group_type = 'node' AND ogm.entity_type = 'node'");
      $query->innerJoin('node', 'content', "ogm.etid = content.nid and content.type NOT IN ('" . implode("','", $this->excludedContentTypes) . "')");
      $query->groupBy('purl.id');
    }
    elseif ($request['includesites'] == "nocontent"){
      $query->addExpression('COUNT(etid)', 'total');
      $query->leftJoin('og_membership', 'ogm', "ogm.gid = purl.id AND ogm.group_type = 'node' AND ogm.entity_type = 'node'");
      $query->groupBy('purl.id');
      $query->havingCondition('total', '0', '=');
      if ($this->latestUpdate) {
        $fields['changed'] = array('property' => 'changed');
        $query->addExpression('NULL', 'changed');
        $this->setPublicFields($fields);
      }
    }

    if ($this->latestUpdate && $request['includesites'] != "nocontent") {
      $query->havingCondition('changed', strtotime($this->latestUpdate), '<=');
      $fields['changed'] = array('property' => 'changed');
      $this->setPublicFields($fields);
    }
    if (isset($fields['privacy'])) {
      $query->addField('access', 'group_access_value', 'privacy');
      $query->innerJoin('field_data_group_access', 'access', 'access.entity_id = purl.id');
    }
    if (isset($fields['subdomain'])) {
      $query->addField('u', 'mail', 'subdomain');
    }

    $url_parts = explode(".", str_replace("http://", "", $base_url));
    $query->addExpression("'" . $url_parts[0] . "'", 'installation');

    $this->queryForListSort($query);
    $this->queryForListFilter($query);
    $this->queryForListPagination($query);
    $this->addExtraInfoToQuery($query);

    return $query;
  }

  /**
   * {@inheritdoc}
   *
   * adds logic to handle site roles and latest updated content, if needed
   */
  public function mapDbRowToPublicFields($row) {
    global $base_url;
    $new_row = parent::mapDbRowToPublicFields($row);

    if (isset($new_row['changed'])) {
      if ($new_row['changed']) {
        $new_row['changed'] = date('M j, Y h:ia', $row->changed);
      }
    }
    if (isset($new_row['created'])) {
      $new_row['created'] = date('M j, Y h:ia', $row->created);
    }
    if (isset($new_row['subdomain'])) {
      $domain_parts = explode(".", preg_replace('/.*@/', "", $new_row['subdomain']));
      if (count($domain_parts) > 2 && in_array("harvard", $domain_parts) && in_array("edu", $domain_parts)) {
        $new_row['subdomain'] = implode(" ", array_slice($domain_parts, -4, count($domain_parts) - 2));
      }
      else {
        $new_row['subdomain'] = "";
      }
    }

    // check for custom domain
    $row->custom_domain = db_select('spaces_overrides', 'so')
                          ->fields('so', array('value'))
                          ->condition('id', $row->id, '=')
                          ->condition('type', 'og', '=')
                          ->condition('object_id', 'vsite_domain_name', '=')
                          ->condition('object_type', 'variable', '=')
                          ->condition('value', 'N;', '<>')
                          ->condition('value', 's:0:"";', '<>')
                          ->execute()
                          ->fetchField();
    if ($row->custom_domain) {
      $new_row['site_url'] = "http://" . unserialize($row->custom_domain);
    }
    else {
      $new_row['site_url'] = $base_url . "/" . $row->value;
    }

    if (isset($new_row['privacy'])) {
      $privacy_values = array(
        '0' => 'Public on the web.',
        '1' => 'Invite only during site creation.',
        '2' => 'Anyone with the link.',
        '4' => 'Harvard Community'
      );
      $new_row['privacy'] = $privacy_values[$row->privacy];
    }
    return $new_row;
  }
}
