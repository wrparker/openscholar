<?php

class os_boxes_widget_slider extends os_boxes_widget_collection {

  public $embed_as = array(
    'iframe',
  );

  public function options_defaults() {
    $options = parent::options_defaults();
    return $options + array(
      'effect' => 'fade',
      'timeout' => 5000,
      'speed' => 700,
    );
  }

  public function options_form(&$form_state) {
    $form = parent::options_form($form_state);
    $defaults = $this->options_defaults();
    $form['advanced']['#after_build'][] = '_os_boxes_widget_slider_field_display_handler';
    $form['display'] = array(
      '#type' => 'fieldset',
      '#title' => t('Display Options'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#weight' => 10,
    );

    $form['display']['timeout'] = array(
      '#type' => 'select',
      '#description' => t('Each slide will be displayed for this length of time.'),
      '#title' => t('Duration'),
      '#default_value' => isset($this->options['timeout']) ? $this->options['timeout'] : $defaults['timeout'],
      '#options' => $this->_transition_speed_opts(1, 10, .5),
    );

    $form['display']['speed'] = array(
      '#type' => 'select',
      '#description' => t('Transitions between slides will take this long.'),
      '#title' => t('Transition Speed'),
      '#default_value' => isset($this->options['speed']) ? $this->options['speed'] : $defaults['speed'],
      '#options' => $this->_transition_speed_opts(0, 1, .1),
    );

    return $form;
  }

   /**
   * @function _transition_speed_opts()
   *
   * Helper function for making for time selects.  All args are seconds.
   *
   * @returns array( $milliseconds => $seconds . 's')
   */
  private function _transition_speed_opts($min, $max, $increment) {
    $precision = explode('.', $increment);
    $decimals = (count($precision) > 1) ? strlen($precision[1]) : 0;

    $return = array();
    for ($i = $min; $i <= $max; $i += $increment) {
      $return[$i * 1000] = number_format($i, $decimals) . ' sec';
    }
    return $return;
  }
  
  public function render() {
    $block = parent::render();
    $defaults = $this->options_defaults();
    $slides = array();
    foreach ($this->options['widgets'] as $key => $widget) {
      list(, $delta) = explode('-', $widget['bid'], 2);
      $box = boxes_box_load($delta);
      $content = $box->render();
      $slides[] = $content['content'];     
    }

    $vars = array();
    $vars['slides'] = $slides;
    $block['content'] = theme('os_slideshow_widget_slideshow', $vars);
    $timeout = isset($this->options['timeout']) ? $this->options['timeout'] : $defaults['timeout'];
    $speed = isset($this->options['speed']) ? $this->options['speed'] : $defaults['speed'];
    $assets = $this->setup_assets($this->delta, $timeout, $speed, 0);
    $this->add_assets($assets);

    return $block;
  }

  /**
   * Setup needed assets for the os slideshow widget, which will be used with add_assets().
   *
   * @param $delta
   *   The delta of the box.
   * @param $timeout
   *   The flexslider timeout.
   * @param $speed
   *   The flexslider speed.
   * @param $random
   *   The flexslider random order boolean.
   * @return array
   *   Structured array of assets.
   */
  private function setup_assets($delta, $timeout, $speed, $random) {
    $assets = array();
    $assets['library'] = array();
    $assets['library'][] = array('os_slideshow', 'responsiveslides');

    $assets['js'] = array();
    $assets['js'][] = drupal_get_path('module', 'os_slideshow') . '/os_slideshow_slider.js';
    $assets['js'][] = drupal_get_path('module', 'os_boxes') . '/plugins/os_boxes_widget_slider/os_boxes_widget_slider.js';

    $assets['settings'] = array();
    $assets['settings'][] = array(
      // Settings reference from http://www.woothemes.com/flexslider/
      'os_slideshow' =>
        array('boxes-box-' . $delta => array(
          'pager' => TRUE,
          'nav' => TRUE,
          // Flex starts the timer after transition. Responsive starts it during.
          // Adding them will let responsive look like flex did.
          'timeout' => intval($timeout + $speed),
          'speed' => $speed,
          'random' => $random,
        ),
      )
    );

    $assets['css'] = array();
    $assets['css'][] = _os_slideshow_add_css();

    return $assets;
  }
}

/**
 * #after_build handler in options_form to display settings form field for widget_slider widget.
 * To hide the 'Widget Title' field while editing widget slider widget.
 */
function _os_boxes_widget_slider_field_display_handler($element, &$form_state) {
  $form_state['complete form']['title']['#access'] = FALSE;  
}