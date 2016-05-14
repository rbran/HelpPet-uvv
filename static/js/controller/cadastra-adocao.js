app.controller('CadastraAdocaoController', function($scope, $location, $routeParams, petsService, petCadastroService)  {
    $scope.dataCadastraAdocao = {pet: petCadastroService.get()};
    $scope.dataMain.loading = true;
    if($scope.dataCadastraAdocao.pet == null) {
        petsService.getPet(parseInt($routeParams['id'])).then(function(data) {
            if(data.resposta == "sucesso") {
                $scope.dataCadastraAdocao.pet = data.consultar;
                $scope.dataMain.loading = false;
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    }else{
        $scope.dataMain.loading = false;
    }
    
    $scope.cadastraAdocao = function() {
        $scope.dataMain.loading = true;
        if($scope.dataCadastraAdocao.pet.adocao == null)
            $scope.dataCadastraAdocao.pet.adocao = {observacao: ""};
        petsService.atualizaPet($scope.dataCadastraAdocao.pet).then(function(data) {
            if(data.resposta == "sucesso") {
                $location.url('/home');
                $scope.dataMain.loading = false;
            }else{
                alert("Erro ao receber dados do servidor");
            }
        });
    };
    
});