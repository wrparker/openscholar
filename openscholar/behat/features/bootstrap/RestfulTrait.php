<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Message\FutureResponse;
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
    'variable' => 'api/variables',
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
      print_r([__LINE__, $this->accessToken]);
      return $this->accessToken[$user]['access_token'];
    }

    $request = $this->getClient()->get($this->locatePath('api'));
    print_r($request->json());

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
  private function handleExceptions(\GuzzleHttp\Exception\ClientException $e, $return = FALSE) {
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

    if ($return) {
      return $errors;
    }

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
      return $values['Delta'] == 'PREV' ? $this->meta['delta'] : $values['Delta'];
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
      if ($this->results['data']['value']['description'] != $this->meta['widget']['description']) {
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
   * Invoke a rest request.
   *
   * @param $method
   *   The method: POST, GET etc. etc.
   * @param $path
   *   The path of the request.
   * @param $headers
   *   The headers of the request.
   * @param $body
   *   The body of the request AKA the payload.
   * @param $return
   *   Determine if we need to return the request errors.
   *
   * @return ResponseInterface
   *   The request object.
   * @throws Exception
   */
  private function invokeRestRequest($method, $path, $headers, $body, $return = FALSE) {
    try {
      $request = $this->getClient()->{$method}($path, [
        'headers' => $headers,
        'body' => $body,
      ]);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      return $this->handleExceptions($e, $return);
    }

    return $request;
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

    $request = $this->invokeRestRequest($this->operations[$operation], $path,
      ['access_token' => $token],
      [
        'vsite' => FeatureHelp::getNodeId($values['Site']),
        'delta' => $delta,
        'widget' => $this->widgets[$values['Widget']],
        'options' => [
          'description' => $values['Description'],
        ],
      ]
    );

    $this->meta['delta'] = $delta;
    $this->meta['widget'] = $request->json()['data'];
    $this->results = $this->getClient()->get($path . '?delta=' . $delta)->json();
    $this->verifyOperationPassed($operation);
  }

  /**
   * @Given /^I "([^"]*)" a layout as "([^"]*)" with the settings:$/
   */
  public function iALayoutAsWithTheSettings($operation, $account, TableNode $table) {
    list($values, $token, $path) = $this->setVariables('layout', $account, $table);
    $box_path = $this->locatePath($this->endpoints['box']);
    $op = $this->operations[$operation];
    $delta = $this->getDelta($values);

    if ($op == 'post') {
      $this->invokeRestRequest($op, $box_path,
        ['access_token' => $token],
        [
          'vsite' => FeatureHelp::getNodeId($values['Site']),
          'delta' => $delta,
          'widget' => $this->widgets[$values['Box']],
          'options' => [
            'description' => 'Widget for testing a layout',
          ],
        ]
      );

      $blocks = [
        'boxes-' . $delta => [
          'module' => 'boxes',
          'delta' => $delta,
          'region' => 'sidebar_second',
          'weight' => 2,
          'status' => 0,
        ],
      ];
    }
    elseif ($op == 'put') {
      $blocks = [
        'boxes-' . $delta => [
          'region' => 'sidebar_first',
        ],
      ];
    }
    else {
      // Create the layout override.
      $this->invokeRestRequest($op, $path,
        ['access_token' => $token],
        [
          'vsite' => FeatureHelp::getNodeId($values['Site']),
          'object_id' => $values['Context'],
          'delta' => 'boxes-' . $delta,
        ]
      );
      return;
    }

    // Create the layout override.
    $this->invokeRestRequest($op, $path,
      ['access_token' => $token],
      [
        'vsite' => FeatureHelp::getNodeId($values['Site']),
        'object_id' => $values['Context'],
        'blocks' => $blocks,
      ]
    );

    $this->meta['delta'] = $delta;
  }

  /**
   * @Given /^I "([^"]*)" the variable "([^"]*)" as "([^"]*)" with the value "([^"]*)" in "([^"]*)"$/
   */
  public function iTheVariableAsWithTheValue($operation, $name, $account, $value, $site) {
    $token = $this->restLogin($account);
    $path = $this->locatePath($this->endpoints['variable']);
    $op = $this->operations[$operation];

    $this->invokeRestRequest($op, $path,
      ['access_token' => $token],
      [
        'vsite' => FeatureHelp::getNodeId($site),
        'object_id' => $name,
        'value' => $value,
      ]
    );
  }

  /**
   * @Given /^I try to "([^"]*)" a box as "([^"]*)" in "([^"]*)"$/
   */
  public function iTryToABoxAsIn($operation, $account, $site) {
    $token = $this->restLogin($account);
    $path = $this->locatePath($this->endpoints['box']);
    $delta = $this->getDelta(array());

    $request = $this->invokeRestRequest($this->operations[$operation], $path,
      ['access_token' => $token],
      [
        'vsite' => FeatureHelp::getNodeId($site),
        'delta' => $delta,
        'widget' => $this->widgets['Terms'],
        'options' => [
          'description' => 'Dummy one',
        ],
      ],
      TRUE
    );

    if ($request != "You can't manage boxes in this vsite.") {
      throw new Exception('The user did not got the expected message.');
    }
  }

}
