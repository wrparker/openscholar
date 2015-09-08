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

    $bundles = os_app_info_declared_node_bundle();
    $type_info = node_type_get_types();
//Also Check Spaces features
    foreach ($bundles as $bundle) {
      if (!og_user_access('node', $vsite, 'create ' . $bundle . ' content')) {
        continue;
      }

      $type_url_str = str_replace('_', '-', $bundle);
      $add_links[] = array(
        'label' => $type_info[$bundle]->name,
        'type' => 'link',
        'href' => "node/add/{$type_url_str}",
        'alt' => $type_info[$bundle]->description,
      );
    }

    $structure = array(
      array(
        'label' => 'Site Content',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(
          array(
            'label' => 'Browse',
            'type' => 'heading',
            'default_state' => 'collapsed',
            'children' => array(
              array(
                'label' => 'Content',
                'type' => 'link',
                'href' => '/cp/content'
              ),
              array(
                'label' => 'Files',
                'type' => 'link',
                'href' => '/cp/content/files'
              ),
//              @tbd v2
//              array(
//                'label' => 'Widgets',
//                'type' => 'link',
//                'href' => '/cp/content'
//              ),
              array(
                'label' => 'Tagging',
                'type' => 'link',
                'href' => '/cp/build/taxonomy'
              ),
            ),
          ),
          array(
            'label' => 'Add',
            'type' => 'heading',
            'default_state' => 'collapsed',
            'children' => $add_links,
          ),
          array(
            'label' => 'Import',
            'type' => 'heading',
            'default_state' => 'collapsed',
            'children' => array(
              array(
              )
            ),
          ),
        ),
      ),
      array(
        'label' => 'Menus',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(),
      ),
      array(
        'label' => 'Appearance',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(),
      ),
      array(
        'label' => 'Settings',
        'type' => 'heading',
        'default_state' => 'collapsed',
        'children' => array(),
      ),
    );

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
}
