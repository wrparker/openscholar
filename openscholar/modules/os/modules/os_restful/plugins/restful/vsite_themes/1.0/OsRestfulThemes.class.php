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
     return array();
  }

  /**
   * Get content type options.
   */
  public function getThemes() {
    ctools_include('themes', 'os');
    $themes = os_get_themes();
    ksort($themes);
    $output = array();
    $flavor_options_bind = array();
    $default_flavor = '';

    $themes_sorted = array();
    $featured_themes = array_filter(variable_get('cp_appearance_featured_themes', array('aberdeen' => 'aberdeen', 'airstream' => 'airstream')));

    // From where we get the list of single page themes?
    $single_themes = array();

    // Adds each theme option to the form, and flavor options if available.
    foreach ($themes as $theme) {
      // Adds this theme to the radio options.
      $info = $theme->info;
      $info['theme_name'] = $theme->name;

      //Flavors is not shown for the featured themes
      if ((!in_array($theme->name, $featured_themes) || !count(os_theme_get_flavors($info['theme_name']))) && empty($theme->info['single'])) {
        //Add this theme to the list of theme options.
        $radio_options[] = array('name' => $info['name'],
          'themeKey' => $info['theme_name'],
          'screenshot' => $info['screenshot'],
          'flavorName' => $info['flavor_name'],
          'flavorOptions' => $flavor_options,
        );
        $themes_sorted[$info['theme_name']] = $theme;

        // Only continues if this theme has flavors.
        $theme_flavors = os_theme_get_flavors($info['theme_name']);
        $flavor_options = _cp_appearance_get_flavor_options($theme_flavors, $info, $default_flavor);
        if (!empty($flavor_options)) {
          foreach ($flavor_options as $key => $value) {
            $flavor_options_bind[] = array('key' => $key, 'name' => $value);
          }
          $flavor_options = $flavor_options_bind;
        }
      }
      else {
        //Add this theme to the list of theme options.
        $radio_options[] = array('name' => $info['name'],
          'themeKey' => $info['theme_name'].'-os_featured_flavor-default',
          'screenshot' => $info['screenshot'],
          'flavorName' => $info['flavor_name'],
          'flavorOptions' => $flavor_options,
        );
        $theme->name = $info['theme_name'].'-os_featured_flavor-default';
        $themes_sorted[$info['theme_name'].'-os_featured_flavor-default'] = $theme;
        $theme->info['theme_flavor_name'] = $info['flavor_name'];

        $flavors = os_theme_get_flavors($info['theme_name']);
         foreach ($flavors as $flavor_name => $flav) {
          $flavor_theme = (object)array(
            'filename' => $flav['path'].'/'.$flav['file'],
            'name' => $info['theme_name'].'-os_featured_flavor-'.$flavor_name,
            'type' => 'theme',
            'owner' => 'themes/engines/phptemplate/phptemplate.engine',
            'status' => '1',
            'weight' => '0',
            'bootsrap' => '0',
            'schema_version' => '-1',
            'info' => drupal_parse_info_file($flav['path'].'/'.$flav['file']),
            'base_themes' => $theme->base_themes,
            'prefix' => 'phptemplate',
            'stylesheets' => $theme->stylesheets,
            'engine' => 'phptemplate',
            'base_theme' => 'hwpi_basetheme',
            'is_flavor' => true,  // Indicate that this is a flavor not a full theme.
            'single' => $theme->info['single'],
          );
          $flavor_theme->info['flavor_name'] = $flavor_theme->info['name'];
          $flavor_theme->info['name'] = $info['name'];
          $flavor_theme->info['theme_flavor_name'] = $flavor_theme->info['flavor_name'];

          $flavor_theme->info['screenshot'] = $flav['path'].'/screenshot.png';
          $featured_themes[$info['theme_name'].'-os_featured_flavor-'.$flavor_name] = $info['theme_name'].'-os_featured_flavor-'.$flavor_name;
          if (empty($theme->info['single'])) {
            $themes_sorted[$info['theme_name'].'-os_featured_flavor-'.$flavor_name] = $flavor_theme;
          }
          else {
            $single_themes[$flavor_theme->name] = $flavor_theme;
          }
          $radio_options[] = array('name' => $flavor_theme->info['theme_flavor_name'],
            'themeKey' => $info['theme_name'].'-os_featured_flavor-'.$flavor_name,
            'screenshot' => $flavor_theme->info['screenshot'],
            'flavorName' => $flavor_theme->info['flavor_name'],
            'flavorOptions' => $flavor_options,
          );
        }
      }
    }


    // @todo
    // We need to figure out feature theme and normal theme.
    foreach ($radio_options as $key => $value) {
      if (in_array($value['themeKey'], $featured_themes)) {
        //unset($radio_options[$key]);
      }
    }

    print_r($featured_themes);

    return $radio_options;

  }
}
