app.controller('CadastraPetController', function($scope, $location, petsService)  {
    $scope.dataCadastraPet = {pet: {especie: String($scope.dataHome.especies[0].id)}};
    
    $scope.cadastraPet = function() {
        var respostaCadastraPet = petsService.cadastraPet($scope.dataCadastraPet.pet);
        
        respostaCadastraPet.then(function(data) {
            if(data.resposta != "sucesso") {
                alert("Erro ao receber dados do servidor");
            }
        });
    };
});