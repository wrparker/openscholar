<?php

class os_boxes_manual_list extends os_boxes_default {

  public $embed_as = array(
    'iframe',
    'oembed',
  );

  public function options_defaults() {
    return parent::options_defaults() + array(
      'nodes' => array(),
      'style' => 'titles',
      'random' => false,
      'hide_title' => 0,
    );
  }

  public function options_form(&$form_state) {
    include_once (drupal_get_path('module', 'vsite') . '/includes/nodeautocomplete.inc');
    $form_state['build_info']['files'][] = drupal_get_path('module', 'vsite') . '/includes/nodeautocomplete.inc';
    $form = parent::options_form($form_state);

    $form['#attached']['js'][] = drupal_get_path('module', 'os_boxes') . '/plugins/os_boxes_manual_list/os_boxes_manual_list.js';
    $form['search'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add New Post'),
      'node_to_add' => vsite_node_autocomplete_element(),
      'add' => array(
        '#type' => 'button',
        '#value' => t('Add post'),
        '#href' => '',
        '#add' => TRUE,
        '#ajax' => array(
          'callback' => 'os_boxes_manual_add',
          'wrapper' => 'nodes',
        ),
      ),
    );

    $form_state['storage']['nodes'] = isset($form_state['storage']['nodes']) ? $form_state['storage']['nodes'] : 0;

    $form['nodes'] = array(
      '#type' => 'markup',
      '#tree' => TRUE,
      '#prefix' => '<div id="nodes">',
      '#suffix' => '</div>',
      '#pre_render' => array('os_boxes_manual_list_table'),
    );

    if (!empty($form_state['triggering_element']['#add'])) {

      if (empty($form_state['values']['node_to_add'])) {
        form_set_error('', t('Please chose a node to add.'));
      }
      else {
        $explode = explode('[', $form_state['values']['node_to_add']);
        $nid = intval(end($explode));
        $node = node_load($nid);
        unset($form_state['storage']['removed'][$nid]);
        $this->options['nodes'][$nid] = array('title' => $node->title, 'nid' => $node->nid, 'weight' => 1 - count($this->options['nodes']));
      }
    }

    $form_state['storage']['removed'] = empty($form_state['storage']['removed']) ? array() : $form_state['storage']['removed'];

    if (!empty($form_state['triggering_element']['#remove'])) {
      $form_state['storage']['removed'][$form_state['triggering_element']['#remove']] = $form_state['triggering_element']['#remove'];
    }

    $nodes = &$form['nodes'];

    $delta = 0;
    uasort($this->options['nodes'], 'os_boxes_contact_node');
    foreach ($this->options['nodes'] as &$node) {
      if (in_array($node['nid'], $form_state['storage']['removed'])) {
        continue;
      }
      $nodes[$node['nid']] = $this->addNode($node, $delta);
      $delta++;
    }

    // Blank element for copying purposes.
    $form['style'] = array(
      '#type' => 'select',
      '#title' => t('Display Style'),
      '#options' => array(
        'full' => t('List of full posts'),
        'teasers' => t('List of teasers'),
        'titles' => t('List of titles')
      ),
      '#required' => TRUE,
      '#default_value' => $this->options['style']
    );

    $form['random'] = array(
      '#type' =>'checkbox',
      '#title' => t('Random Post'),
      '#default_value' => $this->options['random'],
      '#description' => t('Check this box to show one random post out of the list, instead of all of them.'),
    );

    $form['hide_title'] = array(
      '#type' => 'checkbox',
      '#title' => 'Hide Titles',
      '#default_value' => isset($this->options['hide_title']) ? $this->options['hide_title'] : $defaults['hide_title'],
      '#description' => t('Hide titles of posts.'),
      '#states' => array(
        'disabled' => array(
          '#edit-style' => array('value' => 'titles'),
        ),
      ),
    );

    $form['count'] = array(
      '#type' => 'hidden',
      '#default_value' => count($this->options['nodes'])-1,
      '#attributes' => array(
        'id' => 'edit-count',
      ),
      '#process' => array('os_boxes_manual_list_add_new_fields'),
    );
    return $form;
  }

  public function render() {
    $block = parent::render();

    // Avoid caching if set to random.
    $random = $this->options['random'];

    if ($random || ($block['content'] = $this->get_cache()) === FALSE) {

      $links = array();

      $nodes = $this->options['nodes'];
      if ($random) {
        $nodes = array($nodes[array_rand($nodes)]);
      }

      foreach ($nodes as $n) {
        if ($node = node_load($n['nid'])) {
          // nullify title unless user has selected to show only titles
          if($this->options['style'] != 'titles' && $this->options['hide_title']) {
            $node->title = NULL;
          }
          if ($this->options['style'] == 'teasers') {
            // Theme the nodes as a teaser.

            // Node may not load if it has since been deleted.
            $links[$n['weight']] = render(node_view($node, 'teaser'));
          }
          elseif ($this->options['style'] == 'full') {
            $node = node_load($n['nid']);
            $links[$n['weight']] = render(node_view($node, 'full'));
          }
          else {
            $l = array(
              'href' => 'node/' . $n['nid'],
              'title' => $n['title'],
            );
            $links[$n['weight']] = $l;
          }
        }
      }
      ksort($links);
      if (empty($links)) {
        $block['title'] = $block['subject'] = '';
      }
      elseif  ($this->options['style'] == 'teasers' || $this->options['style'] == 'full') {
        $block['content'] = theme('item_list', array('items' => $links, 'attributes' => array('class' => array('featured-links'))));
      }
      else {
        $block['content'] = theme('links', array('links' => $links, 'attributes' => array('class' => array('featured-links'))));
      }

      if (!$random) {
        $this->set_cache($block['content']);
      }
    }

    return $block;
  }

  /**
   * Adding a single node.
   *
   * @param $node
   * @param $delta
   * @return array
   */
  private function addNode($node, $delta) {
    return array(
      '#weight' => $node['weight'],
      'nid' => array(
        '#type' => 'hidden',
        '#default_value' => $node['nid'],
      ),
      'text' => array(
        '#type' => 'markup',
        '#markup' => $node['title'] . render($remove),
      ),
      'title' => array(
        '#type' => 'hidden',
        '#default_value' => $node['title'],
      ),
      'remove' => array(
        '#type' => 'button',
        '#value' => t('Remove'),
        '#remove' => $node['nid'],
        '#ajax' => array(
          'callback' => 'os_boxes_manual_add',
          'wrapper' => 'nodes',
        ),
      ),
      'weight' => array(
        '#type' => 'textfield',
        '#default_value' => $node['weight'],
        '#size' => 3,
        '#attributes' => array(
          'class' => (string)$delta != 'blank' ? array('field-weight') : array(),
        )
      ),
    );
  }
}

function os_boxes_manual_list_table($element) {
  // Render the form as a table.
  $rows = array();
  foreach (element_children($element) as $c) {
    $rows[] = array(
      'data' => array(
        drupal_render($element[$c]['nid']) . drupal_render($element[$c]['text']) . drupal_render($element[$c]['title']),
        drupal_render($element[$c]['weight']),
        drupal_render($element[$c]['remove']),
      ),
      'class' => (string)$c!='blank'?array('draggable'):array(),
    );
  }
  $element['#headers'] = array();
  $element['#attributes'] = array('id' => 'manual-nodes-list');
  $element['#rows'] = $rows;
  $element['#sticky'] = false;

  $element['#theme'] = 'table';
  drupal_add_tabledrag('manual-nodes-list', 'order', 'sibling', 'field-weight');
  return $element;
}

function os_boxes_manual_list_add_new_fields($element, &$form_state, &$form) {
  for ($i=$element['#default_value']; $i < $element['#value']; $i++) {
    $form['options']['nodes'][$i] = array(
      '#attributes' => array('id' => $i),
      '#parents' => array('nodes', $i),
      '#array_parents' => array('options', 'nodes', $i),
      '#tree' => TRUE,
    );
    $form['options']['nodes'][$i] += $form['options']['nodes']['blank'];
    foreach ($form['options']['nodes'][$i] as $key => &$child) {
      if (strpos($key, '#') !== 0) {
        unset($child['#parents']);
        unset($child['#name']);
        unset($child['#value']);
      }
    }
    form_builder($form['form_id']['#value'], $form['options']['nodes'][$i], $form_state);
  }
  unset($form_state['values']['nodes']['blank']);

  // Remove nids that aren't in the post.
  if (!empty($form_state['values']['nodes'])) {
    foreach ($form_state['values']['nodes'] as $k => $v) {
      if (!isset($form_state['input']['nodes'][$k]) || !isset($form_state['input']['nodes'][$k]['nid'])) {
        unset($form_state['values']['nodes'][$k]);
      }
    }
  }

  return $element;
}

/**
 * Ajax form callback.
 */
function os_boxes_manual_add($form, $form_state) {
  return $form['options']['nodes'];
}

/**
 * Sorting callback. Put the last element at the bottom of the list.
 */
function os_boxes_contact_node($a, $b) {
  return ($a['weight'] < $b['weight']) ? 1 : -1;
}
