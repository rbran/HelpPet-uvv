app.factory('especieService', function($http) {
    var url = "/dynamic/especies.php";
    
    var getEspecies = function(idDono) {
        return $http.get(url).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    }
    
    return {
        getEspecies: getEspecies
    };
});