<?php
/**
 * @file
 * os_importer.cp.inc
 */

/**
 * Implements hook_cp_settings().
 */
function os_importer_cp_settings() {
  $settings = array();
  $app_info = os_app_info();
  $purl = vsite_get_purl();
  $importer_menu_title = array(
    'blog' => 'Blog entry',
    'biblio' => 'Publication',
    'media_gallery' => 'Media Gallery',
    'faq' => 'FAQ',
    'software_project' => 'Software Project'
  );

  $importer_formats = array(
    'ical' => 'iCal',
    'rss' => 'RSS',
    'csv' => 'CSV'
  );

  $params = array(
    '!new-york-times' => l(t('New York Times'), 'http://www.nytimes.com/services/xml/rss/index.html'),
    '@wikipedia-url' => 'http://en.wikipedia.org/wiki/Character_encoding',
  );

  $rss_feed_helptext = 'Importing content from RSS feeds supports only news and blog content. ';
  $rss_feed_helptext .= 'For example: you can import a feed item from the !new-york-times rss news feeds.<br />';
  $rss_feed_helptext .= "When submitting the form, the import process will begin. After that, you can choose which news items you want to import into your site.";
  $rss_feed_helptext = t($rss_feed_helptext, $params);

  $encode_options = array(
    'utf-8' => t('UTF-8'),
    'utf-16' => t('UTF-16'),
    'utf-32' => t('UTF-32'),
    t('MS-Windows character sets') => array(
      'Windows-1250' => t('Central European languages that use Latin script'),
      'Windows-1251' => t('Cyrillic alphabets'),
      'Windows-1252' => t('Western languages'),
      'Windows-1253' => t('Greek'),
      'Windows-1254' => t('Turkish'),
      'Windows-1255' => t('Hebrew'),
      'Windows-1256' => t('Arabic'),
      'Windows-1257' => t('Baltic languages'),
      'Windows-1258' => t('Vietnamese'),
    ),
    t('ISO 8859') => array(
      'ISO 8859-1' => t('Western Europe'),
      'ISO 8859-2' => t('Western and Central Europe'),
      'ISO 8859-9' => t('Western Europe with amended Turkish character set'),
      'ISO 8859-14' => t('Celtic languages (Irish Gaelic, Scottish, Welsh)'),
    ),
  );

  foreach ($app_info as $type => $info) {
    if (empty($info['importers'])) {
      continue;
    }

    foreach ($info['browse_pages'] as $node_type => $app) {
      $import_format_option = array();

      if (!os_importer_importable_content($node_type)) {
        continue;
      }

      foreach ($info['importers'][$node_type] as $import_type => $feature) {
        $import_format_option[$import_type] = $importer_formats[$import_type];
      }

      if (count($import_format_option) > 1) {
        $form_settings = array(
          '#title' => t('Format'),
          '#type' => 'radios',
          '#options' => $import_format_option,
          '#weight' => -6,
          '#default_value' => 'csv',
          '#onclick_callback' => 'template_download_information',
        );
      } else {
        $single_option = array_values($import_format_option);
        $form_settings = array(
          '#type' => 'markup',
          '#markup' => '<label>Format</label>' . $single_option[0] . ' only',
          '#weight' => -6,
        );
      }

      $settings['os_importer_content_format-' . $node_type] = array(
        'group' => array(
          '#id' => 'os_importer_content_' . $node_type,
          '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
          '#weight' => -6,
          '#menu_parent' => 'import',
        ),
        'form' => $form_settings,
      );

      $template_markup = '<div class="import-help element-hidden rss-help-wrapper">' . $rss_feed_helptext . '</div>';
      $template_markup .= '<div class="import-help csv-help-wrapper">' . l(t('Download a template'), (isset($purl) ? $purl . '/' : '') . 'cp/os-importer/' . $node_type . '/template') . '</div>';

      $settings['os_importer_download_template-' . $node_type] = array(
        'group' => array(
          '#id' => 'os_importer_content_' . $node_type,
          '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
          '#weight' => -5,
          '#menu_parent' => 'import',
        ),
        'form' => array(
          '#type' => 'markup',
          '#default_value' => 'utf-8',
          '#markup' => $template_markup,
          '#weight' => -5,
        ),
      );
      
      $settings['os_importer_file_upload-' . $node_type] = array(
        'form' => array(
          '#type' => 'managed_file',
          '#custom_directive' => 'media-browser-field',
          '#custom_directive_parameters' => array(
            'cardinality' => 1,
            'panes' => array('upload', 'library'),
            'droppable_text' => t('Drag and drop file here.'),
            'upload_text' => t('Select file to Add'),
            'max-filesize' => '100kb',
            'extensions' => array('csv'),
            'types' => array('document'),
          ),
          '#upload_location' => $purl ? "public://{$purl}/files" : "public://",
          '#upload_validators' => array(
            'file_validate_extensions' => array('csv'),
            'file_validate_size' => array(2*1024*1024),
          ),
        ),
        'group' => array(
          '#id' => 'os_importer_content_' . $node_type,
          '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
          '#weight' => 3,
          '#menu_parent' => 'import',
        ),
      );

      $settings['os_importer_encode-' . $node_type] = array(
        'group' => array(
          '#id' => 'os_importer_content_' . $node_type,
          '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
          '#weight' => 4,
          '#menu_parent' => 'import',
        ),
        'form' => array(
          '#title' => t('Encoding'),
          '#type' => 'optgroup',
          '#options' => $encode_options,
          '#default_value' => 'utf-8',
          '#description' => t('Select the encoding of your file. For a full list of encodings you can visit <a href="@wikipedia-url">this</a> Wikipedia page.', $params),
          '#weight' => 4,
        ),
      );

      $settings['os_importer_submit-' . $node_type] = array(
        'group' => array(
          '#id' => 'os_importer_content_' . $node_type,
          '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
          '#weight' => 5,
          '#menu_parent' => 'import',
        ),
        'form' => array(
          '#value' => t('Submit'),
          '#type' => 'submit',
          '#weight' => 5,
        ),
        'rest_submit' => 'os_importer_file_submit',
        'submit_full_request' => TRUE,
      );
    }
  }  
  
  return $settings;
}


/**
 * Submit handler for os_importer_submit button for CSV/RSS/iCal import.
 */
 function os_importer_file_submit($request) {
  foreach($request as $var => $value) {
    if (strpos($var, 'os_importer_submit-') === 0) {
      $node_type = end(explode('-', $var));
    }
  }

  $import_file_fid = $request['os_importer_file_upload-' . $node_type];
  $import_file = file_load($import_file_fid);
  $encode = $request['os_importer_encode-' . $node_type];
  $extension = end(explode('.', $import_file->uri));
  $bundle = os_importer_importable_content($node_type);

  if (in_array($extension, array('csv', 'ical'))) {
    $source = feeds_source($bundle[$extension]);
    $fetcher_config = $source->getConfigFor($source->importer->fetcher);    
    $fetcher_config['source'] = $import_file->uri;
    $fetcher_config['file'] = $import_file;
    $fetcher_config['encode'] = $encode;
    $source->setConfigFor($source->importer->fetcher, $fetcher_config);
    $source->save();
    $source->startImport(); 
  }
  else {
    /*$node_type = $form_state['type'] == 'blog' ? 'blog_import' : 'feed_importer';
    $node = entity_create('node', array(
      'title' => $values['title'],
      'type' => $node_type,
      'language' => $language->language,
      'uid' => $user->uid,
    ));
    $wrapper = entity_metadata_wrapper('node', $node);
    $wrapper->field_rss_url->set($values['url']);
    $wrapper->save();*/
  }
}