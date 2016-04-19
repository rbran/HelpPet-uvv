app.controller('PesquisaAdocaoController', function($scope, $location)  {
    $scope.dataPesquisaAdocao = {};
    $scope.dataPesquisaAdocao.pets = [
        {nome: "polenta", especie: "Cachorro", dono: {nome: "usuario1", email: "usuario1@example.com"}, descricao: ""},
        {nome: "Pet5", especie: "Gato", dono: {nome: "usuario2", email: "usuario2@example.com"}, descricao: ""},
        {nome: "Pet3", especie: "Cachorro", dono: {nome: "usuario1", email: "usuario1@example.com"}, descricao: ""},
        {nome: "Pet6", especie: "Gato", dono: {nome: "usuario1", email: "usuario1@example.com"}, descricao: ""},
    ];
});