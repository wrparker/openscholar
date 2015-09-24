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
    return array($timestamp);
  }
}

?>