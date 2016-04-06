app.controller('HomeController', function($scope, $location, store)  {
        if(!$scope.isLoged()){
            $location.path('login');
        }
        
        $scope.logout = function() {
            store.remove('jwt');
            $location.path('login');
        }
    });