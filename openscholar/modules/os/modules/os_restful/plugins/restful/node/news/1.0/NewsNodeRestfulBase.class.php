<?php

class NewsNodeRestfulBase extends OsNodeRestfulBase {

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['date'] = array(
      'property' => 'field_news_date',
      'process_callbacks' => array(
        array($this, 'processDate'),
      ),
    );

    return $public_fields;
  }

  public function processDate($timestamp) {
    return date('M j Y', $timestamp);
  }
}
