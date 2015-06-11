<?php

/**
 * @contains
 */

class CsvExport extends ExportBase {
  /**
   * Export the content to a file.
   *
   * @param $name
   *   The name of the file.
   */
  public function exportToFile($name) {
    $content = $this->generateFileContent();

    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=\"$name\";" );

    echo $content;
    drupal_exit();
  }
}
