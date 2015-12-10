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
      'site_creation_date' => array(
        'property' => 'site_creation_date',
      ),
      'site_update_date' => array(
        'property' => 'site_update_date',
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

    $query->innerJoin('node', 'n', 'purl.id = n.nid AND provider = :provider', array(':provider' => 'spaces_og'));
    $query->addField('n', 'title');
    $query->addField('n', 'created', 'site_creation_date');
    $query->addField('u', 'mail', 'owner_email');
    $query->innerJoin('users', 'u', 'u.uid = n.uid');
    $query->addExpression('MAX(content.changed)', 'site_update_date');
    $query->leftJoin('og_membership', 'ogm', "ogm.gid = purl.id AND ogm.group_type = 'node' AND ogm.entity_type = 'node'");
    $query->leftJoin('node', 'content', "ogm.etid = content.nid and content.type NOT IN ('" . implode("','", $this->excludedContentTypes) . "')");
    $query->groupBy('purl.id');
    if ($this->latestUpdate) {
      $query->havingCondition('site_update_date', strtotime($this->latestUpdate), '<=');
    }
    if (isset($fields['privacy'])) {
      $query->addField('access', 'group_access_value', 'privacy');
      $query->innerJoin('field_data_group_access', 'access', 'access.entity_id = purl.id');
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
    if (isset($new_row['site_update_date'])) {
      if ($new_row['site_update_date']) {
        $new_row['site_update_date'] = date('M j, Y h:ia', $row->site_update_date);
      }
    }
    if (isset($new_row['site_creation_date'])) {
      $new_row['site_creation_date'] = date('M j, Y h:ia', $row->site_creation_date);
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
