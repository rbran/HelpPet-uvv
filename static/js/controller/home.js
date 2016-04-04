app.controller('HomeController', function($scope, $location)  {
        if(!$scope.isLoged()){
            $location.path('login');
        }
    });