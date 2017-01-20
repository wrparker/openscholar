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

    if (!is_array($info['browse_pages'])) {
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
	
	// Import format option should be displayed for all content types except biblio/publication.
      if ($node_type != 'biblio') {
		$settings['os_importer_content_format-' . $node_type] = array(
        'group' => array(
          '#id' => 'os_importer_content_' . $node_type,
          '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
          '#weight' => -6,
          '#menu_parent' => 'import',
        ),
        'form' => $form_settings,
      );

      $template_markup = '<div class="import-help element-hidden rss-import-fields">' . $rss_feed_helptext . '</div>';
      $template_markup .= '<div class="import-help csv-import-fields">' . l(t('Download a template'), (isset($purl) ? $purl . '/' : '') . 'cp/os-importer/' . $node_type . '/template') . '</div>';

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
	  }
      
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
          '#class' => array('csv-import-fields'),
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
		// Biblio specific select dropdown.
      if ($node_type == 'biblio') {
		$biblio_import_options = module_invoke_all('biblio_import_options');
		asort($biblio_import_options);
		$biblio_import_options = array_merge(array('0' => 'Select type'), $biblio_import_options);

		$settings['os_importer_filetype-' . $node_type] = array(
			'group' => array(
			  '#id' => 'os_importer_content_' . $node_type,
			  '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
			  '#weight' => 4,
			  '#menu_parent' => 'import',
			),
			'form' => array(
			  '#title' => t('File Type'),
			  '#type' => 'optgroup',
			  '#options' => $biblio_import_options,
			  '#default_value' => '0',
			  '#weight' => 4,
			  '#access' => biblio_access('import'),
			),
		  );
	  }
		
		
	// Encoding option should be displayed for all content types except biblio/publication.
      if ($node_type != 'biblio') {
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
			  '#class' => array('csv-import-fields'),
			),
		  );
	  }            

      // Only blog and news have RSS feed import option.
      if (in_array($node_type, array('blog', 'news'))) {
        $settings['os_importer_rss_title-' . $node_type] = array(
          'group' => array(
            '#id' => 'os_importer_content_' . $node_type,
            '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
            '#weight' => 4,
            '#menu_parent' => 'import',
          ),
          'form' => array(
            '#title' => t('Title'),
            '#type' => 'textfield',
            '#description' => t('Required field - The title of the feed source. E.g. "New York Times Sport".'),
            '#weight' => 4,
            '#class' => array('rss-import-fields', 'element-hidden'),
          ),
        );

        $settings['os_importer_rss_url-' . $node_type] = array(
          'group' => array(
              '#id' => 'os_importer_content_' . $node_type,
              '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
              '#weight' => 4,
             '#menu_parent' => 'import',
          ),
          'form' => array(
            '#title' => t('URL'),
            '#type' => 'textfield',
            '#description' => t('The url of your feed. E.g. http://rss.cnn.com/rss/cnn_tech.rss'),
            '#weight' => 4,
            '#class' => array('rss-import-fields', 'element-hidden'),
          ),
        );
      }

      $settings['os_importer_submit-' . $node_type] = array(
        'group' => array(
          '#id' => 'os_importer_content_' . $node_type,
          '#title' => isset($importer_menu_title[$node_type]) ? $importer_menu_title[$node_type] : ucfirst($node_type),
          '#weight' => 5,
          '#menu_parent' => 'import',
          '#group_validate' => 'os_importer_import_validate',
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

function os_importer_import_validate($values) {
  $valid = TRUE;
  // Determining node type from array key.
  foreach($values as $var => $value) {
    if (strpos($var, 'os_importer_submit-') === 0) {
      $part = explode('-', $var);
      $node_type = end($part);
      break;
    }
  }

   // For biblio content types validation
   if ($node_type == 'biblio') {
	    // If no files have been selected.
		if (empty($values['os_importer_file_upload-' . $node_type])) {
		  drupal_set_message(t('The file field is required.'), 'error');
		  $valid = FALSE;
		} else if ($values['os_importer_filetype-' . $node_type] == '0') {
		  // If no file type have been selected.
		  drupal_set_message(t('You did not select the file type.'), 'error');
		  $valid = FALSE;
		}
   }
  
  // Remove previous warnings and errors.
  drupal_get_messages();
  // For CSV import files.
  if (empty($values['os_importer_content_format-' . $node_type]) || $values['os_importer_content_format-' . $node_type] == 'csv') {
    // If no files have been selected.
    if (empty($values['os_importer_file_upload-' . $node_type])) {
      drupal_set_message(t('The file field is required.'), 'error');
      $valid = FALSE;
    } else if (!empty($values['os_importer_file_upload-' . $node_type])) {
      $file = file_load($values['os_importer_file_upload-' . $node_type]);      
      $part = !empty($file->filename) ? explode('.', $file->filename) : array();
      $extension = end($part);
      if ($extension != 'csv') {
        drupal_set_message(t('Please select a CSV file.'), 'error');
        $valid = FALSE;
      } else {

        if ($node_type == 'person') {
          feeds_include_library('ParserCSV.inc', 'ParserCSV');
          ini_set("auto_detect_line_endings", TRUE);
          $parser = new ParserCSV();
          $parser->setDelimiter(',');
          $iterator = new ParserCSVIterator($file->uri);
          $rows = $parser->parse($iterator);
          // Iterate over the rows for field_website validation.
          $valid_url = TRUE;
          foreach ($rows as $key => $row) {
            if ($key == 0) {
              continue;
            }

            // If title is not empty but url is empty.
            if (!empty($row[11]) && empty($row[12])) {
              $valid_url = FALSE;        
              break;
            }
            else if (!empty($row[13]) && empty($row[14])) {
              $valid_url = FALSE;        
              break;
            }
            else if (!empty($row[15]) && empty($row[16])) {
              $valid_url = FALSE;        
              break;
            }
          }

          if (!$valid_url) {
            drupal_set_message(t('The field Websites URL cannot be empty.'), 'error');
            $valid = FALSE;
          }
        }

        if ($node_type == 'event') {
          // Parse uploaded file content with ParserCSV class.          
          $file_content = file_get_contents($file->uri);
          $file_content = str_replace("\r", "\n", $file_content);
          file_put_contents($file->uri, $file_content);
          feeds_include_library('ParserCSV.inc', 'ParserCSV');
          $parser = new ParserCSV();
          $parser->setDelimiter(',');
          $iterator = new ParserCSVIterator($file->uri);
          $rows = $parser->parse($iterator);
          if (count($rows) < 2) {
            drupal_set_message(t('The import file does not contain any events.'), 'error');
            $valid = FALSE;
          }
        }
        // Validating the mapping of CSV headers.
        $bundle = os_importer_importable_content($node_type);
        $source = feeds_source($bundle['csv']);
        $config = $source->importer->getConfig();
        // Get the mappings of the current importer.
        $mappings_diff = array();
        $mappings = array();
        foreach ($config['processor']['config']['mappings'] as $mapping) {
          $mappings_diff[] = strtolower($mapping['source']);
          $mappings[] = $mapping['target'];
        }

        // Replace the \r with \n.
        $file_content = file_get_contents($file->uri);
        $file_content = str_replace("\r", "\n", $file_content);
        $file_rows = explode("\n", $file_content);
        file_put_contents($file->uri, $file_content);

        feeds_include_library('ParserCSV.inc', 'ParserCSV');
        $parser = new ParserCSV();
        $parser->setDelimiter(',');
        $iterator = new ParserCSVIterator($file->uri);

        $rows = $parser->parse($iterator);

        // Parse file's headers.
        $header = _os_importer_parse_header($parser, $iterator, $mappings);

        // Check what columns are missing from the uploaded CSV file and display
        // message if needed.
        if ($missing = array_diff($mappings_diff, $header)) {
          $params = array('!missing' => theme('item_list', array('items' => $missing)));
          drupal_set_message(t('The following columns are not present in the CSV file: !missing The structure of the your CSV file probably needs to be updated. Please download the template again.', $params), 'error');
          $valid = FALSE;
        }

        // Iterate over the rows for field validation and removing empty rows.
        foreach ($rows as $key => $row) {
          if (!$key) {
            // Check the header for bad vocabularies.
            foreach (array_diff(array_map('strtolower', $row), $mappings_diff) as $vocab) {
              if (!trim($vocab)) {
                drupal_set_message(t('You cannot have a vocabulary without a name.'), 'error');
                $valid = FALSE;
                continue;
              }
              if (preg_match('@[^a-z0-9_]+@', $vocab) && !str_replace(array(' ', '.', '&', '-'), '', strtolower($vocab))) {
                drupal_set_message(t('The vocabularies you want to import doesn\'t contain any text.'), 'error');
                $valid = FALSE;
                continue;
              }
            }
            continue;
          }

          // Check if row is empty.
          if (!implode("", $row)) {
            unset($rows[$key]);
            continue;
          }

          /** @var OsImporterEntityValidateBase $handler */
          if (!$handler = entity_validator_get_validator_handler('node', $node_type)) {
            continue;
          }

          $handler->rest = FALSE;

          $node = array(
            'type' => $node_type,
          );
          $node = entity_create('node', $node);
          $wrapper = entity_metadata_wrapper('node', $node);
          // Build the form values - break down the row.

          foreach ($row as $delta => $value) {
            // Check only the fields found in the mappings array. Columns after the
            // last mapping column are used to create taxonomy terms.
            // @see os_importer_feeds_presave().
            if ($delta >= count($mappings)) {
              break;
            }

            // Fail in case mapping does not exist.
            if (!isset($mappings[$delta])) {
              $params = array(
                '@delta' => $delta,
                '@value' => $value,
              );
              drupal_set_message(t('Mapping of CSV file is incorrect for delta @delta with value @value', $params), 'error');
              $valid = FALSE;
            }
            $field = $mappings[$delta];

            $field = str_replace(':', '__', $field);

            // Do not validate file fields.
            if ($field == 'file') {
              continue;
            }

            if ($field == 'body') {
              $value = array('value' => $value);
            }

            $info = $wrapper->{$field}->info();

            if (!is_array($value) && $info['type'] == 'list<text>') {
              $value = array($value);
            }
            elseif (!is_array($value) && $info['type'] == 'text_formatted') {
              $value = array('value' => $value);
            }
            elseif (empty($value) && in_array($info['type'], array('integer', 'decimal'))) {
              $value = 0;
            }
            elseif ($value && $info['type'] == 'integer') {
              $value = intval($value);
            }
            elseif ($field == 'created' && is_string($value)) {
              $orig_value_created = $value;
              $value = strtotime($value);
              // if strtotime() output is blank, then date format is not correct, displaying error message.
              if(empty($value) || ($value < 0)) {                
                drupal_set_message(t('Invalid date value entered for the field "!field" with value "!value".', array('!field' => $field, '!value' => $orig_value_created)), 'error');
                $valid = FALSE;
              }
            }

            try {
              $wrapper->{$field}->set($value);
            }
            catch (EntityMetadataWrapperException $e) {
              drupal_set_message(t('Settings the value of field "!field" with value "!value" failed.', array('!field' => $field, '!value' => $orig_value)));
              $valid = FALSE;
            }
          }

          if ($handler->validate($node, TRUE) === FALSE) {
            $errors = $handler->getErrors(FALSE);

            $return = array();

            // Replace the messages' placeholders with the CSV column name for better
            // validation error messages.
            foreach ($errors as $field => $field_errors) {
              foreach ($field_errors as $error) {
                if (!isset($error['params']['@field'])) {
                  continue;
                }

                // We take the label of the field (either the original label, or if
                // it's a property we added, then the one defined in
                // os_importer_entity_property_info_alter().
                $field_info = $wrapper->{$field}->info();
                $field_label = $field_info['label'];
                $error['params']['@field'] = $field_label;

                $return[] = format_string($error['message'], $error['params']) . '</br>';
              }
            }
            $return = array(
              '!errors' => implode("\n\r", $return),
            );
            drupal_set_message(t('Field validation errors:</br>!errors', $return));
            $valid = FALSE;
          }
        }
      }
    }
  } else if ($values['os_importer_content_format-' . $node_type] == 'ical') {
    // If no files have been selected.
    if (empty($values['os_importer_file_upload-' . $node_type])) {
      drupal_set_message('The file field is required.', 'error');
      $valid = FALSE;
    } else if (!empty($values['os_importer_file_upload-' . $node_type])) {
      $file = file_load($values['os_importer_file_upload-' . $node_type]);
      $part = !empty($file->filename) ? explode('.', $file->filename) : array();
      $extension = end($part);
      if ($extension != 'ics') {
        drupal_set_message('Please select an iCal file.', 'error');
        $valid = FALSE;
      } else {
        // iCal file validation.
        // Parsing ics file content with iCalcreator class.
        $file_content = file_get_contents($file->uri);
        $config = array(
          'unique_id' => '',
          'filename' => drupal_realpath($file->uri),
        );
        
        module_load_include('php', 'os_events', 'iCalcreator/iCalcreator.class');
        $calendar = new vcalendar($config);
        if (!$calendar->parse($file_content)) {
          drupal_set_message(t('The import file does not contain any events.'), 'error');
          $valid = FALSE;
        }
      }
    }
  } else if ($values['os_importer_content_format-' . $node_type] == 'rss') {
    if (empty($values['os_importer_rss_title-' . $node_type])) {
      drupal_set_message('The Title field is required.', 'error');
      $valid = FALSE;
    }
    if (empty($values['os_importer_rss_url-' . $node_type])) {
      drupal_set_message('The URL field is required.', 'error');  
      $valid = FALSE;
    } else {
      // Validate the url address.
      $result = drupal_http_request($values['os_importer_rss_url-' . $node_type]);
      if ($result->code != 200) {
        drupal_set_message(t('The address @url is not a valid address.', array('@url' => check_plain($values['os_importer_rss_url-' . $node_type]))), 'error');
        $valid = FALSE;
      }

      // Verify the user didn't enter that address.
      if (module_exists('vsite') && $vsite = vsite_get_vsite()) {
        $importer_type = $node_type == 'blog' ? 'blog_import' : 'feed_importer';

        // Check for existing nodes in the same VSite.
        $query = new EntityFieldQuery();
        $result = $query
          ->entityCondition('entity_type', 'node')
          ->entityCondition('bundle', $importer_type)
          ->propertyCondition('status', NODE_PUBLISHED)
          ->fieldCondition(OG_AUDIENCE_FIELD, 'target_id', $vsite->id)
          ->fieldCondition('field_rss_url', 'value', $values['os_importer_rss_url-' . $node_type])
          ->execute();

        if (!empty($result['node'])) {
          $row = reset($result['node']);
          $node = node_load($row->nid);

          $params = array(
            '@url' => url('cp/os-importer/' . $node_type . '/manage/' . $node->nid),
            '@title' => $node->title,
          );
          drupal_set_message(t('RSS url already exists in <a href="@url">@title</a>.', $params), 'error');
          $valid = FALSE;
        }
      }
    }
  }
  return $valid;
}


/**
 * Submit handler for os_importer_submit button for CSV/RSS/iCal import.
 */
 function os_importer_file_submit($request) {
  global $user, $language;
  foreach($request as $var => $value) {
    if (strpos($var, 'os_importer_submit-') === 0) {
      $part = explode('-', $var);
      $node_type = end($part);
    }
  }
  $import_file_fid = $request['os_importer_file_upload-' . $node_type];
  $import_file = file_load($import_file_fid);
  $encode = $request['os_importer_encode-' . $node_type];
  $part = !empty($import_file->uri) ? explode('.', $import_file->uri) : array();
  $extension = end($part);

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
    $import_type = $node_type == 'blog' ? 'blog_import' : 'feed_importer';
    $node = entity_create('node', array(
      'title' => $request['os_importer_rss_title-' . $node_type],
      'type' => $import_type,
      'language' => $language->language,
      'uid' => $user->uid,
    ));
    $wrapper = entity_metadata_wrapper('node', $node);
    $wrapper->field_rss_url->set($request['os_importer_rss_url-' . $node_type]);
    $wrapper->save();
  }
}