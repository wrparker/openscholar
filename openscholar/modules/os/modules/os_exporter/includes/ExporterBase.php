<?php

abstract class ExportBase {

  protected $content = array();

  /**
   * Concatenating content to the content variable.
   *
   * @param $content
   *   The value to add.
   */
  public function addContent($content) {
    $this->content[] = $content;
    return $this;
  }

  /**
   * Returning the content variable.
   *
   * @return array
   *   Get the content variable.
   */
  public function getContent() {
    return $this->content;
  }


  /**
   * Set the content variable to a specific value.
   *
   * @param $content
   *   The content to set.
   *
   * @return $this
   *   The current object.
   */
  public function setContent($content) {
    $this->content = $content;
    return $this;
  }

  /**
   * @return string
   *   Generate the file.
   */
  protected function generateFileContent() {
    $file_content = '';

    foreach ($this->content as $content) {
      $file_content .= implode(',', $content) . "\n";
    }

    return $file_content;
  }

  /**
   * Export the content to a file.
   *
   * @param $name
   *   The name of the file.
   */
  abstract function exportToFile($name);

  /**
   * Put array in the beginning of the content variable.
   *
   * @return $this
   *   The current object.
   */
  public function setFileHeader($header) {
    $content = $this->content;

    $this->content = array($header);
    $this->content[] = $content;
    return $this;
  }
}
