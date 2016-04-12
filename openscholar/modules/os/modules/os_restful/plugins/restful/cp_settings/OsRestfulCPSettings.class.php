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
        \RestfulInterface::HEAD => 'getAllForms'
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

  /**
   * Handle activating the space for access and variable override purposes
   * @return bool - TRUE if the space activated
   */
  protected function activateSpace() {
    if ($this->request['vsite'] && $vsite = vsite_get_vsite($this->request['vsite'])) {
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