app.factory('manterPets', function($http) {
    var url = "/dynamic/pets.php";
    
    var getPetsPorDono = function(idDono) {
        var enviar = {consultarDono: idDono};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    }
    
    return {
        getPetsPorDono: getPetsPorDono
    };
});

app.controller('HomeController', function($scope, $location, store, jwtHelper, manterPets)  {
    if(!$scope.isLoged()){
        $location.path('login');
        return;
    }
    
    $scope.dataHome = {};
    
    var jwt = jwtHelper.decodeToken(store.get('jwt'));
    
    $scope.dataHome.usuario = jwt.data.usuario;
    
    var resposta = manterPets.getPetsPorDono(jwt.data.id);
    
    resposta.then(function(data) {
        if(data.resposta == "sucesso"){
            $scope.dataHome.pets = data.idDono;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});