<?php 

class os_sv_list_file extends os_sv_list {
  public function __construct() {
    parent::__construct();
    $this->entity_type = 'file';
  }
  
  /**
   * Implementation of boxes_content::options_form().
   */
  public function options_form(&$form_state) {
    $form = parent::options_form($form_state);
    $types = array('all' => t('All')) + 
      array_reduce(file_type_get_all_types(), function ($ret, $o) {$ret[$o->type] = $o->label; return $ret;});
    
    if (isset($types['html'])) {
      $types['html'] = t('Embedded Media');
    }
    $form['content']['content_type']['#title'] = t('File Type');
    $form['content']['content_type']['#options'] = $types;
    return $form;
  }
 
  /**
   * @function query()
   * 
   * Remove html files from ever showing up in sv list.
   */
  public function query() {
    $efq = parent::query();
    return $efq;
  }
  
  
  protected function _sort_options() {
    return array(
      'sort_newest' => t('Newest'),
      'sort_oldest' => t('Oldest'),
      'sort_alpha' => t('Alphabetical'),
      'sort_filesize' => t('Filesize'),
      'sort_random' => t('Random'),
    );
  }
  

  /**
   * sorting functions
   */
  function sort_newest(&$efq) {
    $efq->propertyOrderBy('timestamp', 'DESC');
  }
  function sort_oldest(&$efq) {
    $efq->propertyOrderBy('timestamp', 'ASC');
  }
  function sort_alpha(&$efq) {
    $efq->propertyOrderBy('filename', 'ASC');
  }
  function sort_filesize(&$efq) {
    $efq->propertyOrderBy('filesize', 'DESC');
  }

  /**
   * Check access to the the box.
   *
   * We return here TRUE because in the case of file we need to check if the
   * linked nodes are of a bundle (App) that is set to private. This will be
   * done in the get_ids() method.
   */
  public function checkWidgetAccess($bundle) {
    return TRUE;
  }

  /**
   * Overrides os_sv_list::get_ids().
   */
  public function get_ids($page) {
    $ids = parent::get_ids($page);

    if (!$ids) {
      return array();
    }

    // Get the bundle of the nodes that are using the files with the given ids.
    $query = db_select('node', 'n');
    $query->join('file_usage', 'fu', 'n.nid = fu.id');
    $query->fields('n', array('type'));
    $query->condition('fu.fid', $ids, 'IN');
    $results = $query
      ->execute()
      ->fetchAll();

    $attached_nodes_bundles = array();
    foreach ($results as $result) {
      $attached_nodes_bundles[] = $result->type;
    }

    // Get the node bundles that use the files which are private.
    $private_bundles_using_files = array_intersect($attached_nodes_bundles, array_keys(os_get_bundles(array(OS_PRIVATE_APP))));
    if (empty($private_bundles_using_files)) {
      return $ids;
    }

    // We have files that are used in a bundle (App) that is private so check to
    // see if the user has access to view it.
    foreach ($private_bundles_using_files as $private_bundle) {
      if (!node_access('view', $private_bundle)) {
        $ids = array();
        break;
      }
    }
    return $ids;
  }
}
