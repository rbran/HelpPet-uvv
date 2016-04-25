app.controller('PesquisaAdocaoController', function($scope, $location, petsService)  {
    $scope.dataPesquisaAdocao = {};
    $scope.dataPesquisaAdocao.pets = [];
    
    
    
    var respostaPets = petsService.getPetsAdocao();
    
    respostaPets.then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataPesquisaAdocao.pets = data.consultarAdocao;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});