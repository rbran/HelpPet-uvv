app.controller('CadastraPerdidoController', function($scope, $location, $routeParams, petsService, petCadastroService)  {
    $scope.dataCadastraPerdido = {pet: petCadastroService.get()};
    if($scope.dataCadastraPerdido.pet == null) {
        petsService.getPet(parseInt($routeParams['id'])).then(function(data) {
            if(data.resposta == "sucesso") {
                $scope.dataCadastraPerdido.pet = data.consultar;
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    }
    
    $scope.cadastraPerdido = function() {
        petsService.atualizaPet($scope.dataCadastraPerdido.pet).then(function(data) {
            if(data.resposta == "sucesso") {
                $location.url('/');
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    };
    
});