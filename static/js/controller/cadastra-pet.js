app.controller('CadastraPetController', function($scope, $location, petsService)  {
    $scope.dataCadastraPet = {pet: {especie: '1'}};
    
    $scope.cadastraPet = function() {
        $scope.dataMain.loading = true;
        petsService.cadastraPet($scope.dataCadastraPet.pet).then(function(data) {
            if(data.resposta != "sucesso") {
                alert("Erro ao receber dados do servidor");
            }else{
                $location.url('/home');
                $scope.dataMain.loading = false;
            }
        });
    };
});