app.controller('MantemDadosUsuarioController', function($scope, $location, NgMap, usuarioService)  {
    $scope.dataMantemDadosUsuario = {};
    $scope.map = null;
    
    NgMap.getMap().then(function(map) {
        $scope.map = map;
    });
    
    usuarioService.getDados().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataMantemDadosUsuario.dados = data.usuario;
            $location.path('/home');
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
    
    $scope.atualizaDadosUsuario = function() {
        $scope.dataMantemDadosUsuario.dados.localizacao = {latitude: $scope.map.markers[0].position.lat(), longitude: $scope.map.markers[0].position.lng()};
        usuarioService.atualizaDados($scope.dataMantemDadosUsuario.dados);
    };
});