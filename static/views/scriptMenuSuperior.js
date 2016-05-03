function menuCascata(){
	document.getElementById ("menuCascata").classList.toggle("show");
}
window.onclick = function(e){
	if (!e.target.matches('.drop')){
		var cascata = document.getElementsByClassName("conteudo-cascata");
		for (var d = 0; d < drop.length; d++){
			var abrirCascata = drop[d];
			if (abrirCascata.classList.contains('show')){
				abrirCascata.classList.remove('show');
			}
		}
	}
}