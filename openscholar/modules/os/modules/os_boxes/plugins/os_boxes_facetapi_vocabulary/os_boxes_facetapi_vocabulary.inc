<?php

class os_boxes_facetapi_vocabulary extends os_boxes_default {

  /**
   * Create a new box.
   */
  protected function __construct() {
    parent::__construct();
    $this->title = t('Filter by taxonomy');
  }

  /**
   * Implementation of boxes_content::options_defaults().
   */
  public function options_defaults() {
    $options = parent::options_defaults();
    $options['bundle'] = FALSE;
    $options['vocabularies'] = array();
    $options['sort_by'] = 'alpha';

    return $options;
  }

  /**
   * Implementation of boxes_content::options_form().
   */
  public function options_form(&$form_state) {
    $form = parent::options_form($form_state);

    $bundle = $this->options['bundle'];
    $selected_vocab = $this->options['vocabularies'];
    $sort_by = $this->options['sort_by'];

    if (!empty($form_state['values']['bundle'])) {
      // We have the bundle from the AJAX.
      $bundle = $form_state['values']['bundle'];
      $selected_vocab = 'all';
      $sort_by = $form_state['values']['sort_by'];
    }

    $bundles = os_get_bundles(array(OS_PUBLIC_APP, OS_PRIVATE_APP));
    // Use first bundle of the list in case none was selected before.
    if (!$bundle) {
      $bundle = reset(array_keys($bundles));
    }

    $form['bundle'] = array(
      '#type' => 'select',
      '#description' => t('Which bundle would you like to use.'),
      '#title' => t('Content type'),
      '#options' => $bundles,
      '#required' => TRUE,
      '#default_value' => $bundle,
      '#ajax' => array(
        'callback' => 'os_boxes_facetapi_vocabulary_ajax_callback',
        'wrapper' => 'vocab-wrapper',
      ),
    );

    $form['vocab_wrapper'] = array(
      '#prefix' => '<div id="vocab-wrapper">',
      '#suffix' => '</div>',
      '#parents' => array('vocab_wrapper'),
    );

    $vocabs = os_search_solr_get_bundle_vocabularies($bundle);
    $options = array('all' => t('All vocabularies')) + $vocabs;

    $form['vocab_wrapper']['vocabularies'] = array(
      '#title' => t('Select vocabularies'),
      '#type' => 'select',
      '#options' => $options,
      '#multiple' => TRUE,
      '#default_value' => $selected_vocab,
    );

    $options = array(
      'alpha' => t('Alphabetical'),
      'weight' => t('Vocabulary weight')
    );
    $form['sort_by'] = array(
      '#title' => t('Sort by:'),
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => $sort_by,
    );

    return $form;
  }

  /**
   * Check whether an empty Solr search is needed.
   *
   * @return $browse_page
   *   Solr search page id in case empty search is needed, FALSE otherwise.
   */
  public function is_empty_search_needed() {
    if (!$bundle = $this->options['bundle']) {
      return;
    }

    $page_id = 'browse_' . $bundle;
    if (!$search_page = apachesolr_search_page_load($page_id)) {
      return;
    }
    $env_id = $search_page['env_id'];

    if (apachesolr_has_searched($env_id)) {
      // Search was executed already.
      return;
    }
    return $page_id;
  }

  /**
   * Facet widgets that in a non-solr search page must fake a search page in
   * order to show the facet. To do so we execute a "silent" search on solr
   * without showing the results.
   */
  public function call_empty_search_if_needed() {
    if (!$page_id = $this->is_empty_search_needed()) {
      return;
    }

    // Execute the query.
    try {
      if (!$search_page = apachesolr_search_page_load($page_id)) {
        throw new Exception("Failed to load search page information $search_page");
      }
      $env_id = $search_page['env_id'];

      if (apachesolr_has_searched($env_id)) {
        // Search was executed already.
        return;
      }
      $solr = apachesolr_get_solr($env_id);

      $conditions = apachesolr_search_conditions_default($search_page);

      // Initializes params for empty query.
      $params = array(
        'spellcheck' => 'false',
        'fq' => isset($conditions['fq']) ? $conditions['fq'] : array(),
        'rows' => 1,
      );
      $context['page_id'] = $page_id;
      $context['search_type'] = 'apachesolr_search_show_facets';
      apachesolr_search_run_empty('apachesolr', $params, $search_page['search_path'], $solr, $context);

      // Returning page id means there was an empty search, and we will cache the
      // block.
      return $page_id;
    }
    catch (Exception $e) {
      watchdog('Apache Solr', nl2br(check_plain($e->getMessage())), NULL, WATCHDOG_ERROR);
    }
  }

  /**
   * Implementation of boxes_content::render().
   */
  public function render() {
    $block = parent::render();
    $searcher_name = os_search_solr_get_searcher_name();
    // Gets delta map
    if (!$map = facetapi_get_delta_map()) {
      return $block;
    }

    $delta = $block['delta'];

    // Filter by OG vocab.
    $string_og_vocab = facetapi_build_delta($searcher_name, 'block', 'sm_og_vocabulary');
    $delta_og_vocab = array_search($string_og_vocab, $map);

    if ($this->options['bundle'] && is_string($this->options['bundle'])) {
      $bundle = 'node:' . $this->options['bundle'];
    }
    else {
      $bundle = NULL;
    }
    $cid = $this->set_cache_id($bundle);

    if ($page_id = $this->is_empty_search_needed()) {
      // Block delta is needed since $delta_og_vocab is not unique between sites
      $cid = $this->cid = $cid .= ':' . $page_id;

    }
    // Verify the cache is still valid, based on time since created.
    // Verify the facet block has something to work on (means - it is in a solr
    // search page). If not - execute the empty solr search without showing
    // the results.
    // If empty search happened, we will cache the block.
    $use_cache = $this->call_empty_search_if_needed();
    if (!$use_cache || (($block = $this->get_cache()) === FALSE)) {

      $block = module_invoke('facetapi', 'block_view', $delta_og_vocab);
      $block['delta'] = $delta;

      // Set block subject/title from box configuration.
      $block['subject'] = check_plain($this->title);
      $block['title'] = check_plain($this->title);

      if (empty($block['content'])) {
        // Block content must be defined, even if it is empty, to avoid errors.
        // os_boxes_widget_submit() renders blocks into a 'dummy' region, but
        // gives errors in case the content is empty, hence the space.
        $block['content'] = ' ';

        // While block is empty, caching NULL will provide the needed caching.
        if ($use_cache) {
          $this->set_cache(NULL);
        }
      }
      else {
        if (!empty($this->options['vocabularies'])) {
          // Get the list of selected vocabularies
          $selected = array();
          foreach ($this->options['vocabularies'] as $vocabulary_name => $value) {
            if ($value == "all") {
              $selected = array();
              break;
            }
            if ($value) {
              $selected[$vocabulary_name] = $value;
            }
          }
          // If no selection - show all. If there is selection - show only them.
          if (count($selected)) {
            foreach (array_keys($block['content']['sm_og_vocabulary']) as $vocabulary_name) {
              if (empty($selected[$vocabulary_name])) {
                unset($block['content']['sm_og_vocabulary'][$vocabulary_name]);
              }
            }
          }
        }

        if (!empty($block['content']['sm_og_vocabulary'])) {
          // Sort by the Vocabulary weight if that option is selected.
          if ($this->options['sort_by'] == 'weight') {
            $query = new EntityFieldQuery();
            $results = $query->entityCondition('entity_type', 'taxonomy_vocabulary')
              ->propertyCondition('machine_name', array_keys($block['content']['sm_og_vocabulary']), 'IN')
              ->propertyOrderBy('weight')
              ->execute();

            $ordered_vocab = array();
            foreach($results['taxonomy_vocabulary'] as $vid) {
              $vocab = taxonomy_vocabulary_load($vid->vid);
              // Take the existing content and reorder it based on the query.
              $ordered_vocab[$vocab->machine_name] = $block['content']['sm_og_vocabulary'][$vocab->machine_name];
            }
            $block['content']['sm_og_vocabulary'] = $ordered_vocab;
          }
          else {
            // Default is to sort by title.
            foreach (array_keys($block['content']['sm_og_vocabulary']) as $vocabulary_name) {
              array_sort_by_column($block['content']['sm_og_vocabulary'][$vocabulary_name]['#items'], 'data');              
            }
          }
        }

        $block['content'] = render($block['content']);
        if ($use_cache) {
          $this->set_cache($block);
        }
      }
    }
    return $block;
  }

  /**
   * When submitting a box we need to invalidate here.
   */
  public function options_submit($form, $form_state) {
    if (module_exists('vsite') && !$vsite = vsite_get_vsite()) {
      return;
    }

    // We try to delete the cache of a newly selected bundle, leaving the old cached
    // info unused. If we will re-select the old bundle its cache will be cleared
    // next time.
    if ($this->options['bundle'] && is_string($this->options['bundle'])) {
      $bundle = 'node:' . $this->options['bundle'];
    }
    else {
      $bundle = NULL;
    }
    $cid = $this->set_cache_id($bundle);

    cache_clear_all($cid, 'cache_os_boxes', TRUE);
    cache_clear_all('assets:' . $cid, 'cache_os_boxes', TRUE);
  }
}

/**
 * AJAX callback to attach the vocab to the form.
 *
 * Since the controlling logic for populating the form is in the form builder
 * function, all we do here is select the element and return it to be updated.
 */
function os_boxes_facetapi_vocabulary_ajax_callback($form, &$form_state) {
  return $form['options']['vocab_wrapper'];
}

/**
 * Function to sort multidimentional array.
 *
 * @param $arr
 *   The reference to the actual array element that needs to be sorted.
 * @param $col
 *   The inner array key for sorting.
 * @param $dir
 *   To define sorting order.
 */
function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
  $sort_col = array();
  foreach ($arr as $key=> $row) {
    $sort_col[$key] = strip_tags($row[$col]);
  }
  array_multisort($sort_col, $dir, $arr);
}