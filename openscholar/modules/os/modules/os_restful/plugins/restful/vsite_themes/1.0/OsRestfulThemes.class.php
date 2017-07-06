<?php

/**
 * @file
 * Contains \RestfulDataProviderDbQuery
 */

class OsRestfulThemes extends \RestfulBase implements \RestfulDataProviderInterface {

  /**
   * Overrides \RestfulBase::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '^.*$' => array(
        \RestfulInterface::GET => 'getThemes',
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {

  }

  /**
   * Get content type options.
   */
  public function getThemes() {

    ctools_include('themes', 'os');
    $themes = os_get_themes();
    ksort($themes);
    $radio_options = array();
    $default_flavor = '';

    $themes_sorted = array();
    $featured_themes = array_filter(variable_get('cp_appearance_featured_themes', array('aberdeen' => 'aberdeen', 'airstream' => 'airstream')));

    // Adds each theme option to the form, and flavor options if available.
    foreach ($themes as $theme) {
      $info = $theme->info;
      $info['theme_name'] = $theme->name;

      //Flavors is not shown for the featured themes
      if ((!in_array($theme->name, $featured_themes) || !count(os_theme_get_flavors($info['theme_name']))) && empty($theme->info['single'])) {
        // Only continues if this theme has flavors.
        $theme_flavors = os_theme_get_flavors($info['theme_name']);
        $flavor_options = _cp_appearance_get_flavor_options($theme_flavors, $info, $default_flavor);
      }
      $output[] = array('label' => $info['theme_name'], 'screenshot' => $info['screenshot'], 'flavor_name' => $info['flavor_name'], 'flavor_options' => $flavor_options);

    }

    return $output;
  }
}
