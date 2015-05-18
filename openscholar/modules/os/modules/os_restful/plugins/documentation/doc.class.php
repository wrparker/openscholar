<?php

abstract class documentation {
  /**
   * @api {get} api/session/token X CSRF token
   * @apiVersion 0.1.0
   * @apiName Basic
   * @apiGroup Basic
   *
   * @apiDescription
   * When running a REST request inside a Drupal installation RESTful will
   * validate you need to pass an X-CSRF-TOKEN as a header for each request.
   * This will make sure that the user of the cookie was not hijacked.
   *
   * @apiSampleRequest off
   *
   * @apiSuccessExample {json} Success-Response:
   *     {
   *       "X-CSRF-Token": "pgaSEyNaDELTBuPXy-Jpx_6I-mrEruxH3_-BEcMtnU0"
   *     }
   */
  abstract public function XCsrfTokenTokenGrant();

  /**
   * @api {get} api/login-token Access Token
   * @apiVersion 0.1.0
   * @apiName Access token
   * @apiGroup Basic
   *
   * @apiDescription
   * Access token uses us for request which done outside the Drupal installation.
   * A good example will be a mobile application try to access content which not
   * accessible to anonymous users. Since the request is done outside the Drupal
   * installation we need a way to verify which user involve in the request.
   *
   * In the example you can see how a request to get the access token is done
   * using Angular JS. Once the request succeeded you will get back a JSON with
   * three important parameters:
   * * `access_token` will keep the access token that you can use in order to make a
   * successful rest request.
   * * `expires_in` define for how many times the access token is valid. Usually for
   * one day.
   * * `refresh_token` will used you to get a new access token once the previous has
   * expired.
   *
   * @apiSampleRequest off
   *
   * @apiExample {js} Example usage:
   *  $http.get(backend + 'login-token', {
   *    headers: {'Authorization': 'Basic ' + Base64.encode($scope.user.name + ':' + $scope.user.pass)}
   *  }).success(function(data) {
   *    localStorageService.set('access_token', data.access_token);
   *  });
   *
   * @apiHeaderExample {json} Header example:
   *  {
   *    "Authorization": "Basic YWRtaW46YWRtaW4="
   *  }
   *
   * @apiSuccessExample {json} Success response:
   *  {
   *    access_token: "Y3wQua-qFY-mukslgPaLqKdNmlGdBQK4dly-UhlJcYk",
   *    type: "Bearer",
   *    expires_in: 86400,
   *    refresh_token: "xRP-nnKA05GGsN-jr80Z_hfPHqrkpyjAtevDSeTLbYU"
   *  }
   *
   * @apiErrorExample {json} Failed response:
   *  {
   *    type: "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.4.2",
   *    title: "Bad credentials",
   *    status: 401,
   *    detail: "Unauthorized."
   *  }
   */
  abstract public function AccessTokenTokenGrant();

  /**
   * @api {get} api/ End points
   * @apiVersion 0.1.0
   * @apiName End points
   * @apiGroup Basic
   *
   * @apiDescription
   * One of RESTful good features is the API discovery.
   *
   * @apiSampleRequest api/
   */
  abstract public function endpoints();
}
