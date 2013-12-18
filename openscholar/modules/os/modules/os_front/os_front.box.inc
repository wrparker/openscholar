<?php
/**
 * @file
 * iqss_scholars_front.box.inc
 */

/**
 * Implements hook_default_box().
 */
function os_front_default_box() {
  $export = array();

  $path = drupal_get_path('theme', 'os_default');
  $build = array(
    'banner' => array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array(
          'os-align-center',
        ),
      ),
      'image' => array(
        '#theme' => 'image',
        '#path' => "$path/images/osfrontpg_logo.gif",
        '#alt' => t('OpenScholar'),
        '#title' => t('OpenScholar'),
        '#attributes' => array(
          'class' => array(
            'os-title-image',
          ),
        ),
      ),
    ),
    'tagline' => array(
      '#markup' => '<strong>' . t('The easiest way to manage all your institution\'s sites') . '</strong>',
    ),
  );
  $value = drupal_render($build);
  if (user_access('access administration pages')) {
    $value .= '<br/>' . t('Welcome! Hover over this widget to customize your front page.');
  }
  $box = new stdClass();
  $box->disabled = FALSE; /* Edit this to true to make a default box disabled initially */
  $box->api_version = 1;
  $box->delta = 'os_front_header';
  $box->plugin_key = 'os_boxes_html';
  $box->title = '';
  $box->description = 'Front Page Header Text';
  $box->options = array(
    'text' => array(
      'value' => $value,
      'format' => 'filtered_html',
    ),
    'additional_classes' => '',
  );
  $export['os_front_header'] = $box;

  $value = '';
  if (!user_is_anonymous()) {
    $build = array(
      'banner' => array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array(
            'os-align-center',
          ),
        ),
        'image' => array(
          '#theme' => 'image',
          '#path' => "$path/images/osfrontpg_support.gif",
          '#alt' => t('OpenScholar support'),
          '#title' => t('OpenScholar support'),
          '#attributes' => array(
            'class' => array(
              'os-title-image',
            ),
          ),
        ),
      ),
      'intro' => array(
        '#markup' => '<p>' . t('As you customize your sitewide theme, <a href="/admin/config/openscholar">OpenScholar settings</a> and <a href="/admin/config/openscholar/vsite_register">Vsite Register settings</a>...') . '</p>',
      ),
      'list' => array(
        '#theme' => 'item_list',
        '#type' => 'ol',
        '#items' => array(
          0 => t('For questions about administering and configuring your new OpenScholar installation, search and/or post to our <a href="https://github.com/openscholar/openscholar/issues">GitHub issue queue</a>.'),
          1 => t('To provide your end-users with additional site-building guides and documentation, feel free to refer to OpenScholar @ Harvard\'s <a href="http://support.openscholar.harvard.edu">Help Center</a> and <a href="http://community.openscholar.harvard.edu">Community forum</a>.'),
        ),
      ),
    );
    $value = drupal_render($build);
  }
  $box = new stdClass();
  $box->disabled = FALSE; /* Edit this to true to make a default box disabled initially */
  $box->api_version = 1;
  $box->delta = 'os_front_main_support';
  $box->plugin_key = 'os_boxes_html';
  $box->title = 'Support';
  $box->description = 'Support';
  $box->options = array(
    'text' => array(
      'value' => $value,
      'format' => 'filtered_html',
    ),
    'additional_classes' => '',
  );
  $export['os_front_main_support'] = $box;

  $value = '';
  if (!user_is_anonymous()) {
    $build = array(
      'banner' => array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array(
            'os-align-center',
          ),
        ),
        'image' => array(
          '#theme' => 'image',
          '#path' => "$path/images/osfrontpg_configure.gif",
          '#alt' => t('OpenScholar configure'),
          '#title' => t('OpenScholar configure'),
          '#attributes' => array(
            'class' => array(
              'os-title-image',
            ),
          ),
        ),
      ),
      'list' => array(
        '#theme' => 'item_list',
        '#type' => 'ol',
        '#items' => array(
          0 => t('To configure these widgets, simply hover over the widget, click the gear, then click <strong>Edit widget</strong>.'),
          1 => t('To add or remove widgets from this context, <a href="/admin/structure/context/list/os_sitewide_front/edit">edit the os_sitewide_front context</a>.'),
        ),
      ),
    );
    $value = drupal_render($build);
  }
  $box = new stdClass();
  $box->disabled = FALSE; /* Edit this to true to make a default box disabled initially */
  $box->api_version = 1;
  $box->delta = 'os_front_main_configure';
  $box->plugin_key = 'os_boxes_html';
  $box->title = 'Configure this page';
  $box->description = 'Create A Site';
  $box->options = array(
    'text' => array(
      'value' => $value,
      'format' => 'filtered_html',
    ),
    'additional_classes' => '',
  );
  $export['os_front_main_configure'] = $box;


  $variables = array(
    'header' => t('Sample websites'),
    'images' => array(
      0 => array(
        'path' => "$path/images/osfrontpg_samplesite1.gif",
        'alt' => t('Sample site A'),
        'title' => t('Sample site A'),
      ),
      1 => array(
        'path' => "$path/images/osfrontpg_samplesite2.gif",
        'alt' => t('Sample site B'),
        'title' => t('Sample site B'),
      ),
      2 => array(
        'path' => "$path/images/osfrontpg_samplesite3.gif",
        'alt' => t('Sample site C'),
        'title' => t('Sample site C'),
      ),
    ),
  );
  $box = new stdClass();
  $box->disabled = FALSE; /* Edit this to true to make a default box disabled initially */
  $box->api_version = 1;
  $box->delta = 'os_front_sample';
  $box->plugin_key = 'os_boxes_html';
  $box->title = '';
  $box->description = t('List example site screenshots');
  $box->options = array(
    'text' => array(
      'value' => theme('os_front_boxcontent_sample', $variables),
      'format' => 'filtered_html',
    ),
    'additional_classes' => '',
  );
  $export['os_front_sample'] = $box;

  return $export;
}
