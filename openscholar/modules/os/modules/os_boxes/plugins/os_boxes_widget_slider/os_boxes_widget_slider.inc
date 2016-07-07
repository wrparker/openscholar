<?php

class os_boxes_widget_slider extends os_boxes_widget_collection {

  public $embed_as = array(
    'iframe',
  );
  
  public function render() {
    $block = parent::render();
    $slides = array();
    foreach ($this->options['widgets'] as $key => $widget) {
      list(,$delta) = explode('-', $widget['bid'], 2);
      $box = boxes_box_load($delta);
      $content = $box->render();
      $slides[] = $content['content'];     
    }
       
    $vars = array();
    $vars['slides'] = $slides;
    $block['content'] = theme('os_slideshow_widget_slideshow', $vars);
    //dsm($this);
    $assets = $this->setup_assets($this->delta, 5000, 500, 0);
    $this->add_assets($assets);

   // $box->title = $this->options['widgets'][$k]['title'];
    //$box->options['make_embeddable'] = $this->options['make_embeddable'];
     
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