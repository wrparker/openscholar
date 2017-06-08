<?php

/**
 * @file
 * Contains OsRestfulPurl.
 */

class OsRestfulPurl extends \RestfulBase implements \RestfulDataProviderInterface {

  /**
   * {@inheritdoc}
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        \RestfulInterface::POST => 'save_site',
      ),
      '^.*$' => array(
        \RestfulInterface::GET => 'check_exiting_sites',
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {}


  /**
   * Checking for existing sites.
   */
  public function check_exiting_sites($siteValue) {
    //Validate new vsite URL
    if (strlen($siteValue) < 3 || !valid_url($siteValue)) {
      return("Invalid");
    }
    else if (($purl = purl_load(array('value' => $siteValue, 'provider' => 'spaces_og'), TRUE)) || menu_get_item($siteValue)) {
      return("Not-Available");
    }
    else {
      return("Available");
    }
  }



  /**
   * Callback save to create site.
   */
  function save_site() {
    ctools_include('user', 'os');
    ctools_include('vsite', 'vsite');
    $values = &drupal_static('vsite_register_form_values');

    if($this->request['individualScholar'] != "") {
      $values['bundle'] = 'personal';
      $values['domain'] = $this->request['individualScholar'];
    } else if($this->request['projectLabSmallGroup'] != "") {
      $values['bundle'] = 'project';
      $values['domain'] = $this->request['projectLabSmallGroup'];
    } else {
      $values['bundle'] = 'department';
      $values['domain'] = $this->request['departmentSchool'];
    }
    $values['preset'] = $this->request['contentOption'];
    $values['vsite_private'] = $this->request['vsite'];

    $parent = FALSE;
    if($this->request['parent']){
      $parent = $this->request['parent'];
    }

    // The site has created on the behalf of a new user.
    $new_user = FALSE;

    // If the specified user account already exists...
    if ($values['vicarious_user'] && $values['existing_username']) {
      // Loads that user account as site owner.
      $site_owner = user_load_by_name($values['existing_username']);
    }
    elseif (($values['vicarious_user'] && !$values['existing_username']) || (!$values['vicarious_user'] && $values['name'])) {
      // Create user for current logged in user or on someone else's behalf.
      $user_options = array(
        'name' => $values['name'],
        'pass' => $values['password'],
        'mail' => $values['mail'],
        'status' => 1,
        'field_first_name' => $values['first_name'],
        'field_last_name' => $values['last_name'],
      );
      $site_owner = os_user_create($user_options);

      // We created a new user. After creating the vsite we'll grant him the vsite
      // admin role.
      $new_user = TRUE;

      // Send out an email to notify the user of the newly created account.
      // Email only sent if user requested to be notified of new account.
      // if ($values['notify']) {
      //  _user_mail_notify('register_no_approval_required', $site_owner);
      // }

      // Logs in as the new user, if we're not already logged in.
      global $user;
      if ($user->uid == 0) {
        $user = $site_owner;
        user_login_finalize($state);
      }
    }
    else {
      // Creates site for current logged in user. No need to create a new user.
      global $user;
      $site_owner = $user;
    }

    // Creates the vsite node.
    $name = $purl = $values['domain'];
    $author = $site_owner->uid;
    $bundle = $values['bundle'];
    $preset = $values['preset'];
    $visibility = isset($values['vsite_private']) ? $values['vsite_private'] : FALSE;
    $state['additional_settings'] = empty($state['additional_settings']) ? array() : $state['additional_settings'];
    $vsite = vsite_create_vsite($name, $purl, $author, $bundle, $preset, $parent, $visibility, $state['additional_settings']);
    if ($vsite) {
      $message = vsite_register_message($form, $values['domain']);
      $commands[] = ajax_command_replace('#submit-suffix', $message);
      $commands[] = ajax_command_remove('#edit-submit');

      // Grant the proper roles to the user.
      if ($new_user) {
        os_role_grant($site_owner->uid, 'vsite admin', $vsite->nid);
      }

      // If we have gotten to this point, then the vsite registration was success.
      // Clears the errors.
      drupal_get_messages('error');
    }
    else {
      $commands[] = _vsite_register_form_error();
    }

    // Check for a present queued og_tasks batch.
    $batch =& batch_get();
    if ($vsite && $batch) {
      // Run all the batch commands right now.
      $batch['progressive'] = FALSE;
      batch_process();
    }

    print $commands[0]['data'];
    exit;
  }
}