app.controller('HomeController', function($scope, $location, store, jwtHelper, petsService, petCadastroService)  {
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
        var deletadoId = $scope.dataHome.pets[index].id;
        $scope.dataHome.pets.splice(index, 1);
        petsService.deletaPet(deletadoId);
    };
    
    $scope.removerAdocao = function(index) {
        $scope.dataHome.pets[index].adocao = null;
        petsService.atualizaPet($scope.dataHome.pets[index]);
    };
    
    $scope.removerPerdido = function(index) {
        $scope.dataHome.pets[index].perdido = null;
        petsService.atualizaPet($scope.dataHome.pets[index]);
    };
    
    var jwt = jwtHelper.decodeToken(store.get('jwt'));
    
    $scope.dataHome.usuario = jwt.data.usuario;
    
    var respostaPets = petsService.getPetsPorDono(jwt.data.id);
    
    respostaPets.then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataHome.pets = data.consultarDono;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
    
});