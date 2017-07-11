<?php

class Grouper extends \RestfulBase implements \RestfulDataProviderInterface {

  /**
   * Overrides \RestfulBase::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        // If they don't pass a menu-id then display nothing.
        \RestfulInterface::GET => 'getAllGroups',
        \RestfulInterface::HEAD => 'getAllGroups',
        \RestfulInterface::PUT => 'saveGroupToSite'
      ),
    );
  }

  public function publicFieldsInfo() {
    return array();
  }

  public function getAllGroups() {
    $huid = $this->getHUIDForUser();
    $cid = 'grouper:'.$huid;

    if ($cache = cache_get($cid)) {
      return $cache->data;
    }
    else {
      $grouper_url = variable_get('grouper_url', 'https://{user}:{pass}@grouper-ws.stage.iam.harvard.edu/grouper-ws-facade/v1/user/huid:{huid}/{accessLevel}');
      $replacements = array(
        '{user}' => variable_get('grouper_user', 'grouper-ws-service-account-at-open-scholar'),
        '{pass}' => variable_get('grouper_pass', 'grouper12345678'),
        '{huid}' => $huid,
        '{accessLevel}' => 'viewablegroups',
      );
      $grouper_url = strtr($grouper_url, $replacements);

      $response = drupal_http_request($grouper_url);
      if ($response->code == 200) {
        $raw_data = $response->data;
      }
      elseif ($response->code == -1) {
        // TODO: Remove when the service is actually working
        $raw_data = file_get_contents(drupal_get_path('module', 'grouper').'/test_data.json');
      }
      else {
        switch ($response->code) {
          case 403:
            throw new RestfulForbiddenException("Cannot access Grouper restful interface.");
            break;
        }
      }

      $cleaned = json_decode($raw_data);
      return $cleaned;
    }
  }

  public function saveGroupToSite() {
    if (!isset($_GET['vsite'])) {
      return;
    }
  }

  private function getHUIDForUser() {
    // TODO: Replace this with actual function
    return 70855038;
  }
}