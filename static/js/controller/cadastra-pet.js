app.controller('CadastraPetController', function($scope, $location, petsService)  {
    $scope.dataCadastraPet = {pet: {especie: '1'}};
    
    $scope.cadastraPet = function() {
        var respostaCadastraPet = petsService.cadastraPet($scope.dataCadastraPet.pet);
        
        respostaCadastraPet.then(function(data) {
            if(data.resposta != "sucesso") {
                alert("Erro ao receber dados do servidor");
            }else{
                $location.path('/home');
            }
        });
    };
});