<?php

/**
 * @file
 * Contains \OsRestfulSiteReport
 */
class OsRestfulSiteReport extends \OsRestfulReports {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return array(
      'site_name' => array(
        'property' => 'site_name',
      ),
      'site_owner' => array(
        'property' => 'site_owner',
      ),
    );
  }

  public function get_site_association_report() {
	}

}