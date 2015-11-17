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
    );
  }

  public function get_attributes_report() {
    $request = $this->getRequest();
    if (isset($request['lastupdate'])) {
      $fields = $this->getPublicFields();
      $fields['content_last_updated'] = array("property" => 'last_changed');
      $this->setPublicFields($fields);
      $this->latestUpdate = $request['lastupdate'];
      if (isset($request['exclude'])) {
        $this->excludedContentTypes = $request['exclude'];
      }
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
   */
  public function getQueryForList() {
    $query = $this->getQuery();
    $fields = $this->getPublicFields();

    $query->innerJoin('node', 'n', 'purl.id = n.nid AND provider = :provider', array(':provider' => 'spaces_og'));
    $query->addField('n', 'title');
    if (isset($fields['owner_email'])) {
      $query->addField('u', 'mail', 'owner_email');
      $query->innerJoin('users', 'u', 'u.uid = n.uid');
    }
    if (isset($this->latestUpdate)) {
      $query->addExpression('MAX(content.changed)', 'latest_change');
      $query->innerJoin('og_membership', 'ogm', "ogm.gid = purl.id AND ogm.entity_type = 'node' AND ogm.group_type = 'node'");
      $query->innerJoin('node', 'content', "ogm.etid = content.nid and content.type NOT IN ('" . implode("','", $this->excludedContentTypes) . "')");
      $query->groupBy('ogm.gid');
      $query->havingCondition('latest_change', strtotime($this->latestUpdate), '<=');
    }

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
    $new_row = parent::mapDbRowToPublicFields($row);
    if (isset($new_row['content_last_updated'])) {
      $new_row['content_last_updated'] = date('M j, Y h:ia', $row->latest_change);
    }
    return $new_row;
  }
}
