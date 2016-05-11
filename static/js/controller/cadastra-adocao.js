app.controller('CadastraAdocaoController', function($scope, $location, $routeParams, petsService, petCadastroService)  {
    $scope.dataCadastraAdocao = {pet: petCadastroService.get()};
    if($scope.dataCadastraAdocao.pet == null) {
        petsService.getPet(parseInt($routeParams['id'])).then(function(data) {
            if(data.resposta == "sucesso") {
                $scope.dataCadastraAdocao.pet = data.consultar;
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    }
    
    $scope.cadastraAdocao = function() {
        petsService.atualizaPet($scope.dataCadastraAdocao.pet).then(function(data) {
            if(data.resposta == "sucesso") {
                $location.path('/home');
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    };
    
});