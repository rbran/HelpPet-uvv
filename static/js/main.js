var app = angular.module('helppet', ['ngRoute', 'angular-storage', 'angular-jwt', 'ngMap']);

app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
         templateUrl: 'views/inicio.html',
    }).when('/home', {
         templateUrl: 'views/home.html',
         controller: 'HomeController'
    }).when('/faq', {
        templateUrl: 'views/faq.html',
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
    $scope.isLoged = false;
    $scope.dataMain = {especies: null, loading: false, usuario: {}};
    
    $scope.telaAtiva = function (viewLocation) { 
        return viewLocation === $location.path();
    };
    
    $scope.logout = function() {
        $scope.isLoged = false;
        store.remove('jwt');
        $scope.dataMain.usuario = {};
        $location.path('/');
    }

    $scope.login = function(usuario) {
        $scope.dataMain.loading = true;
        var resposta = LoginService.login(usuario.login, usuario.senha);
            resposta.then(function(data) {
            if(data.resposta == "sucesso"){
                $scope.isLoged = true;
                store.set('jwt', data.jwt);
                $scope.dataMain.usuario = jwtHelper.decodeToken(data.jwt).data;
                $location.path('/home');
                $scope.dataMain.loading = false;
            }else{
                $scope.dataMain.loading = false;
                alert("Login Invalido");
            }
        });
    };
    
    var jwt = store.get('jwt');
    if(store.get('jwt') == null){
        $scope.isLoged = false;
        $scope.dataMain.usuario = {};
        $location.path('/');
    }else if(jwtHelper.isTokenExpired(jwt)) {
        $scope.isLoged = false;
        $scope.dataMain.usuario = {};
        store.remove('jwt');
        $location.path('/login');
    }else{
        $scope.dataMain.usuario = jwtHelper.decodeToken(jwt).data;
        $scope.isLoged = true;
    }
    
    
    
    $scope.resolveEspeciesNome = especieService.resolveEspeciesNome;
    
    especieService.getEspecies().then(function(data) {
        if(data.resposta == "sucesso") {
            $scope.dataMain.especies = data.consulta;
        }else{
            alert("Erro ao receber dados do servidor");
        }
    });
});