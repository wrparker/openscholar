<div ng-app="remoteLogin">

  <form ng-controller="remoteLoginForm" ng-submit="login()" novalidate>

    <div ng-show="status">
      <h2>{{status}}<h2>
    </div>

    <label for="username">Username</label>
    <input type="text" id="username" ng-model="username" />

    <label for="password">Password</label>
    <input type="password" id="password" ng-model="password" />

    <div>
      <input type="submit" value="Send">
    </div>

  </form>

</div>
