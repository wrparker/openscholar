<?php

class PageNodeRestfulBase extends OsNodeRestfulBase {
  public static function controllersInfo() {
    return array(
      'form' => array(
        // If they don't pass a menu-id then display nothing.
        \RestfulInterface::GET => 'getForms',
      ),
    ) + parent:: controllersInfo();
  }

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['path'] = array(
      'property' => 'path',
    );

    return $public_fields;
  }

  public function getForms($args) {
    $content_type = 'page';
    module_load_include('inc', 'node', 'node.pages');
    return $form = node_add($content_type);
  }

}
