<?php

/**
 * @file
 *
 * Extending FeedsSource to add a 'finished' call back to batch processing
 */
class OsFeedsSource extends FeedsSource {

  /**
   * Batch API helper. Starts a Batch API job.
   *
   * @see FeedsSource::startImport().
   * @see FeedsSource::startClear().
   * @see feeds_batch()
   *
   * @param $title
   *   Title to show to user when executing batch.
   * @param $method
   *   Method to execute on importer; one of 'import' or 'clear'.
   */
  protected function startBatchAPIJob($title, $method) {
    $batch = array(
      'title' => $title,
      'finished' => 'os_batch_finished_callback',
      'operations' => array(
        array('feeds_batch', array($method, $this->id, $this->feed_nid)),
      ),
      'progress_message' => '',
    );
    batch_set($batch);
  }
}
