<?php

/**
 * Contains static methods for os search solr update 7005.
 */
class update7005 extends AbstractUpdate {

  /**
   * @inheritdoc
   */
  public static function Iterator($entity) {
    ctools_include('layout', 'os');
    $vsite = vsite_get_vsite($entity->nid);
    spaces_set_space($vsite);

    foreach (['os_front', 'os_public'] as $context) {
      if (!$blocks = os_layout_get($context, TRUE, TRUE, $vsite)) {
        continue;
      }

      if (!in_array('boxes-solr_search_box', array_keys($blocks))) {
        continue;
      }

      unset($blocks['boxes-solr_search_box']);
      os_layout_set($context, $blocks, $vsite);
    }
  }
}
