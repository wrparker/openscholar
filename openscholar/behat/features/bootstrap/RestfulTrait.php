<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;

trait RestfulTrait {

  /**
   * @var array
   *
   * Holds list of endpoints path.
   */
  private $endpoints = array(
    'box' => 'api/boxes',
  );

  /**
   * @var array
   *
   * List of widgets and the machine name they represent.
   */
  private $widgets = array(
    'Terms' => 'os_taxonomy_fbt',
  );

  /**
   * @var String
   *
   * Holds the access token for the user.
   */
  private $accessToken = array();

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
   * Handling bad 404 restful request.
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
   * @Given /^I create a "([^"]*)" as "([^"]*)" with the settings:$/
   */
  public function iCreateAAsWithTheSettings($type, $account, TableNode $table) {
    $values = $this->getValues($table);
    $token = $this->restLogin($account);

    try {
      $this->getClient()->post($this->locatePath($this->endpoints[$type]), [
        'headers' => ['access_token' => $token],
        'body' => [
          'vsite' => FeatureHelp::getNodeId($values['Site']),
          'delta' => time(),
          'widget' => $this->widgets[$values['Widget']],
          'options' => [
            'description' => $values['Description'],
          ],
        ],
      ]);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      $this->handleExceptions($e);
    }
  }
}
