app.controller('PesquisaPerdidosController', function($scope, $location, petsService)  {
    $scope.dataPesquisaPerdidos = {};
    $scope.dataPesquisaPerdidos.pets = [];
    $scope.dataPesquisaPerdidos.petDetalhe = null;
    
    $scope.mostraDetalhe = function(index) {
        $scope.dataPesquisaPerdidos.petDetalhe = $scope.dataPesquisaPerdidos.pets[index];
    }
    
    $scope.dataMain.loading = true;
    petsService.getPetsPerdidos().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataPesquisaPerdidos.pets = data.consultarPerdido;
            $scope.dataMain.loading = false;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});