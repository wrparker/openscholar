<?php

/**
 * @file
 * Contains \RestfulDataProviderDbQuery
 */

class OSRestfulCPMenu extends \RestfulBase implements \RestfulDataProviderInterface {

  /**
   * Overrides \RestfulBase::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        // If they don't pass a menu-id then display nothing.
        \RestfulInterface::GET => 'index',
        \RestfulInterface::HEAD => 'index',
        // POST
        \RestfulInterface::POST => 'create',
      ),
      // We don't know what the ID looks like, assume that everything is the ID.
      '^.*$' => array(
        \RestfulInterface::GET => 'getMenu',
        \RestfulInterface::HEAD => 'getMenu',
        \RestfulInterface::PUT => 'replace',
        \RestfulInterface::PATCH => 'update',
        \RestfulInterface::DELETE => 'remove',
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return array(
      'type' => array(
        'property' => 'type',
      ),
      'label' => array(
        'property' => 'label',
      ),
      'weight' => array(
        'property' => 'weight',
      ),
      'children' => array(
        'property' => 'children',
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function access() {
    $account = $this->getAccount();
    return user_access('adminsiter site configuration', $account);
  }

  /**
   * Verify the user's request has access CRUD in the current group.
   */
  public function checkGroupAccess() {
    $account = $this->getAccount();

    $vsite = null;
    if (!empty($this->request['vsite'])) {
      $vsite = $this->request['vsite'];
    }

    if ($vsite) {
      return user_access('administer spaces', $account) || og_is_member('node', $vsite, 'user', $account);
    } else {
      $this->throwException('The vsite ID is missing.');
    }

    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function index() {

    $this->throwException('You must provide the id of the menu.');

    return $return;
  }

  /**
   * View a menu.
   *
   * @param string $name_string
   *  the name of the menu you would like to retrieve.
   */
  public function getMenu($name_string) {

    $output = array();

    $function = "get_$name_string";
    if (method_exists($this, $function)) {
      $output = $this->$function();
      $user = $this->getAccount();

      drupal_alter('os_restful_cp_menu_'.$name_string, $output, $user);
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function viewMultiple(array $ids) {
    $this->notImplementedCrudOperation(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function update($id, $full_replace = FALSE) {
    $this->notImplementedCrudOperation(__FUNCTION__);
  }


  /**
   * {@inheritdoc}
   */
  public function create() {
    $this->notImplementedCrudOperation(__FUNCTION__);
  }


  /**
   * {@inheritdoc}
   */
  public function remove($id) {
    $this->notImplementedCrudOperation(__FUNCTION__);
  }

  /**
   * Builds the admin menu as a structured array ready for drupal_render().
   *
   * @return Array of links and settings relating to the admin menu.
   */
  protected function get_admin_panel() {

    $user = $this->getAccount();
    $vsite = $this->request['vsite'];
    $vsite_object = vsite_get_vsite($vsite);

    $bundles = os_app_info_declared_node_bundle();
    $type_info = node_type_get_types();
    $variable_controller = $this->getVariableController($vsite);
    $spaces_features = $variable_controller->get('spaces_features');

    foreach ($bundles as $bundle) {
      if (!og_user_access('node', $vsite, 'create ' . $bundle . ' content')) {
        continue;
      }

      //Check that the feature is enabled.
      $feature = os_get_app_by_bundle($bundle);
      if (empty($spaces_features[$feature])) {
        continue;
      }

      $type_url_str = str_replace('_', '-', $bundle);
      $add_links["{$bundle}"] = array(
        'label' => $type_info[$bundle]->name,
        'type' => 'link',
        'href' => "node/add/{$type_url_str}",
        'alt' => $type_info[$bundle]->description,
      );

      if (os_importer_importable_content($bundle)) {
          $import_links["{$bundle}"] = array(
          'label' => os_importer_importer_title($bundle),
          'type' => 'link',
          'href' => 'cp/os-importer/' . $bundle,
          'alt' => t("One time bulk import of @type content.",array('@type' => $type_info[$bundle]->name)),
        );
      }
    }

    $feature_settings = array();
    if (spaces_access_admin($user, $vsite_object)) {
      foreach (array_keys(array_filter($spaces_features)) as $feature) {
        $item = menu_get_item("cp/build/features/{$feature}");
        if ($item && $item['href'] == "cp/build/features/{$feature}") {
          $feature_settings["feature_{$feature}"] = array(
            'label' => $item['title'],
            'type' => 'link',
            'href' => "/".$item['href'],
          );
        }
      }
    }

    $structure = array(
      'content' => array(
        'label' => 'Site Content',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(
          'browse' => array(
            'label' => 'Browse',
            'type' => 'heading',
            'default_state' => 'collapsed',
            'children' => array(
              'content' => array(
                'label' => 'Content',
                'type' => 'link',
                'href' => '/cp/content'
              ),
              'files' => array(
                'label' => 'Files',
                'type' => 'link',
                'href' => '/cp/content/files'
              ),
//              @tbd v2
//              'widgets' => array(
//                'label' => 'Widgets',
//                'type' => 'link',
//                'href' => '/cp/content'
//              ),
              'tagging' => array(
                'label' => 'Tagging',
                'type' => 'link',
                'href' => '/cp/build/taxonomy'
              ),
            ),
          ),
          'add' => array(
            'label' => 'Add',
            'type' => 'heading',
            'default_state' => 'collapsed',
            'children' => $add_links,
          ),
          'import' => array(
            'label' => 'Import',
            'type' => 'heading',
            'default_state' => 'collapsed',
            'children' => $import_links,
          ),
        ),
      ),
      'menus' => array(
        'label' => 'Menus',
        'type' => 'link',
        'href' => '/cp/build/menu'
      ),
      'appearance' => array(
        'label' => 'Appearance',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(
            'themes' => array(
              'label' => 'Themes',
              'type' => 'link',
              'href' => '/cp/appearance'
            ),
            'layout' => array(
              'label' => 'Layout',
              'type' => 'link',
              'href' => '/cp/build/layout'
            ),
            'theme_settings' => array(
              'label' => 'Theme Settings',
              'type' => 'link',
              'href' => '/dev/null'
            ),
          ),
      ),
      'settings' => array(
        'label' => 'Settings',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(
          'app' => array(
            'label' => 'Apps',
            'type' => 'link',
            'href' => '/cp/apps'
          )
        ) + $feature_settings,
      ),
      'users_roles' => array(
        'label' => 'Users & Roles',
        'type' => 'link',
        'href' => '/cp/users'
      ),
      'help' => array(
        'label' => 'Help',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(
          'support' => array(
            'label' => 'Support',
            'type' => 'link',
            'href' => '/cp/support'
          ),
          'documentation' => array(
            'label' => 'Documentation',
            'type' => 'link',
            'href' => '/cp/welcome'
          ),
        ),
      ),
    );

    //Should we show this user the admin links?
    if (user_access('access administration pages',$user)) {
      $admin_menu = menu_tree_all_data('management', NULL, 2);
      $admin_menu = current($admin_menu);
      $admin_links = array();

      foreach ($admin_menu['below'] as $mi) {
        $link = $mi['link'];
        if ($link['hidden'] != 0) continue;
        $key = str_replace(" ", "_", strtolower($link['title']));
        $admin_links[$key] = array(
          'label' => $link['title'],
          'type' => 'link',
          'href' => "/{$link['href']}",
        );
      }

      $structure['admin'] = array(
        'label' => 'Admin',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => $admin_links,
      );
    }

    return $structure;

    if ($user->uid) {
      $links = array(
        'account' => array(
          'title' => t('<strong>@username</strong>', array('@username' => format_username($user))),
          'href' => 'user',
          'html' => TRUE,
          'description' => t('View or edit your user account'),
        ),
        'logout' => array(
          'title' => t('Log out'),
          'href' => 'user/logout',
          'description' => t('Log out of this site'),
        ),
      );
      if (arg(0) == 'user') {
        $links['account']['attributes']['class'][] = 'active';
      }

      if (module_exists('vsite') && user_access('subscribe as support team') && $vsite = vsite_get_vsite()) {
        if (!og_is_member('node', $vsite->id)) {
          $links['support_team_subscribe'] = array(
            'title' => t('Support @purl', array('@purl' => $vsite->group->purl)),
            'href' => 'group/node/' . $vsite->id . '/subscribe/vsite_support_expire',
            'description' => t('Subscribe to group for support'),
            'query' => drupal_get_destination()
          );
        }
        else {
          $links['support_team_unsubscribe'] = array(
            'title' => t('Unsubscribe @purl' , array('@purl' => $vsite->group->purl)),
            'href' => 'group/node/' . $vsite->id . '/unsubscribe',
            'description' => t('Un-Subscribe from group'),
            'query' => drupal_get_destination()
          );
        }
      }
    }
    else {
       $links = array(
        'login' => array(
          'title' => t('Log in'),
          'href' => 'user',
          'description' => t('Log in to this site'),
        ),
      );
    }
    $build['toolbar_right']['toolbar_user'] = array(
      '#theme' => 'links__toolbar_user',
      '#links' => $links,
      '#attributes' => array('id' => 'toolbar-user'),
    );

    $base_domain = variable_get('purl_base_domain');

    if (variable_get('vsite_register_welcome_message', FALSE)) {
      $links = array(
        'help' => array(
          'title' => t('Help'),
          // 'href' => 'admin/help',
          'href' => '',
          // 'query' => array('popup'=>'1'),
          'fragment' => 'overlay=cp/welcome',
          'external' => TRUE,
          'html' => TRUE,
          'description' => t('Get help on various topics'),
        ),
      );
    }
    else {
      $links = array(
        'help' => array(
          'title' => t('Help'),
          // 'href' => 'admin/help',
          'href' => !empty($base_domain) ? $base_domain . '/help/os_help/User-Documentation' : 'help/os_help/User-Documentation',
          // 'query' => array('popup'=>'1'),
          'html' => TRUE,
          'description' => t('Get help on various topics'),
        ),
      );
    }

    $build['toolbar_right']['toolbar_help'] = array(
      '#theme' => 'links',
      '#links' => $links,
      '#attributes' => array('id' => 'toolbar-help'),
    );

    // Add a "home" link.
    $link = array(
      'home' => array(
        'title' => '<span class="home-link">Home</span>',
        'href' => '<front>',
        'html' => TRUE,
        'description' => t('Return to your home page'),
      ),
    );
    $build['toolbar_left']['toolbar_home'] = array(
      '#theme' => 'links',
      '#links' => $link,
      '#attributes' => array('id' => 'toolbar-home'),
    );

    $links = array();
    $l = menu_get_item('cp/content');
    if ($l['access']) {
      $links['content'] = $l + array(
        '#paths' => array('node/add'),
        'attributes' => array(
          'data-drawer' => 'content-drawer',
        )
      );
    }else{
      //They have access to add content but not list
      $l = menu_get_item('cp/content/add');
      if ($l['access']) {
        $links['content'] = $l + array(
          '#paths' => array(),
          'attributes' => array(
            'data-drawer' => 'content-drawer',
          )
        );
      }
    }
    $l = menu_get_item('cp/build');
    if ($l['access']) {
      $l['title'] = t('Build');
      $links['site'] = $l + array(
        '#paths' => array(),
        'attributes' => array(
          'data-drawer' => 'site-drawer',
        )
      );
    }
    $l = menu_get_item('cp/appearance');
    if ($l['access']) {
      $links['appearance'] = $l + array(
        '#paths' => array(),
      );
    }
    $l = menu_get_item('cp/settings');
    if ($l['access']) {
      $links['settings'] = $l + array(
        '#paths' => array(),
      );
    }
    $l = menu_get_item('cp/users');
    if ($l['access']) {
      $l['title'] = t('People');
      $links['people'] = $l + array(
        '#paths' => array(),
      );
    }
    // Make sure the contact module is enabled
    // before adding in the link for cp/support.
    if(module_exists('contact')) {
      $l = menu_get_item('cp/support');
      if ($l['access']) {
        $links['support'] = $l + array(
          '#paths' => array(),
        );
      }
    }
    if (user_access('access toolbar')) {
      $links['admin'] = array(
        'title' => t('Admin'),
        'href' => 'admin',
        'html' => TRUE,
        'attributes' => array('alt' => t('Admin'), 'data-drawer' => 'admin-drawer'),
        '#paths' => array(),
        'description' => t('The global administration pages.'),
      );
    }

    $build['toolbar_left']['toolbar_menu'] = array(
      '#theme' => 'links',
      '#links' => $links,
      '#attributes' => array('id' => 'toolbar-menu', 'class' => array('drawer-links')),
    );

    // Add an anchor to be able to toggle the visibility of the drawer.
    $build['toolbar_toggle'] = array(
      '#theme' => 'toolbar_toggle',
      '#collapsed' => _toolbar_is_collapsed(),
      '#attributes' => array('class' => array('toggle')),
    );

    return $build;
  }

  protected function getVariableController($vsite) {

    $controller = FALSE;
    ctools_include('plugins');

    $plugin = ctools_get_plugins('spaces', 'plugins', 'spaces_controller_variable');
    if ($plugin && $class = ctools_plugin_get_class($plugin, 'handler')) {
      $controller = new $class('variable', 'og', $vsite);
    }

    return $controller;
  }
}
