app.factory('petsService', function($http) {
    var url = "/dynamic/pets.php";
    
    var getPet = function(id) {
        var enviar = {consultar: id};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    var getPetsPorDono = function(idDono) {
        var enviar = {consultarDono: idDono};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    
    var getPetsPerdidos = function() {
        var enviar = {consultarPerdido: true};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    var getPetsAdocao = function() {
        var enviar = {consultarAdocao: true};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    var cadastraPet = function(pet) {
        var enviar = {cadastra: pet};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    var atualizaPet = function(pet) {
        var enviar = {atualiza: pet};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    };
    
    return {
        getPet: getPet,
        getPetsPorDono: getPetsPorDono,
        getPetsPerdidos: getPetsPerdidos,
        getPetsAdocao: getPetsAdocao,
        cadastraPet: cadastraPet,
        atualizaPet: atualizaPet
    };
});

app.factory('petCadastroService', function() {
    var petSalvo = null;
    function set(petSalvar) {
        petSalvo = petSalvar;
    }

    function get() {
        var ret = petSalvo;
        petSalvo = null
        return ret;
    }

    return {
        set: set,
        get: get
    }
            
});