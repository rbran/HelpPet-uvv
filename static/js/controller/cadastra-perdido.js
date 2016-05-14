app.controller('CadastraPerdidoController', function($scope, $location, $routeParams, NgMap, petsService, petCadastroService)  {
    $scope.dataCadastraPerdido = {pet: petCadastroService.get()};
    $scope.map = null;
    
    NgMap.getMap().then(function(map) {
        $scope.map = map;
    });
  
    $scope.dataMain.loading = true;
    $scope.dataCadastraPerdido.pet = petCadastroService.get();
    if($scope.dataCadastraPerdido.pet == null) {
        petsService.getPet(parseInt($routeParams['id'])).then(function(data) {
            if(data.resposta == "sucesso") {
                $scope.dataMain.loading = false;
                $scope.dataCadastraPerdido.pet = data.consultar;
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    }else{
        $scope.dataMain.loading = false;
    }
    
    $scope.cadastraPerdido = function() {
        $scope.dataMain.loading = true;
        if($scope.dataCadastraPerdido.pet.perdido == null)
            $scope.dataCadastraPerdido.pet.perdido = {}
        $scope.dataCadastraPerdido.pet.perdido.localizacao = {latitude: $scope.map.markers[0].position.lat(), longitude: $scope.map.markers[0].position.lng()};
        petsService.atualizaPet($scope.dataCadastraPerdido.pet).then(function(data) {
            if(data.resposta == "sucesso") {
                $location.url('/home');
                $scope.dataMain.loading = false;
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    };
    
});