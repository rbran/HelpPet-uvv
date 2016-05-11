app.controller('CadastraPerdidoController', function($scope, $location, $routeParams, NgMap, petsService, petCadastroService)  {
    $scope.dataCadastraPerdido = {pet: {perdido: {}}};
    $scope.map = null;
    
    NgMap.getMap().then(function(map) {
        $scope.map = map;
    });
  
    $scope.dataCadastraPerdido.pet = petCadastroService.get();
    if($scope.dataCadastraPerdido.pet == null) {
        petsService.getPet(parseInt($routeParams['id'])).then(function(data) {
            if(data.resposta == "sucesso") {
                $scope.dataCadastraPerdido.pet = data.consultar;
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    }
    $scope.dataCadastraPerdido.pet.perdido = {};
    
    $scope.cadastraPerdido = function() {
        $scope.dataCadastraPerdido.pet.perdido.localizacao = {latitude: $scope.map.markers[0].position.lat(), longitude: $scope.map.markers[0].position.lng()};
        petsService.atualizaPet($scope.dataCadastraPerdido.pet).then(function(data) {
            if(data.resposta == "sucesso") {
                $location.path('/home');
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    };
    
});