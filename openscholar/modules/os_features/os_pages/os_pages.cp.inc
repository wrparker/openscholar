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
    'page_title' => 'Page',
  );
  $form_id = "node_edit_form";

  // create form elements

  $form_settings_title = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('Title'),
        '#weight' => 1,
        '#column' => 'left',
      );

  $form_settings_node_body = array(
        '#type' => 'textarea',
        '#default_value' => '',
        '#title' => t('Body'),
        '#weight' => 1,
        '#column' => 'left',
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
          '#column' => 'left',
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
        '#prefix' => "<div class='page-fieldset-header'><p>Publishing options</p></div>",
        '#column' => 'right',
      );

  $form_settings_url_alias_checkbox = array(
        '#type' => 'checkbox',
        '#default_value' => '',
        '#title' => t('Generate automatic URL alias'),
        '#prefix' => "<div class='page-fieldset-header'><p>URL path settings</p></div>",
        '#description' => t('Uncheck this to create a custom alias below.'),
        '#column' => 'right',
      );
  $form_settings_url_alias_path = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('URL alias'),
        '#description' => t('Uncheck this to create a custom alias below.'),
        '#column' => 'right',
      );
  $form_settings_menu_settings = array(
        '#type' => 'checkbox',
        '#default_value' => '',
        '#title' => t('Provide a menu link'),
        '#prefix' => "<div class='page-fieldset-header'><p>Menu settings</p></div>",
        '#column' => 'right',
      );

  $form_settings_post_created = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('Posted by'),
        '#prefix' => "<div class='page-fieldset-header'><p>Post Created/Edited By</p></div>",
        '#description' => t('You may change this if posting on behalf of someone else.'),
        '#column' => 'right',
      );

  $form_settings_post_created_on = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t('Posted on'),
        '#description' => t('Format: 2017-06-30 17:24:00 +0530. The date </br> format is YYYY-MM-DD and +0530 is the time </br> zone offset from UTC. Leave blank to use the time </br> of form submission.'),
        '#column' => 'right',
      );

  $form_settings_revision = array(
        '#type' => 'checkbox',
        '#default_value' => '',
        '#title' => t('Create new revision'),
        '#prefix' => "<div class='page-fieldset-header'><p>Revision information</p></div>",
        '#column' => 'right',
      );

  $form_settings_revision_log = array(
        '#type' => 'textarea',
        '#default_value' => '',
        '#title' => t('Revision log message'),
        '#description' => t('Provide an explanation of the changes you are </br> making. This will help other authors understand </br> your motivations.'),
        '#column' => 'right',
      );

  $form_settings_seo = array(
        '#type' => 'textarea',
        '#default_value' => '',
        '#title' => t('Meta description'),
        '#prefix' => "<div class='page-fieldset-header'><p>Search Engine Optimization (SEO)</p></div>",
        '#description' => t('Best if 150 characters or less. Used as a meta </br> description tag and instead of trimmed body in </br> certain lists.'),
        '#column' => 'right',
      );

  $form_settings_css = array(
        '#type' => 'textfield',
        '#default_value' => '',
        '#title' => t("Apply css class name to this page's body tag"),
        '#prefix' => "<div class='page-fieldset-header'><p>Apply css</p></div>",
        '#description' => t('Warning: this is to be used for custom themeing </br> purpose only.'),
        '#column' => 'right',
      );


  // attach the form elements with the settings array

  $settings['page_title'] = array(
    'group' => array(
      '#id' => $form_id,
      '#title' => $importer_menu_title['page_title'],
      '#weight' => -7,
      '#menu_parent' => 'add',
    ),
    'form' => $form_settings_title
  );

  $settings['page_body'] = array(
    'group' => array(
      '#id' => $form_id,
      '#title' => $importer_menu_title['page_title'],
      '#weight' => -7,
      '#menu_parent' => 'add',
    ),
    'form' => $form_settings_node_body
  );

  $settings['file_upload'] = array(
     'group' => array(
        '#id' => $form_id,
        '#title' => $importer_menu_title['page_title'],
        '#weight' => -6,
        '#menu_parent' => 'add',
      ),
        'form' => $form_settings_upload
      );


  $settings['page_publishing'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_checkboxex,
        );

  $settings['page_url_alias_checkbox'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_url_alias_checkbox,
        );

  $settings['page_url_alias_path'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_url_alias_path,
        );

  $settings['page_menu_settings'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_menu_settings,
        );

  $settings['page_post_created'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_post_created,
        );

  $settings['page_post_created_on'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_post_created_on,
        );

  $settings['page_post_revision'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_revision,
        );

  $settings['page_post_revision_log'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_revision_log,
        );

  $settings['page_seo'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_seo,
        );

  $settings['page_css'] = array(
          'group' => array(
            '#id' => $form_id,
            '#title' => $importer_menu_title['page_title'],
            '#weight' => -4,
            '#menu_parent' => 'add'
          ),
          'form' => $form_settings_css,
        );

  return $settings;
}
