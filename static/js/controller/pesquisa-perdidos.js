app.controller('PesquisaPerdidosController', function($scope, $location, petsService)  {
    $scope.dataPesquisaPerdidos = {};
    $scope.dataPesquisaPerdidos.pets = [];
    $scope.dataPesquisaPerdidos.petDetalhe = null;
    
    $scope.mostraDetalhe = function(index) {
        $scope.dataPesquisaPerdidos.petDetalhe = $scope.dataPesquisaPerdidos.pets[index];
    }
    
    petsService.getPetsPerdidos().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataPesquisaPerdidos.pets = data.consultarPerdido;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});