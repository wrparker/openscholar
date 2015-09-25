<?php
/**
 * @file Contains the OsRestfulEntityCacheableBase class.
 *
 * This class alows for clients to implement a permanent caching system, and only fetch updates for the entity in question
 */

class OsRestfulEntityCacheableBase extends RestfulEntityBase {

  public static function controllersInfo() {
    return array(
      'updates\/\d*$' => array(
        RestfulInterface::GET => 'getUpdates',
        RestfulInterface::HEAD => 'getUpdates'
      )
    ) + parent::controllersInfo();
  }

  /**
   * Returns all entities that have been updated since the timestamp given
   */
  public function getUpdates($path) {
    $timestamp = str_replace('updates/', '', $path);

    if ($timestamp < strtotime('-30 days')) {
      return $this->getList();
    }

    $request = $this->getRequest();

    $entity_type = $this->entityType;
    $result = $this
      ->getQueryForUpdates($timestamp)
      ->execute();

    $return = array();
    if (!empty($result[$entity_type])) {
      $ids = array_keys($result[$entity_type]);

      // Pre-load all entities if there is no render cache.
      $cache_info = $this->getPluginKey('render_cache');
      if (!$cache_info['render']) {
        entity_load($entity_type, $ids);
      }

      $return = array();

      // If no IDs were requested, we should not throw an exception in case an
      // entity is un-accessible by the user.
      foreach ($ids as $id) {
        if ($row = $this->viewEntity($id)) {
          $return[] = $row;
        }
      }
    }

    return $return;
  }

  public function getQueryForUpdates($timestamp) {
    $info = $this->getEntityInfo();
    $entity_type = $this->getEntityType();
    $query = $this->getEntityFieldQuery();

    $this->queryForListSort($query);
    $this->queryForListFilter($query);
    $this->queryForListPagination($query);
    $this->addExtraInfoToQuery($query);

    if (in_array('changed', $info['schema_fields_sql']['base table'])) {
      $query->propertyCondition('changed', (int)$timestamp, '>');
    }
    return $query;
  }
}

?>