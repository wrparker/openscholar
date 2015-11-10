<?php

/**
 * @file
 * Contains \OsRestfulUserReport
 */
abstract class OsRestfulUserReport extends \OsRestfulReports {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return array(
      'username' => array(
        'property' => 'username',
      ),
      'user_email' => array(
        'property' => 'user_email',
      ),
    );
  }

  public function get_user_role_report() {
	}

}