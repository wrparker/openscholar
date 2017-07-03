<?php
/**
 * @file
 * os_pages.cp.inc
 */

/**
 * Implements hook_cp_settings().
 */
function os_pages_cp_settings() {
  $settings = array();

  $import_format_option = array( array('key' => 1, 'label' => t('Published to this site')),
    array('key' => 1, 'label' => t('Sticky at top of lists')),
    array('key' => 1, 'label' => t('Prevent this page from appearing in search engine results'))
  );

  $importer_menu_title = array(
    'node_page' => 'Create Page',
  );

  // create form elements

  $form_settings_title = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('Title'),
        '#weight' => 1,
        '#column' => 'left-node-page',
      );

  $form_settings_node_body = array(
        '#type' => 'textarea',
        '#default_value' => '',
        '#title' => t('Body'),
        '#weight' => 1,
        '#column' => 'left-node-page',
      );

  $form_settings_upload = array(
          '#type' => 'managed_file',
          '#title' => t('Attach File'),
          '#custom_directive' => 'media-browser-field',
          '#custom_directive_parameters' => array(
            'cardinality' => 1,
            'panes' => array('upload', 'library'),
            'hide_helpicon' => false,
            'upload_text' => t('Upload'),
            'droppable_text' => t('Drop Files to upload here.'),
            'max-filesize' => '500kb',
            //'types' => $extension_types,
          ),
          '#weight' => 5,
          '#column' => 'left-node-page',
          '#class' => array('csv-import-fields', 'os-importer-file-upload-wrapper'),
          '#upload_location' => $purl ? "public://{$purl}/files" : "public://",
          '#upload_validators' => array(
            'file_validate_extensions' => array('csv'),
            'file_validate_size' => array(2*1024*1024),
          ),
        );

  $form_settings_checkboxex = array(
        '#type' => 'checkboxes',
        '#options' => $import_format_option,
        '#default_value' => '',
        '#title' => '',
        '#prefix' => "<div class='node-page-fieldset-header'>Publishing options </br>(Published to this site)</div>",
        '#column' => 'right-node-page',
      );

  $form_settings_url_alias_checkbox = array(
        '#type' => 'checkbox',
        '#default_value' => '',
        '#title' => t('Generate automatic URL alias'),
        '#prefix' => "<div class='node-page-fieldset-header'>URL path settings</br>(Automatic alias)</div>",
        '#description' => t('Uncheck this to create a custom alias below.'),
        '#column' => 'right-node-page',
      );
  $form_settings_url_alias_path = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('URL alias'),
        '#description' => t('Uncheck this to create a custom alias below.'),
        '#column' => 'right-node-page',
      );
  $form_settings_menu_settings = array(
        '#type' => 'checkbox',
        '#default_value' => '',
        '#title' => t('Provide a menu link'),
        '#prefix' => "<div class='node-page-fieldset-header'>Menu settings</br>(Not in menu)</div>",
        '#column' => 'right-node-page',
      );

  $form_settings_post_created = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('Posted by'),
        '#prefix' => "<div class='node-page-fieldset-header'>Post Created/Edited By</br>(By admin)</div>",
        '#description' => t('You may change this if posting on behalf of someone else.'),
        '#column' => 'right-node-page',
      );

  $form_settings_post_created_on = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('Posted on'),
        '#description' => t('Format: 2017-06-30 17:24:00 +0530. The date </br> format is YYYY-MM-DD and +0530 is the time </br> zone offset from UTC. Leave blank to use the time </br> of form submission.'),
        '#column' => 'right-node-page',
      );

  $form_settings_revision = array(
        '#type' => 'checkbox',
        '#default_value' => '',
        '#title' => t('Create new revision'),
        '#prefix' => "<div class='node-page-fieldset-header'>Revision information</div>",
        '#column' => 'right-node-page',
      );

  $form_settings_revision_log = array(
        '#type' => 'textarea',
        '#default_value' => '',
        '#title' => t('Revision log message'),
        '#description' => t('Provide an explanation of the changes you are </br> making. This will help other authors understand </br> your motivations.'),
        '#column' => 'right-node-page',
      );

  $form_settings_seo = array(
        '#type' => 'textarea',
        '#default_value' => '',
        '#title' => t('Meta description'),
        '#prefix' => "<div class='node-page-fieldset-header'>Search Engine Optimization (SEO)</div>",
        '#description' => t('Best if 150 characters or less. Used as a meta </br> description tag and instead of trimmed body in </br> certain lists.'),
        '#column' => 'right-node-page',
      );

  $form_settings_css = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t("Apply css class name to this page's body tag"),
        '#prefix' => "<div class='node-page-fieldset-header'>Apply css</div>",
        '#description' => t('Warning: this is to be used for custom themeing </br> purpose only.'),
        '#column' => 'right-node-page',
      );


  // attach the form elements with the settings array

  $settings['page_title'] = array(
    'group' => array(
      '#id' => 'node_page',
      '#title' => $importer_menu_title['node_page'],
      '#weight' => -7,
      '#menu_parent' => 'add',
    ),
    'form' => $form_settings_title
  );

  $settings['page_body'] = array(
    'group' => array(
      '#id' => 'node_page',
      '#title' => $importer_menu_title['node_page'],
      '#weight' => -7,
      '#menu_parent' => 'add',
    ),
    'form' => $form_settings_node_body
  );

  $settings['os_page_file_upload'] = array(
     'group' => array(
        '#id' => 'node_page',
        '#title' => $importer_menu_title['node_page'],
        '#weight' => -6,
        '#menu_parent' => 'add',
      ),
        'form' => $form_settings_upload
      );


  $settings['node_page_publishing'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => 'Page',
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_checkboxex,
        );

  $settings['node_page_url_alias_checkbox'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_url_alias_checkbox,
        );

  $settings['node_page_url_alias_path'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_url_alias_path,
        );

  $settings['node_page_menu_settings'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_menu_settings,
        );

  $settings['node_page_post_created'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_post_created,
        );

  $settings['node_page_post_created_on'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_post_created_on,
        );

  $settings['node_page_post_revision'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_revision,
        );

  $settings['node_page_post_revision_log'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_revision_log,
        );

  $settings['node_page_seo'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_seo,
        );

  $settings['node_page_css'] = array(
          'group' => array(
            '#id' => 'node_page',
            '#title' => $importer_menu_title['node_page'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_css,
        );

  return $settings;
}
