<?php

class RestfulAppform extends RestfulBase {

  /**
   * Overrides \RestfulBase::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        \RestfulInterface::GET => 'getAppStates',
        \RestfulInterface::PUT => 'setApPStates'
      ),
    );
  }

  public function getAppStates() {
    $this->activateSpace();

    $features = variable_get('spaces_features', array());
    $info = spaces_features('og');

    $output = array();
    foreach ($info as $name => $i) {
      $output[$name] = array(
        'title' => $i->info['name'],
        'description' => $i->info['description'],
        'enabled' => $features[$name] ? 1 : 0,
        'private' => $features[$name] ? $features[$name] - 1 : 0
      );
    }

    return $output;
  }

  public function setAppStates() {
    $values = $this->request['values'];
  }
}
