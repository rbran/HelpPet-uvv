app.controller('HomeController', function($scope, $location, store, jwtHelper, petsService)  {
    if(!$scope.isLoged()){
        $location.path('login');
        return;
    }
    
    $scope.dataHome = {};
    
    var jwt = jwtHelper.decodeToken(store.get('jwt'));
    
    $scope.dataHome.usuario = jwt.data.usuario;
    
    var respostaPets = petsService.getPetsPorDono(jwt.data.id);
    
    respostaPets.then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataHome.pets = data.consultarDono;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});