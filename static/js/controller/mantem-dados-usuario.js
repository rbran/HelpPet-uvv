app.controller('MantemDadosUsuarioController', function($scope, $location, usuarioService)  {
    $scope.dataMantemDadosUsuario = {};
    
    usuarioService.getDados().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataMantemDadosUsuario.dados = data.usuario;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
    
    $scope.atualizaDadosUsuario = function() {
        usuarioService.atualizaDados($scope.dataMantemDadosUsuario.dados);
    };
});