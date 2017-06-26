<?php

/**
 * @file
 * hwpi.api.php
 */

/**
 * Change HWPI ancestry sites.
 *
 * @param $sites
 *   List of sites keyed by site address and the value is the label of the site.
 */
function hook_hwpi_ancestry_alter(&$sites) {
  $sites['http://edu.foo.com'] = 'Foo education site';
}
