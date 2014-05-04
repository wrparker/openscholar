<?php

/**
 * Migrating plugins.
 */
class MigrateBoxes extends MigrateDestination {

  /**
   * @inheritdoc
   */
  public function __toString() {
    return "";
  }

  /**
   * @inheritdoc
   */
  public function fields() {
    return array();
  }

  /**
   * @inheritdoc
   */
  public function import(stdClass $object, stdClass $row) {
  }

  /**
   * @inheritdoc
   */
  public function getCreated() {
    return $this->numCreated;
  }

  /**
   * @inheritdoc
   */
  public function getUpdated() {
    return $this->numUpdated;
  }

  /**
   * @inheritdoc
   */
  public function resetStats() {
    $this->numCreated = 0;
    $this->numUpdated = 0;
  }
}
