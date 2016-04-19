app.controller('PesquisaPerdidosController', function($scope, $location)  {
    $scope.dataPesquisaPerdidos = {};
    $scope.dataPesquisaPerdidos.pets = [
        {nome: "polenta", especie: "Cachorro", dono: {nome: "usuario1", email: "usuario1@example.com"}, ultimaLocalizacao: "-20.3387652,-40.3355198", descricao: ""},
        {nome: "Pet1", especie: "Gato", dono: {nome: "usuario2", email: "usuario2@example.com"}, ultimaLocalizacao: "-20.3387652,-40.3355198", descricao: ""},
        {nome: "Pet2", especie: "Gato", dono: {nome: "usuario1", email: "usuario1@example.com"}, ultimaLocalizacao: "-20.3387652,-40.3355198", descricao: ""},
    ];
});