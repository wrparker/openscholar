<?php

class OsRestfulCPSettings extends \RestfulBase implements \RestfulDataProviderInterface {

  /**
   * Overrides \RestfulBase::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        // If they don't pass a menu-id then display nothing.
        \RestfulInterface::GET => 'getAllForms',
        \RestfulInterface::HEAD => 'getAllForms',
        \RestfulInterface::PUT => 'saveSettings'
      ),
      // We don't know what the ID looks like, assume that everything is the ID.
      '^.*$' => array(
        \RestfulInterface::GET => 'getForms',
        \RestfulInterface::HEAD => 'getForms'
      ),
    );
  }

  public function publicFieldsInfo() {
    return array();
  }

  public function getAllForms() {
    if ($this->activateSpace()) {
      return cp_get_setting_forms();
    }

    throw new RestfulForbiddenException("Vsite ID is required.");
  }

  public function getForms($args) {
    if ($this->activateSpace()) {
      $forms = explode(',', $args);
      $all = cp_get_setting_forms();

      $output = array();
      foreach ($all as $f) {
        if (in_array($f['group']['#id'], $forms)) {
          $output = $f;
        }
      }

      return $output;
    }

    throw new RestfulForbiddenException("Vsite ID is required.");
  }

  public function saveSettings() {
    if ($this->activateSpace()) {
      $forms = cp_get_setting_forms();

      foreach ($this->request as $var => $value) {
        if (!isset($forms[$var])) continue;
        if (!empty($forms[$var]['rest_submit']) && function_exists($forms[$var]['rest_submit'])) {
          $forms[$var]['rest_submit']($value);
        }
        elseif (!empty($forms[$var]['rest_trigger']) && function_exists($forms[$var]['rest_trigger'])) {
          if ($value) {
            $forms[$var]['rest_trigger']();
          }
        }
        else {
          $this->saveVariable($var, $value);
        }
      }

      return;
    }

    throw new RestfulForbiddenException("Vsite ID is required.");
  }

  private function saveVariable($var, $val) {
    if (!empty($this->request['vsite'])) {
      if ($vsite = vsite_get_vsite($this->request['vsite'])) {
        $vsite->controllers->variable->set($var, $val);
      }
    }
    else {
      variable_set($var, $val);
    }

  }

  /**
   * Handle activating the space for access and variable override purposes
   * @return bool - TRUE if the space activated
   */
  protected function activateSpace() {
    if ($_GET['vsite'] && $vsite = vsite_get_vsite($_GET['vsite'])) {
      // Make sure the Drupal $user account is the account Restful authenticated
      $account = $this->getAccount();
      spaces_set_space($vsite);
      $vsite->activate_user_roles();
      $vsite->init_overrides();
      return true;
    }
    return false;
  }

}