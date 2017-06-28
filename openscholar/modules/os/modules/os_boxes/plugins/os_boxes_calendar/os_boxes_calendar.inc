<?php

class os_boxes_calendar extends os_boxes_default {

  public $embed_as = array(
    'iframe',
  );

  public function render() {

    if (module_exists('vsite') && !vsite_get_vsite()) {
      return;
    }

    $block = parent::render();

    $block['content'] = views_embed_view('os_events', 'block_1');

    return $block;
  }

}

