<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Message\ResponseInterface;

/**
 * Class RestfulTrait
 */
trait RestfulTrait {

  /**
   * @var array
   *
   * Holds list of endpoints path.
   */
  private $endpoints = [
    'box' => 'api/boxes',
    'layout' => 'api/layouts',
  ];

  /**
   * @var array
   *
   * List of widgets and the machine name they represent.
   */
  private $widgets = [
    'Terms' => 'os_taxonomy_fbt',
    'Bio' => 'os_boxes_bio',
  ];

  /**
   * @var String
   *
   * Holds the access token for the user.
   */
  private $accessToken = [];

  /**
   * @var array
   *
   * Generic metadata from tests.
   */
  private $meta = [];

  /**
   * @var array
   *
   * List of operations.
   */
  private $operations = [
    'create' => 'post',
    'update' => 'put',
    'delete' => 'delete',
  ];

  /**
   * @var array
   *
   * Results from a JSON request.
   */
  private $results = [];

  /**
   * Alias for Guzzle client.
   *
   * @return \GuzzleHttp\Client
   */
  private function getClient() {
    return new GuzzleHttp\Client();
  }

  /**
   * Login via rest to get the user's access token.
   *
   * @param $user
   *   The user name.
   *
   * @return string
   *   The user access token.
   */
  private function restLogin($user) {
    if (isset($this->accessToken[$user])) {
      return $this->accessToken[$user]['access_token'];
    }

    $base = base64_encode($user . ':' . $this->users[$user]);
    $login_data = $this->getClient()->get($this->locatePath('api/login-token'), [
      'headers' => [
        'Authorization' => 'Basic ' . $base,
      ],
    ]);

    $data = $login_data->json();
    $this->accessToken[$user] = $data;
    return $data['access_token'];
  }

  /**
   * Handling non 200 http request.
   *
   * @param \GuzzleHttp\Exception\ClientException $e
   *   The client exception handler.
   *
   * @throws Exception
   */
  private function handleExceptions(\GuzzleHttp\Exception\ClientException $e) {
    $json = $e->getResponse()->json();

    $implode = array();
    if (!empty($json['errors'])) {
      foreach ($json['errors'] as $errors) {
        foreach ($errors as $error) {
          $implode[] = $error;
        }
      }
    }
    else {
      $implode[] = $json['title'];
    }

    $errors = implode(', ', $implode);
    throw new Exception('Your request has failed: ' . $errors);
  }

  /**
   * Take the table head and the table body into one single array.
   *
   * todo: handle more then one line.
   *
   * @param TableNode $table
   *   The table object.
   *
   * @return array
   */
  private function getValues(TableNode $table) {
    $rows = $table->getRows();
    return array_combine($rows[0], $rows[1]);
  }

  /**
   * Get the delta for the widget.
   *
   * @param $values
   *   The settings from the step definition.
   * @return int
   *   The delta of the widget. for a new widget the timestamp will be returned.
   */
  private function getDelta($values) {
    // Get the delta by specific conditions.
    if (!empty($values['Delta'])) {
      return $values['Delta'] == 'PREV' ? $this->meta['widget']['delta'] : $values['Delta'];
    }
    else {
      return time();
    }
  }

  /**
   * Verify the rest operation passed.
   *
   * @param $operation
   *   The type of the operation: PUT, DELETE or POST.
   *
   * @throws Exception
   */
  private function verifyOperationPassed($operation) {
    // Verify the request did what it suppose to do.
    if ($operation == 'delete') {
      if ($this->results['data'][0]['value']['description'] == $this->meta['widget']['description']) {
        throw new Exception('The box was not deleted.');
      }
    }
    else {

      if ($this->results['data'][0][0]['value']['description'] != $this->meta['widget']['description']) {
        throw new Exception('The results for the box not matching the settings you passed.');
      }
    }
  }

  /**
   * Return array with a needed variables to the rest operations.
   *
   * @param $type
   *   The type of the operation.
   * @param $account
   *   The user name.
   * @param TableNode $table
   *   The table settings from the step definition.
   *
   * @return array
   */
  private function setVariables($type, $account, TableNode $table) {
    return [
      $this->getValues($table),
      $this->restLogin($account),
      $this->locatePath($this->endpoints[$type]),
    ];
  }

  /**
   * @Given /^I test the exposed resources:$/
   */
  public function iTestTheExposedResources(PyStringNode $resources) {
    foreach ($resources->getLines() as $line) {
      $this->getClient()->get($this->locatePath($line));
    }
  }

  /**
   * @Given /^I "([^"]*)" a box as "([^"]*)" with the settings:$/
   */
  public function iAAsWithTheSettings($operation, $account, TableNode $table) {
    list($values, $token, $path) = $this->setVariables('box', $account, $table);
    $delta = $this->getDelta($values);

    $request = '';
    try {
      /** @var ResponseInterface $request */
      $request = $this->getClient()->{$this->operations[$operation]}($path, [
        'headers' => ['access_token' => $token],
        'body' => [
          'vsite' => FeatureHelp::getNodeId($values['Site']),
          'delta' => $delta,
          'widget' => $this->widgets[$values['Widget']],
          'options' => [
            'description' => $values['Description'],
          ],
        ],
      ]);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      $this->handleExceptions($e);
    }

    $this->meta['widget'] = $request->json()['data'][0][0];
    $this->results = $this->getClient()->get($path . '?delta=' . $delta)->json();
    $this->verifyOperationPassed($operation);
  }

  /**
   * @Given /^I "([^"]*)" a layout as "([^"]*)" with the settings:$/
   */
  public function iALayoutAsWithTheSettings($operation, $account, TableNode $table) {
    list($values, $token, $path) = $this->setVariables('layout', $account, $table);
    $op = $this->operations[$operation];
    $delta = $this->getDelta($values);

    if ($op == 'post') {
      try {
        /** @var ResponseInterface $request */
        $request = $this->getClient()->{$this->operations[$operation]}($this->locatePath($this->endpoints['box']), [
          'headers' => ['access_token' => $token],
          'body' => [
            'vsite' => FeatureHelp::getNodeId($values['Site']),
            'delta' => $delta,
            'widget' => $this->widgets[$values['Box']],
            'options' => [
              'description' => 'Widget for testing a layout',
            ],
          ],
        ]);
      } catch (\GuzzleHttp\Exception\ClientException $e) {
        $this->handleExceptions($e);
      }
    }

//    $request = '';
//    try {
//      /** @var ResponseInterface $request */
//      $request = $this->getClient()->{$op}($path, [
//        'headers' => ['access_token' => $token],
//        'body' => [
//          'vsite' => FeatureHelp::getNodeId($values['Site']),
//          'object_id' => $values['Context'],
//          'blocks' => $values['Blocks'],
//        ],
//      ]);
//    } catch (\GuzzleHttp\Exception\ClientException $e) {
//      $this->handleExceptions($e);
//    }
  }

}
