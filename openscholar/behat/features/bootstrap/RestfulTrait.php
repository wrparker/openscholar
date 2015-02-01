<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;

trait RestfulTrait {

  /**
   * @var array
   *
   * Holds list of endpoints path.
   */
  private $endpoints = [
    'box' => 'api/boxes',
  ];

  /**
   * @var array
   *
   * List of widgets and the machine name they represent.
   */
  private $widgets = [
    'Terms' => 'os_taxonomy_fbt',
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
      return $this->accessToken[$user];
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
   * @Given /^I test the exposed resources:$/
   */
  public function iTestTheExposedResources(PyStringNode $resources) {
    foreach ($resources->getLines() as $line) {
      $this->getClient()->get($line);
    }
  }

  /**
   * @Given /^I "([^"]*)" a "([^"]*)" as "([^"]*)" with the settings:$/
   */
  public function iAAsWithTheSettings($operation, $type, $account, TableNode $table) {
    $values = $this->getValues($table);
    $token = $this->restLogin($account);

    $operations = [
      'create' => 'post',
      'update' => 'put',
      'delete' => 'delete',
    ];

    // Get the delta by specific conditions.
    if (!empty($values['Delta'])) {
      $delta = $values['Delta'] == 'PREV' ? $this->meta['widget']['delta'] : $values['Delta'];
    }
    else {
      $delta = time();
    }

    $request = '';
    try {
      $request = $this->getClient()->{$operations[$operation]}($this->locatePath($this->endpoints[$type]), [
        'headers' => ['access_token' => $token],
//    'future' => TRUE,
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

    $this->meta['widget'] = $request->json()['hal:boxes'][0][0];
  }
}
