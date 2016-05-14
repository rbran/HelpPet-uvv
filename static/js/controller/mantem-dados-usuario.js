app.controller('MantemDadosUsuarioController', function($scope, $location, NgMap, usuarioService)  {
    $scope.dataMantemDadosUsuario = {};
    $scope.map = null;
    
    NgMap.getMap().then(function(map) {
        $scope.map = map;
    });
    
    $scope.dataMain.loading = true;
    usuarioService.getDados().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataMantemDadosUsuario.dados = data.usuario;
            $scope.dataMain.loading = false;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
    
    $scope.atualizaDadosUsuario = function() {
        $scope.dataMain.loading = true;
        $scope.dataMantemDadosUsuario.dados.localizacao = {latitude: $scope.map.markers[0].position.lat(), longitude: $scope.map.markers[0].position.lng()};
        usuarioService.atualizaDados($scope.dataMantemDadosUsuario.dados).then(
            function sucesso(respostaServidor) {
                $scope.dataMain.loading = false;
            },
            function erro(respostaServidor) {
                alert("Não foi possivel atualizar os Dados do Usuario");
            });
        $location.url('/home');
    };
});