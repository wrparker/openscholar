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
        'property' => 'username',
      ),
      'user_email' => array(
        'property' => 'user_email',
      ),
    );
  }

  public function get_role_report() {
	}


  /**
   * {@inheritdoc}
   */
  public function getQueryForList() {
    $entity_type = $this->getEntityType();
    $query = $this->getEntityFieldQuery();
    if ($path = $this->getPath()) {
      $ids = explode(',', $path);
      if (!empty($ids)) {
        $query->entityCondition('entity_id', $ids, 'IN');
      }
    }

    $this->queryForListSort($query);
    $this->queryForListFilter($query);
    $this->queryForListPagination($query);
    $this->addExtraInfoToQuery($query);

    return $query;
  }

}