app.controller('PesquisaPerdidosController', function($scope, $location, petsService)  {
    $scope.dataPesquisaPerdidos = {};
    $scope.dataPesquisaPerdidos.pets = [];
    
    var respostaPets = petsService.getPetsPerdidos();
    
    respostaPets.then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataPesquisaPerdidos.pets = data.consultarPerdido;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
    
});