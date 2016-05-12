var app = angular.module('helppet', ['ngRoute', 'angular-storage', 'angular-jwt', 'ngMap']);

app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
         templateUrl: 'views/inicio.html',
//         controller: 'HomeController'
    }).when('/home', {
         templateUrl: 'views/home.html',
         controller: 'HomeController'
    }).when('/faq', {
        templateUrl: 'views/faq.html',
//        controller: 'CadastraPetController'
    }).when('/login', {
        templateUrl: 'views/login.html',
        controller: 'LoginController'
    }).when('/cadastra-pet', {
        templateUrl: 'views/cadastra-pet.html',
        controller: 'CadastraPetController'
    }).when('/pesquisa-perdidos', {
        templateUrl: 'views/pesquisa-perdidos.html',
        controller: 'PesquisaPerdidosController'
    }).when('/pesquisa-adocao', {
        templateUrl: 'views/pesquisa-adocao.html',
        controller: 'PesquisaAdocaoController'
    }).when('/mantem-dados-usuario', {
        templateUrl: 'views/mantem-dados-usuario.html',
        controller: 'MantemDadosUsuarioController'
    }).when('/cadastra-adocao', {
        templateUrl: 'views/cadastra-adocao.html',
        controller: 'CadastraAdocaoController'
    }).when('/cadastra-perdido', {
        templateUrl: 'views/cadastra-perdido.html',
        controller: 'CadastraPerdidoController'
    }).otherwise({
        redirectTo: '/'
    });
}]);

app.config(function Config($httpProvider, jwtInterceptorProvider) {
    jwtInterceptorProvider.tokenGetter = ['store', function(store) {
        return store.get('jwt');
    }];

    $httpProvider.interceptors.push('jwtInterceptor');
})

app.controller("MainController", function($scope, $location, store, jwtHelper, LoginService, especieService) {
    $scope.usuario = null;
    $scope.dataMain = {especies: null};
    
    $scope.isLoged = function() {
        return $scope.usuario != null;
    };
    
    $scope.telaAtiva = function (viewLocation) { 
        return viewLocation === $location.path();
    };
    
    $scope.logout = function() {
        $scope.usuario = null;
        store.remove('jwt');
        $location.path('/');
    }

    $scope.login = function(usuario) {
        var resposta = LoginService.login(usuario.login, usuario.senha);
            resposta.then(function(data) {
            if(data.resposta == "sucesso"){
                $scope.usuario = usuario;
                store.set('jwt', data.jwt);
                $location.path('/home');
            }else{
                alert("Erro no login");
            }
        });
    };
    
    var jwt = store.get('jwt');
    if(store.get('jwt') == null){
        $scope.usuario = null;
        $location.path('/');
    }else if(jwtHelper.isTokenExpired(jwt)) {
        $scope.usuario = null;
        store.remove('jwt');
        $location.path('/login');
    }else{
        $scope.usuario = jwt.data;
    }
    
    especieService.getEspecies().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataMain.especies = data.consulta;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});