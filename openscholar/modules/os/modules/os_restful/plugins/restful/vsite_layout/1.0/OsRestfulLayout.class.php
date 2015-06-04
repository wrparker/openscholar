<?php

/**
 * @file
 * Contains \OsRestfulLayout
 */

class OsRestfulLayout extends \OsRestfulSpaces {

  protected $validateHandler = 'layouts';
  protected $objectType = 'context';

  /**
   * Verify the user have access to the manage layout.
   */
  public function checkGroupAccess() {
    parent::checkGroupAccess();
    $account = $this->getAccount();

    if (!spaces_access_admin($account, $this->space)) {
      // The current user can't manage boxes.
      $this->throwException("You can't manage layout in this vsite.");
    }
  }

  /**
   * @api {patch} api/layout Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Layout
   *
   * @apiDescription Update a layout in a vsite.
   *
   * @apiParam {Number} object_id The identifier of the layout object.
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Integer}  vsite       vsite ID.
   * @apiSuccess {String}   object_id   Identifier of the layout object.
   * @apiSuccess {Array}  blocks      Array of blocks.
   *
   * @apiExample {js} Example usage:
   *  vsite: 2,
   *  object_id: os_pages-page-581,
   *  blocks: [
   *    os_search_db-site-search: [
   *      region: "sidebar_first"
   *    ]
   *  ]
   */
  public function updateSpace() {
    // Check group access.
    $this->checkGroupAccess();

    // Validate the object from the request.
    $this->validate();

    // Set up the blocks layout.
    ctools_include('layout', 'os');

    $blocks = os_layout_get($this->object->object_id, FALSE, FALSE, $this->space);

    foreach ($blocks as $delta => $block) {
      if (empty($this->object->blocks[$delta])) {
        continue;
      }
      $blocks[$delta] = array_merge($blocks[$delta], $this->object->blocks[$delta]);
    }

    os_layout_set($this->object->object_id, $blocks, $this->space);

    return $blocks;
  }

  /**
   * @api {post} api/layout Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Layout
   *
   * @apiDescription Create a layout in a vsite.
   *
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Integer}  vsite         vsite ID.
   * @apiSuccess {String}   object_id     Identifier of the layout object.
   * @apiSuccess {Array}    blocks        Array of blocks.
   * @apiSuccess {String}   blocks.module The name of the module which provide the block.
   * @apiSuccess {String}   blocks.delta  The delta of the box.
   * @apiSuccess {String}   blocks.region The region In the body.
   * @apiSuccess {String}   blocks.weight The weight of the module. Will affect
   * the order of the blocks in the same region.
   * @apiSuccess {Boolean}  blocks.status Determine if the block will be display or not.
   *
   * @apiExample {js} Example usage:
   *  vsite: 2,
   *  object_id: os_pages-page-581,
   *  boxes: [
   *    boxes-1419335380: [
   *      module: "boxes",
   *      delta: "1419335380",
   *      region: "sidebar_second",
   *      weight: 2,
   *      status: 0
   *    ]
   *  ]
   */
  public function createSpace() {
    // Check group access.
    $this->checkGroupAccess();

    // Validate the object from the request.
    $this->validate();

    if (!isset($this->object->blocks['os_pages-main_content'])) {
      // When creating the layout override we need the page content.
      $this->object->blocks['os_pages-main_content'] = array(
        'module' => "os_pages",
        'delta' => "main_content",
        'region' => "content_top",
      );
    }

    // Set up the blocks layout.
    ctools_include('layout', 'os');

    os_layout_set($this->object->object_id, $this->object->blocks, $this->space);

    return $this->object->blocks;
  }

  /**
   * In order to delete the layout override pass the next arguments:
   *
   * type: DELETE
   * values: {
   *  vsite: 2,
   *  object_id: os_pages-page-582:reaction:block,
   *  delta: boxes-1419335380
   * }
   */
  public function deleteSpace() {
    // Check group access.
    $this->checkGroupAccess();

    db_delete('spaces_overrides')
      ->condition('object_id', $this->object->object_id)
      ->condition('id', $this->object->vsite)
      ->execute();
  }
}
