app.factory('petsService', function($http) {
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