<?php
/**
 * @file
 * os_image_gallery.oembed_provider.inc
 */

/**
 * Implements hook_default_oembed_provider().
 */
function os_image_gallery_default_oembed_provider() {
  $export = array();

  $provider = new stdClass();
  $provider->disabled = FALSE; /* Edit this to true to make a default provider disabled initially */
  $provider->api_version = 1;
  $provider->name = 'Qualtrics';
  $provider->title = 'Qualtrics';
  $provider->endpoint = 'https://harvard.az1.qualtrics.com';
  $provider->scheme = 'https://*.qualtrics.com/*
http://*.qualtrics.com/*';
  $export['Qualtrics'] = $provider;

  return $export;
}
