var app = angular.module('helppet', ['ngRoute', 'angular-storage', 'angular-jwt']);

app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
         templateUrl: 'views/home.html',
         controller: 'HomeController'
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

app.factory('LoginService', function($http) {
    var url = "/dynamic/login.php";
    
    var login = function(login, senha) {
        var enviar = {login: login, senha: senha};
        
        return $http.post(url, enviar).then(
            function sucesso(respostaServidor) {
                return respostaServidor.data;
            },
            function erro(respostaServidor) {
                return {resposta:"erro", mensagem: "Erro ao se comunicar com a servidor"};
            });
    }
    
    return {
        login: login
    };
});

app.controller("MainController", function($scope, $location, store, jwtHelper, LoginService) {
    $scope.usuario = {};
    
    $scope.isLoged = function() {
        return store.get('jwt') != null;
    };
    
    $scope.telaAtiva = function (viewLocation) { 
        return viewLocation === $location.path();
    };
    
    $scope.logout = function() {
        store.remove('jwt');
        $location.path('login');
    }

    $scope.login = function(usuario) {
        var resposta = LoginService.login($scope.usuario.login, $scope.usuario.senha);
            resposta.then(function(data) {
            if(data.resposta == "sucesso"){
                store.set('jwt', data.jwt);
                $location.path('home');
            }else{
                alert("Erro no login");
            }
        });
    };
    
    var jwt = store.get('jwt');
    if(store.get('jwt') == null){
        $location.path('login');
    }else if(jwtHelper.isTokenExpired(jwt)) {
        store.remove('jwt');
        $location.path('login');
    }
});