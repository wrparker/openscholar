<?php

class SlideshowSlideNodeRestfulBase extends OsNodeRestfulBase {

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['description'] = array(
      'property' => 'field_description',
    );

    $public_fields['link'] = array(
      'property' => 'field_link',
    );

    $public_fields['headline'] = array(
      'property' => 'field_headline',
    );

    $public_fields['alt_text'] = array(
      'property' => 'field_slideshow_alt_text',
    );

    $public_fields['title_text'] = array(
      'property' => 'field_slideshow_title_text',
    );

    $public_fields['image'] = array(
      'property' => 'field_image',
      'process_callbacks' => array(
        array($this, 'singleFileFieldDisplay'),
      ),
    );

    return $public_fields;
  }

}
