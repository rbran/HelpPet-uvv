var app = angular.module('helppet', ['ngRoute', 'angular-storage']);

app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
         templateUrl: 'views/home.html',
         controller: 'HomeController'
    }).when('/login', {
        templateUrl: 'views/login.html',
        controller: 'LoginController'
    }).otherwise({
        redirectTo: '/'
    });
}]);


app.controller("MainController", function($scope, store) {
        $scope.mainData = {
            jwt: null
        };
        
        $scope.storeJWT = function(jwt) {
            store.set('jwt', jwt);
            $scope.mainData.jwt = jwt;
        };
        
        $scope.isLoged = function() {
            if($scope.mainData.jwt == null) {
                return false;
            } else {
                return true;
            }
        };
        
        $scope.getJWT = function() {
            return $scope.mainData.jwt;
        }
        
        $scope.mainData.jwt = store.get('jwt');
    });