<?php

class os_files_handler_field_file_link extends views_handler_field_file_uri {

  function render($values) {
    $output = file_create_url($values->{$this->field_alias});
    return $output;
  }
}
