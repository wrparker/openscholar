<?php

/**
 * @file
 * Contains OsActivitiesResource.
 */

class OsActivitiesResource extends RestfulEntityBaseMultipleBundles {

  /**
   * Define the bundles to exposed to the API.
   *
   * @var array
   *  Array keyed by bundle machine, and the RESTful resource as the value.
   */
  protected $bundles = array(
    'os_create_node' => 'activities',
    'os_edit_node' => 'activities',
  );
}
