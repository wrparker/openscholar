<?php
/**
 * @file Contains the OsRestfulEntityCacheableBase class.
 *
 * This class alows for clients to implement a permanent caching system, and only fetch updates for the entity in question
 */

abstract class OsRestfulEntityCacheableBase extends RestfulEntityBase {

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

    $q = db_select('entities_deleted', 'ed')
      ->fields('ed')
      ->condition('entity_type', $entity_type)
      ->condition('deleted', (int)$timestamp, '>')
      ->execute();

    $deleted = array();

    foreach ($q as $r) {
      $deleted[] = array(
        'id' => $r->entity_id,
        'status' => 'deleted',
        'extra' => unserialize($r->extra)
      );
    }

    drupal_alter('os_restful_deleted_entities', $deleted, $this);
    $return = array_merge($return, $deleted);

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

  /**
   * @param $id - the entity of the id to retrieve the last modified timestamp for
   * @return mixed - either a timestamp with the last time this entity was changed, or FALSE if the entity no longer exists
   */
  abstract protected function getLastModified($id);

  // Override these functions to allow us to act before any actions are performed
  protected function updateEntity($id, $null_missing_fields = FALSE) {
    $unmodified = \RestfulManager::getRequestHttpHeader('If-Unmodified-Since');
    if ($unmodified) {
      $modified = $this->getLastModified($id);
      if ($modified === FALSE) {
        throw new RestfulGoneException(t("Entity @id has been deleted.", array('@id' => $id)), 410);
      }
      if (strtotime($unmodified) < $modified) {
        throw new RestfulException(t("Entity @id has been modified since updates were last retrieved.", array('@id' => $id)), 409);
      }
    }
    return parent::updateEntity($id, $null_missing_fields);
  }

  public function deleteEntity($entity_id) {
    $unmodified = \RestfulManager::getRequestHttpHeader('If-Unmodified-Since');
    if ($unmodified) {
      $modified = $this->getLastModified($entity_id);
      if ($modified === FALSE) {
        throw new RestfulGoneException(t("Entity @id has been deleted.", array('@id' => $entity_id)), 410);
      }
      if (strtotime($unmodified) < $modified) {
        throw new RestfulException(t("Entity @id has been modified since updates were last retrieved.", array('@id' => $entity_id)), 409);
      }
    }
    return parent::deleteEntity($entity_id);
  }

  protected function reject($timestamp = null) {
    static $rejected = [];
    $rejected['timestmap'] = $timestamp;
    return $rejected;
  }

  public function additionalHateoas() {
    $addtl = array();
    $path = $this->getPath();

    $rejects = $this->reject();
    if (!count($rejects)) {
      $addtl['updatedOn'] = $rejects['timestamp'];
    }
    if ($this->method == \RestfulInterface::GET) {
      $timestamp = str_replace('updates/', '', $path);
      if ($timestamp == $path) {
        $addtl['allEntitiesAsOf'] = REQUEST_TIME;
      }
      if ($timestamp < strtotime('-30 days')) {
        $addtl['allEntitiesAsOf'] = REQUEST_TIME;
      } else {
        $addtl['updatesAsOf'] = REQUEST_TIME;
      }
    }

    return $addtl;
  }
}

?>