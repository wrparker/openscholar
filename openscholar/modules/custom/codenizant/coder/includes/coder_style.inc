<?php

/**
 * @file
 * This include file implements coder functionality for Drupal Standards.
 */

/**
 * Implements hook_reviews().
 */
function coder_style_reviews() {
  $br = 'br';
  $rules = array(
    array(
      '#type' => 'regex',
      '#value' => '\t',
      '#warning' => 'Use an indent of 2 spaces, with no tabs',
    ),
    array(
      '#type' => 'regex',
      '#never' => '<\?php',
      '#value' => '^ (  )*[^ \'".]',
      '#warning' => 'Use an indent of 2 spaces, with no tabs',
      '#severity' => 'minor',
    ),
    array(
      '#type' => 'regex',
      '#value' => '\s(if|elseif|while|foreach|switch|case|return|for|catch)\(',
      '#warning' => 'Control statements should have one space between the control keyword and opening parenthesis',
    ),
    array(
      '#type' => 'regex',
      '#value' => '[\s\(](\w+)\s\(',
      '#not' => '^(if|elseif|while|foreach|switch|case|return|for|list|catch)$',
      '#warning' => 'Functions should be called with no spaces between the function name and opening parentheses',
    ),
    array(
      '#type' => 'regex',
      '#value' => '\){',
      '#warning' => 'use a space between the closing parenthesis and the open bracket',
    ),
    array(
      '#type' => 'regex',
      '#value' => '(\S=>|=>\S)',
      '#warning' => 'Arrays should be formatted with a space separating each element and assignment operator',
    ),
    /* Commenting out for string concatentation style change.
 array(
 '#type' => 'regex',
 '#value' => '(\.\s\'\'|\'\'\s\.|\.\s""|\.\s"")',
 '#warning' => 'string concatenation should be formatted without a space separating the operators (dot .) and a quote',
 ),
 */
    array(
      '#type' => 'regex',
      '#value' => '([^\(\'\"\s0-9]\.|\.[^\)\'\"\=\s0-9])',
      '#warning' => 'string concatenation should be formatted with a space separating the operators (dot .) and non-quote terms',
    ),
    array(
      '#type' => 'regex',
      '#value' => '<\?(\w+)',
      '#not' => '^(php|xml)$',
      '#warning' => 'Always use &lt;?php ?&gt; to delimit PHP code, not the &lt;? ?&gt; shorthand',
    ),
    array(
      '#type' => 'regex',
      '#value' => 'global\s+\$(\w+)(,\s\$(\w+))*',
      '#not' => '^_|^(' . _coder_style_core_global_regex() . ')$',
      '#warning' => 'global variables should start with a single underscore followed by the module and another underscore',
    ),
    array(
      '#type' => 'callback',
      '#source' => 'all',
      '#value' => '_coder_style_closing_php_callback',
      '#warning' => 'the final ?> should be omitted from all code files',
    ),
    array(
      '#type' => 'regex',
      '#value' => '}\s*else',
      '#warning' => 'else statements should begin on a new line',
    ),
    array(
      '#type' => 'regex',
      '#value' => '[,][^ \n\r]',
      '#warning' => 'missing space after comma',
    ),
    array(
      '#type' => 'regex',
      '#value' => '^\s*{',
      '#warning' => 'curly braces { should end a line, not start one',
    ),
    array(
      '#type' => 'regex',
      '#value' => '(?-i)(function\s+|\$)(([a-z]+[A-Z]+([a-z]*[A-Z]*)*)|([A-Z]+[a-z]+([A-Z]*[a-z]*)*))',
      '#warning' => 'do not use mixed case (camelCase), use lower case and _',
      '#class-not' => '.+',
      '#filename-not' => array('test'), // Add this line too for patch files, where we don't have have class context.
    ),
    array(
      '#type' => 'regex',
      '#value' => '\s(stdclass)\s*\(',
      '#not' => '^(?-i)stdClass$',
      '#warning' => "use stdClass caseCapitalization, it's the one exception to the mixed case style standard",
    ),
    array(
      '#type' => 'regex',
      '#source' => 'html',
      '#value' => '<' . $br . '>', // NOTE: use $br only to avoid a warning.
      '#warning' => 'use &lt;br /&gt; instead of &lt;br&gt;',
      '#severity' => 'minor',
    ),
    array(
      '#type' => 'regex',
      '#source' => 'html',
      '#value' => '(?-i)<[A-Z]+',
      '#warning_callback' => '_coder_style_xhtml_warning',
      '#severity' => 'minor',
    ),
    array(
      '#type' => 'regex',
      '#value' => '\s(if|elseif|while|foreach|switch|return|for|catch)\s*\(.*\) \s*{\s*[^\s]+',
      '#warning' => 'The control statement should be on a separate line from the control conditional',
    ),
    array(
      '#type' => 'regex',
      '#source' => 'all',
      '#value' => '[ \t]+$',
      '#warning' => 'There should be no trailing spaces',
      '#severity' => 'minor',
    ),
    array(
      '#type' => 'regex',
      '#value' => '[\s\(](strlen|strtolower|strtoupper|substr|ucfirst)\s*\(',
      '#warning' => 'in most cases, replace the string function with the drupal_ equivalent string functions',
      '#severity' => 'minor',
    ),
    array(
      '#type' => 'regex',
      '#value' => '\[\s*[A-Za-z][A-Za-z0-9_]*\s*]',
      '#not' => '\[\s*[A-Z][A-Z0-9_]*\s*]',
      '#warning' => 'use quotes around a string literal array index, this is not only a style issue, but a known performance problem',
      '#case-sensitive' => TRUE,
    ),
    array(
      '#type' => 'regex',
      '#value' => '[\s=>]+(true|false|null)[\)\s;,\n\r]+',
      '#case-sensitive' => TRUE,
      '#warning' => 'Use uppercase for PHP constants, e.g. NULL, TRUE, FALSE',
    ),
    array(
      '#type' => 'regex',
      '#value' => '\s+else\s+if\s*\(',
      '#warning' => 'Use "elseif" in place of "else if"',
    ),
    //This one is custom for the code style and standard
    array(
      '#type' => 'regex',
      '#source' => 'php',
      '#value' => '[$][A-Za-z0-9_]{30,}', //[\$]{2,10} //[\\d,;] //[$].{2,10}\s(=)
      '#warning' => 'Do not use a big variable name, must me under 30 characters',
    ),
    /*array(
 '#type' => 'regex',
 '#source' => 'php',
 '#value' => '[$].*[^;]$',
 '#warning' => 'There must be a semicolon at the end',
 ),
 */
    array(
      '#type' => 'regex',
      '#source' => 'php',
      '#value' => '\$GLOBALS\[\'\'\]\s*=',
      '#warning' => 'variables inside $GLOBALS have to use single quotes to wrap them',
    ),
    /*array(
 '#type' => 'regex',
 '#source' => 'php',
 '#value' => 'define[(](\\\'|\")[^A-Z]+', //define\(["\'][^A-Z]["\'] //define\([\'"][^A-Z] //\define[(](\'|\")[^A-Z]+ {define[(](\'|\")[^A-Z]+} //define[(](\\\'|\")[^A-Z]+
 '#case-sensitive' => TRUE,
 '#warning' => 'The definition keys should be in Upper Case',
 ),*/
    array(
      '#type' => 'regex',
      '#source' => 'php',
      '#value' => 'define[(][^\\\'|^\"]',
      '#warning' => 'variables inside define() should have a double or single quote',
    ),
    array(
      '#type' => 'regex',
      '#source' => 'php',
      '#value' => '\b(include|require|include_once)\b',
      '#not' => 'require_once',
      '#warning' => 'Using require_once() is a good practiece in the code',
    ),
    /*array(
 '#type' => 'regex',
 '#value' => '((\S=|=\S)|(\S>|>\S)|(\S<|<\S)|(\S\|\||\|\|\S)|(\S%|%\S)|(\S\+|\+\S)|(\S-|-\S)|(\S\*|\*\S)|(\S\/|\/\S)|(\S&|&\S)|(\S\||\|\S)|(\S\^|\^\S)|(\S<<|<<\S)|(\S>>|>>\S)|(\S<>|<>\S))',
 '#warning' => 'There should be a blank space in between the operators',
 ),*/
    array(
      '#type' => 'regex',
      '#value' => '(class|Class)\s[^A-Z][a-z0-9_-]{3,}',
      '#warning' => 'The class name should be capitalized',
      '#case-sensitive' => TRUE,
    ),
    array(
      '#type' => 'callback',
      '#source' => 'all',
      '#value' => '_coder_style_closing_if_else_callback',
      '#warning' => 'test',
    ),
  );
  $review = array(
    '#title' => t('Drupal Coding Standards'),
    '#link' => 'http://drupal.org/node/318',
    '#rules' => $rules,
    '#description' => t('every developer should use'),
  );
  return array('style' => $review);
}

/**
 * Define the rule callbacks for style.
 */
function _coder_style_closing_php_callback(&$coder_args, $review, $rule, $lines, &$results) {
  if (empty($lines)) {
    return;
  }
  for ($lineno = -1; $last = array_slice($lines, $lineno); $lineno--) {
    $lastline = $last[0][0];
    if (preg_match('/\S/', $lastline)) {
      break;
    }
  }
  if ($last && $lastline && preg_match('/\?>\s*$/i', $lastline)) {
    $severity_name = _coder_severity_name($coder_args, $review, $rule);
    _coder_error($results, $rule, $severity_name, count($lines));
  }
}
/**
 * Function Parses the file and checks for the control statement ends
 * @param unknown_type $coder_args
 * @param unknown_type $review
 * @param unknown_type $rule
 * @param unknown_type $lines
 * @param unknown_type $results
 */
function _coder_style_closing_if_else_callback(&$coder_args, $review, $rule, $lines, &$results) {
  //Process the $lines array to get the if statements
  $found_if_count = 0;
  $found_else_count = 0;
  $if_else_balence = array(
    'if_count' => $found_if_count,
    'else_count' => $found_else_count,
    'if_found' => array(),
    'else_found' => array(),
  );
  foreach ($lines as $line_num => $line_text) {
    if (preg_match('/if\(.*\)\s\{/', $line_text[0])) {
      $found_if_count++;
      $if_else_balence['if_count'] = $found_if_count;
      $if_else_balence['if_found'][$line_num] = $line_text[0];
    }
    if (preg_match('/else\s{/', $line_text[0])) {
      $found_else_count++;
      $if_else_balence['else_count'] = $found_else_count;
      //To add else found line is added but not important at this stage
      //$if_else_balence['else_found'][$line_num] = $line_text[0];
      //Reduce the if count/found from the array and mark the last one
      $last_if_line = key( array_slice( $if_else_balence['if_found'], -1, 1, TRUE ) );
      unset($if_else_balence['if_found'][$last_if_line]);
    }
  }
  //$severity_name = _coder_severity_name($coder_args, $review, $rule);
  watchdog('debug', '<pre>' . print_r($if_else_balence, TRUE) . '</pre>');
}

function _coder_style_core_global_regex() {
  static $coreglobalregex, $coreglobalvars;
  if (!isset($coreglobalregex)) {
    // Note: there's a little extra overhead in formatting this list as an
    // array, but I think it makes it more readable and maintainable.
    $coreglobalvars = array(
      // From the Drupal 5 includes/ directory.
      'active_db',
      'base_path',
      'base_root',
      'base_url',
      'conf',
      'custom_theme',
      'db_prefix',
      'db_type',
      'db_url',
      'form_button_counter',
      'form_submitted',
      'form_values',
      'install_locale',
      'installed_profile',
      'language',
      'last_result',
      'locale',
      'multibyte',
      'pager_page_array',
      'pager_total',
      'pager_total_items',
      'profile',
      'queries',
      'sidebar_indicator',
      'theme',
      'theme_engine',
      'theme_key',
      'theme_path',
      'timers',
      'user',
      'xrds_services',
      'xrds_open_elements',
      'xrds_current_service',
      // From the Drupal 5 modules/ directory -
      // Note: IMHO these should not be allowed, but until we fix core,
      // other modules will need them.
      'channel',
      'element',
      'forum_topic_list_header',
      'id',
      'image',
      'item',
      'items',
      'last_change',
      'last_nid',
      'nid',
      'recent_activity',
      'tag',
    );
    $coreglobalregex = implode('|', $coreglobalvars);
  }
  return $coreglobalregex;
}

/**
 * Define the warning callbacks.
 */

function _coder_style_xhtml_warning() {
  return t('use lowercase html tags to comply with <a href="@xhtml">XHTML</a>',
    array(
    '@xhtml' => 'http://www.w3.org/TR/xhtml1/#h-4.2',
  )
  );
}
