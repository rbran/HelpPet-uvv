var app = angular.module('helppet', ['ngRoute', 'angular-storage', 'angular-jwt']);

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

app.config(function Config($httpProvider, jwtInterceptorProvider) {
    jwtInterceptorProvider.tokenGetter = ['store', function(store) {
        return store.get('jwt');
    }];

    $httpProvider.interceptors.push('jwtInterceptor');
})

app.controller("MainController", function($scope, $location, store, jwtHelper) {
        $scope.isLoged = function() {
            return store.get('jwt') != null;
        };
        
        var jwt = store.get('jwt');
        if(store.get('jwt') == null){
            $location.path('login');
        }else if(jwtHelper.isTokenExpired(jwt)) {
            store.remove('jwt');
            $location.path('login');
        }
});