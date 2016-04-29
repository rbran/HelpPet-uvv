app.factory('usuarioService', function($http) {
    var url = "/dynamic/usuario.php";
    
    var getDados = function() {
        var enviar = {retornaDados: true};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    var atualizaDados = function(dadosUsuario) {
        var enviar = {atualizaDados: dadosUsuario};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    return {
        getDados: getDados,
        atualizaDados: atualizaDados
    };
});