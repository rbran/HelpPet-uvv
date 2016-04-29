app.factory('especieService', function($http) {
    var url = "/dynamic/especies.php";
    var especies = null;
    
    var getEspecies = function() {
        return $http.get(url).then(
            function sucesso(respostaServidor) {
                especies = respostaServidor.data;
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    var resolveEspeciesNome = function(idEspecie) {
        if(especies != null)
            for(var i = 0; i < especies.length; i++) {
                if(especies[i].id == idEspecie) {
                    return especies[i];
                }
            }
        return null;
    };
    
    return {
        getEspecies: getEspecies,
        resolveEspeciesNome: resolveEspeciesNome
    };
});