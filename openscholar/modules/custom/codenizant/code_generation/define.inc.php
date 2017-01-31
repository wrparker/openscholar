<?php
// Define values
define('ROWS_PER_PAGE', 10);
define('GENERIC_TEMPLATES_PATH', drupal_get_path('module', 'code_generation') .'/templates/generic');

// Path location where to save the zipped module files
define('MODULE_PATH', 'sites/nbcumedvil/files');
// Drupal Module Path Location
define('DRUPAL_MODULE_PATH', 'sites/nbcumedvil/modules');
// Drupal Theme Path Location
define('DRUPAL_THEME_PATH', 'sites/nbcumedvil/modules/codenizant/code_generation');
// Drupal Custom Module Path Location where develop modules are placed
define('DRUPAL_CUSTOM_PATH', 'sites/nbcumedvil/modules/custom');

// Allowed file extensions
define('ALLOWED_FILE_EXTENSIONS', 'Allowed file extensions for counting lines of codes are:- .php, .module, .inc, .install, .info, .js, .css, and .html');
define('SHELL_COMMAND_EXTENSIONS', 'php, module, inc, install, info, js, css, html');

