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
      $subquery = db_select('node');
      $subquery->condition('node.type', array('harvard_course', 'feed', 'feed_importer', 'person'), 'NOT IN');
      $subquery->innerJoin('og_membership', 'ogm', 'etid = nid AND entity_type = :type', array(':type' => 'node'));
      $subquery->addField('ogm', 'gid');
      $subquery->addExpression('FROM_UNIXTIME(MAX(changed))', 'latest');

      $query->addField('subq', 'latest', 'last_changed');
      $query->innerJoin($subqery, 'subq', 'gid = purl.id AND latest <= :cutoff', array(':cutoff' => strtotime($this->latestUpdate)));
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
    return $new_row;
  }
}
