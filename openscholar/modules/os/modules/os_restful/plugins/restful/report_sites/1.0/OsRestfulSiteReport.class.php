<?php

/**
 * @file
 * Contains \OsRestfulSiteReport
 */
class OsRestfulSiteReport extends \OsRestfulReports {

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

    $query->innerJoin('node', 'n', "purl.id = n.nid AND provider = 'spaces_og'");
    $query->addField('n', 'title');
    if (isset($fields['owner_email'])) {
      $query->addField('u', 'mail', 'owner_email');
      $query->innerJoin('users', 'u', "u.uid = n.uid");
    }

    $this->queryForListSort($query);
    $this->queryForListFilter($query);
    $this->queryForListPagination($query);
    $this->addExtraInfoToQuery($query);

    return $query;
  }

}