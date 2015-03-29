<?php 

class os_sv_list_box extends os_sv_list {

  public function __construct() {
    parent::__construct();
    $this->entity_type = 'node';
  }
  
  /**
   * Implements boxes_content::options_form().
   */
  public function options_form(&$form_state) {
    $form = parent::options_form($form_state);
    $defaults = $this->options_defaults();
    
    $content_types = array('all' => t('All')) + os_get_bundles(array(1));
    $form['content']['content_type']['#options'] = $content_types;

    return $form;
  }

  public function query() {
    $efq = parent::query();
    $efq->propertyCondition('status', 1); 
    return $efq;
  }

  /**
   * Add our node specific sorts.
   */
  protected function _sort_options() {
    // Standard sorts.
    $sorts = array(
      'sort_newest' => t('Newest Post'),
      'sort_oldest' => t('Oldest Post'),
      'sort_alpha' => t('Alphabetical'),
      'sort_random' => t('Random'),
    );

    return parent::_sort_options() + $sorts;
  }

  /**
   * sorting functions.
   */
  function sort_newest(EntityFieldQuery &$efq) {
    //could thiese be made generic by using entity_get_info to figure out $created and $title per type?
    $efq->propertyOrderBy('created', 'DESC');
  }

  function sort_oldest(EntityFieldQuery &$efq) {
    $efq->propertyOrderBy('created', 'ASC');
  }

  function sort_alpha(EntityFieldQuery &$efq) {
    $efq->propertyOrderBy('title', 'ASC');
  }

  protected function set_cache_id($bundle = NULL, $page = NULL) {
    // In case of an event page, we need to have a cached version of the box
    // for each event.
    // @TODO: we should cache by the date id and not nid. Otherwise there is a problem with repeating events.
    if ($bundle == 'node:event') {
      $object = menu_get_object();
      dpm($object);
      if ($object && $object->type == 'event'){
        $this->cache_id = parent::set_cache_id($bundle, $page) . ':' . $object->nid;
        return $this->cache_id;
      }
    }

    return parent::set_cache_id($bundle, $page);
  }
}
