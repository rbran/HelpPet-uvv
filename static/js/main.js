var app = angular.module('helppet', ['ngRoute', 'angular-storage', 'angular-jwt', 'ngMap']);

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

app.controller("MainController", function($scope, $location, store, jwtHelper, LoginService, especieService) {
    $scope.usuario = {};
    $scope.dataMain = {especies: null};
    
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
        var resposta = LoginService.login(usuario.login, usuario.senha);
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
    
    especieService.getEspecies().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataMain.especies = data.consulta;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});