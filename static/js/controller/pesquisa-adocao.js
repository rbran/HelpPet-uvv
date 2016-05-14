app.controller('PesquisaAdocaoController', function($scope, $location, petsService)  {
    $scope.dataPesquisaAdocao = {};
    $scope.dataPesquisaAdocao.pets = [];
    $scope.dataPesquisaAdocao.petDetalhe = null;
    
    $scope.mostraDetalhe = function(index) {
        $scope.dataPesquisaAdocao.petDetalhe = $scope.dataPesquisaAdocao.pets[index];
    }
    
    $scope.dataMain.loading = true;
    petsService.getPetsAdocao().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataPesquisaAdocao.pets = data.consultarAdocao;
            $scope.dataMain.loading = false;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});