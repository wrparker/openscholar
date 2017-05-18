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
      $grouper_url = variable_get('grouper_url', 'https://grouper-ws.stage.iam.harvard.edu/grouper-ws/servicesRest/v2_3_000/groups');
      $request_body = array(
        'WsrestFindGroupsLiterequest' => array(
          'actAsSubjectIdentifier' => $huid,
          'group_name' => '%',
          'queryFilterType' => 'FIND_BY_GROUP_NAME_APPROXIMATE',
          'stemName' => 'harvard'
        )
      );

      $options = array(
        'data' => $request_body,
        'method' => 'POST'
      );

      $response = drupal_http_request($grouper_url, $options);
      print_r($response);
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