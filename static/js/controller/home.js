app.controller('HomeController', function($scope, $location, store, jwtHelper, petsService, petCadastroService)  {
    if(!$scope.isLoged()){
        $location.path('login');
        return;
    }
    
    $scope.dataHome = {};
    
    $scope.cadastraPet = function(petIndex) {
        petCadastroService.set($scope.dataHome.pets[petIndex]);
    };
    
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