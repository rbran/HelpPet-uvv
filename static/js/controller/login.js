app.controller('LoginController', 
 function($scope, $http, $location)  {
    $scope.usuario = {};
    if($scope.isLoged()){
        $location.path('/home');
    }
});