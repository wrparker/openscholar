<?php

/**
 * @file
 * Administrative page callbacks for Os Reports module.
 */


/**
 * OS Site report form
 */
function os_reports_site_form($form, &$form_state) {
  os_common_angular_app('ReportModule');

  $form['#attached'] = array(
    'js' => array(
      array(
        'data' => array(
          'osRestModulePath' => $GLOBALS['base_url'] . '/' . drupal_get_path('module', 'os_reports'),
          'restBasePath' => url('api'),
        ),
        'type' => 'setting',
      ),
      array (
        'data' => drupal_get_path('module', 'os_reports').'/js/ReportModule.js',
        'type' => 'file',
      ),
    ),
    'css' => array(
      drupal_get_path('module', 'os_reports') . '/os_reports.css',
      drupal_get_path('theme', 'seven') . '/style.css',
    ),
    'library' => array(
      array('os_common', 'EntityService'),
      array('os_common', 'auth'),
    )
  );

  $form['#attributes'] = array(
    'ng-controller' => 'SiteReportQuery',
    'ng-app' => 'ReportModule',
    'name' => 'queryForm',
  );
  $form['keyword_set'] = array(
    '#type' => 'fieldset',
    '#title' => 'Keyword search'
  );
  $form['keyword_set']['keyword'] = array(
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>',
    '#type' => 'textfield',
    '#title' => 'Keyword',
    '#attributes' => array(
      'ng-model' => 'query.keyword',
      'ng-change' => "updateParam('keyword')"
    ),
  );
  $form['keyword_set']['kfields'] = array(
    '#prefix' => '<div class="container-inline"><label for="edit-kfields">Search in fields </label>',
    '#suffix' => '</div>',
    '#type' => 'checkboxes',
  );
  $form['keyword_set']['kfields']['mail'] = array(
    '#title' => 'email',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.kfields.mail',
      'ng-click' => "updateCheckedValues('kfields', 'mail')",
      'value' => 'mail',
    ),
  );
  $form['keyword_set']['kfields']['name'] = array(
    '#title' => 'username',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.kfields.name',
      'ng-click' => "updateCheckedValues('kfields', 'name')",
      'value' => 'name',
    ),
  );
  $form['keyword_set']['kfields']['n.title'] = array(
    '#title' => 'site name',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.kfields.n.title',
      'ng-click' => "updateCheckedValues('kfields', 'n.title')",
      'value' => 'n.title',
    ),
  );
  $form['content'] = array(
    '#type' => 'fieldset',
    '#title' => 'Site Content'
  );
  $form['content']['lastupdatebefore'] = array(
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>',
    '#type' => 'textfield',
    '#title' => 'Content last updated before',
    '#size' => 10,
    '#description' => 'format: YYYYMMDD',
    '#attributes' => array(
      'ng-model' => 'query.lastupdatebefore',
      'ng-change' => "updateParam('lastupdatebefore')",
    ),
  );
  $form['content']['includesites'] = array(
    '#prefix' => '<div class="container-inline"><label for="edit-includesites">Sites to include</label>',
    '#suffix' => '</div>',
  );
  $form['content']['includesites']['all'] = array(
    '#name' => 'includesites',
    '#title' => 'all sites',
    '#type' => 'radio',
    '#attributes' => array(
      'ng-model' => 'query.includesites',
      'ng-click' => "updateParam('includesites')",
      'value' => 'all',
   ),
  );
  $form['content']['includesites']['content'] = array(
    '#name' => 'includesites',
    '#title' => 'only sites with content',
    '#type' => 'radio',
    '#attributes' => array(
      'ng-model' => 'query.includesites',
      'ng-click' => "updateParam('includesites')",
      'value' => 'content',
    ),
  );
  $form['content']['includesites']['nocontent'] = array(
    '#name' => 'includesites',
    '#title' => 'only sites with no content',
    '#type' => 'radio',
    '#attributes' => array(
      'ng-model' => 'query.includesites',
      'ng-click' => "updateParam('includesites')",
      'value' => 'nocontent',
    ),
  );
  $form['columns'] = array(
    '#type' => 'fieldset',
    '#title' => 'Optional Columns',
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>',
  );
  $form['columns']['subdomain'] = array(
    '#title' => 'Subdomain of site owner',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.columns.subdomain',
      'ng-click' => "updateCheckedValues('columns', 'subdomain')",
      'value' => 'subdomain',
    ),
  );
  $form['columns']['createdate'] = array(
    '#title' => 'Creation date',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.columns.createdate',
      'ng-click' => "updateCheckedValues('columns', 'createdate')",
      'value' => 'createdate',
    ),
  );
  $form['columns']['updatedate'] = array(
    '#title' => 'Date of last content update',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.columns.updatedate',
      'ng-click' => "updateCheckedValues('columns', 'updatedate')",
      'value' => 'updatedate',
    ),
  );
  $form['columns']['privacy'] = array(
    '#title' => 'Privacy level',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.columns.privacy',
      'ng-click' => "updateCheckedValues('columns', 'privacy')",
      'value' => 'privacy',
    ),
  );
  $form['columns']['customtheme'] = array(
    '#title' => 'Custom theme',
    '#type' => 'checkbox',
    '#attributes' => array(
      'ng-model' => 'query.columns.customtheme',
      'ng-click' => "updateCheckedValues('columns', 'customtheme')",
      'value' => 'customtheme',
    ),
  );
  $handler = restful_get_restful_handler('report_sites');
  $form['range'] = array(
    '#value' => $handler->getRange(),
    '#type' => 'hidden',
    '#attributes' => array(
      'ng-model' => 'query.range',
      'ng-init' => "params.range = '" . $handler->getRange() . "'",
    ),
  );
  $form['buttons'] = array(
    '#prefix' => '<div class="buttons">',
    '#suffix' => '</div>',
  );
  $form['buttons']['submit'] = array(
    '#markup' => '<a class="button" ng-click="update()">Run Report</a>',
  );
  $form['buttons']['clear'] = array(
    '#markup' => '<a class="button" ng-click="reset()">Clear</a>',
  );
  $form['buttons']['download'] = array(
    '#type' => 'submit',
    '#value' => 'Download CSV of Full Report',
  );

  $buttons = '<div class="pager"><div class="container-inline"><div class="previous"><a ng-click="pager(\'previous\')">‹ previous</a></div><div class="status_count">{{ status }}</div><div class="next"><a ng-click="pager(\'next\')">next ›</a></div></div></div>';

  $form['results'] = array(
    '#markup' => $buttons . '<div class="results"><table><thead><tr><th ng-repeat="header in headers" ng-class="isActive(header)"><a ng-click="sort(this)" class="{{ header }}">{{ header | formatHeader }}</a></th></tr></thead><tbody><tr ng-repeat="row in rows" ng-class-even="\'even\'" ng-class-odd="\'odd\'"><td ng-repeat="header in headers"><span ng-bind-html="row[header] | makelink:header:row"></span></td></tr></tbody></table></div>' . $buttons,
  );

  return $form;
}

function os_reports_site_form_submit($form, &$form_state) {
  global $base_url;
  $form_state['rebuild'] = TRUE;
  
  $handler = restful_get_restful_handler('report_sites');
  $params = $form_state['input'];

  // clean up parameters before rest call
  foreach ($params as $key => $value) {
    if (!$value) {
      unset($params[$key]);
    }
    if (is_array($value)) {
      $values = array_keys($value);
      $params[$key] = $values;
    }
  }

  $params['range'] = 0;

  // make restful call to get data
  $results = $handler->post('', $params);

  if ($results) {
    $filename = str_replace("http://", "", $base_url) . "-site-report.csv";

    drupal_add_http_header("Cache-Control", "public");
    drupal_add_http_header("Content-Type", "application/octet-stream");
    drupal_add_http_header("Cache-Control", "o-store, no-cache, must-revalidate");
    drupal_add_http_header("Cache-Control", "post-check=0, pre-check=0");
    drupal_add_http_header("Content-Disposition", "attachment; filename=\"$filename\";" );
    drupal_add_http_header("Content-Transfer-Encoding", "binary");

    $headers = array_keys($results[0]);
    print ucfirst(str_replace("_", " ", implode($headers, ", "))) . "\n";
    foreach ($results as $row) {
      print implode($row, ", ") . "\n";
    }
    drupal_exit();
  }
  else {
    drupal_set_message('No results in report.', 'warning');
  }
}
