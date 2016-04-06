app.controller('HomeController', function($scope, $location)  {
        if(!$scope.isLoged()){
            $location.path('login');
        }
        
        $scope.logout = function() {
            $scope.delJWT();
            $location.path('login');
        }
    });