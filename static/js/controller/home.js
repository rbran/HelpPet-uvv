app.controller('HomeController', function($scope, $location, store, petsService, petCadastroService)  {
    if(!$scope.isLoged) {
        $location.url('/login');
        return;
    }
    
    $scope.dataHome = {};
    
    $scope.cadastraPetAdocao = function(index) {
        petCadastroService.set($scope.dataHome.pets[index]);
        $location.path('/cadastra-adocao').search({id: $scope.dataHome.pets[index].id});
    };
    $scope.cadastraPetPerdido = function(index) {
        petCadastroService.set($scope.dataHome.pets[index]);
        $location.path('/cadastra-perdido').search({id: $scope.dataHome.pets[index].id});
    };
    
    $scope.deletaPet = function(index) {
        $scope.dataMain.loading = true;
        var deletadoId = $scope.dataHome.pets[index].id;
        petsService.deletaPet(deletadoId).then(
            function sucesso(respostaServidor) {
                $scope.dataHome.pets.splice(index, 1);
                $scope.dataMain.loading = false;
            },
            function erro(respostaServidor) {
                alert("Não foi possivel deletar o PET");
            });
    };
    
    $scope.removerAdocao = function(index) {
        $scope.dataMain.loading = true;
        $scope.dataHome.pets[index].adocao = null;
        petsService.atualizaPet($scope.dataHome.pets[index]).then(function(data) {
            if(data.resposta == "sucesso") {
                $scope.dataMain.loading = false;
            }else{
                alert("Erro atualizar PET");
            }
        });
    };
    
    $scope.removerPerdido = function(index) {
        $scope.dataMain.loading = true;
        var pet = $scope.dataHome.pets[index];
        pet.perdido = null;
        petsService.atualizaPet(pet).then(function(data) {
            if(data.resposta == "sucesso") {
                $scope.dataHome.pets[index].perdido = null;
                $scope.dataMain.loading = false;
            }else{
                alert("Erro atualizar PET");
            }
        });
    };
    
    var respostaPets = petsService.getPetsPorDono($scope.dataMain.usuario.id);
    
    $scope.dataMain.loading = true;
    respostaPets.then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataHome.pets = data.consultarDono;
            $scope.dataMain.loading = false;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
    
});