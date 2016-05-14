app.factory('especieService', function($http) {
    var url = "/dynamic/especies.php";
    var promise = null;

    var getEspecies = function() {
        if (!promise) {
            promise = $http.get(url).then(function(response) {
                return response.data;
            });
        }
        return promise;
    };

    var resolveEspeciesNome = function(idEspecie, especies) {
        if(especies != null)
            for (var i = 0; i < especies.length; i++) {
                if (especies[i].id == idEspecie) {
                    return especies[i].nome;
                }
            }
        return null;
    };

    return {
        getEspecies: getEspecies,
        resolveEspeciesNome: resolveEspeciesNome
    };
});