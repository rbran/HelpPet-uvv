app.controller('LoginController', 
 function($scope, $http, $location)  {
    if($scope.isLoged()){
        $location.path('home');
    }
});