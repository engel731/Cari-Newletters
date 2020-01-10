(function() {
    var searchElement = document.getElementById('search'),
        
        results = document.getElementById('results'),
        resultsId = document.getElementById('results-id'),
        resultsQuartier = document.getElementById('results-quartier'),
        resultsStreet = document.getElementById('results-street'),
        
        fieldId = document.getElementById('id'),
        fieldQuartier = document.getElementById('quartier'),
		fieldStreet = document.getElementById('street'),
        
        selectedResult = -1, // Permet de savoir quel résultat est sélectionné : -1 signifie "aucune sélection"
	    previousRequest, // On stocke notre précédente requête dans cette variable
    	previousValue = searchElement.value; // On fait de même avec la précédente valeur
	
	function getResults(keywords) { 
	    var xhr = new XMLHttpRequest();
	    xhr.open('GET', '/wp-json/cari/v1/street/'+ encodeURI(keywords));
	
    	xhr.addEventListener('readystatechange', function() {
        	if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                var responseJson = JSON.parse(xhr.responseText);
                displayResults(responseJson);
        	}
	    });
	
	    xhr.send(null);
	
	    return xhr;
	}

	function displayResults(response) { // Affiche les résultats d'une requête
        var responseLen = response.length;
        
        results.style.display = responseLen ? 'block' : 'none'; // On cache le conteneur si on n'a pas de résultats
	
        if (responseLen) { // On ne modifie les résultats que si on en a obtenu
            
            results.innerHTML = ''; // On vide les résultats
            resultsId.innerHTML = '';
            resultsQuartier.innerHTML = '';
            resultsStreet.innerHTML = '';
	
	        for (let i = 0, div = [], sugg; i < responseLen ; i++) {
            	sugg = results.appendChild(document.createElement('div'));
                sugg.innerHTML = response[i]['intitule_voie'] + ' (' + response[i]['quartier'] + ')';
                
                div[0] = resultsId.appendChild(document.createElement('div'));
                div[0].innerHTML = response[i]['id'];
                
                div[1] = resultsQuartier.appendChild(document.createElement('div'));
                div[1].innerHTML = response[i]['quartier'];
                
                div[2] = resultsStreet.appendChild(document.createElement('div'));
            	div[2].innerHTML = response[i]['intitule_voie'];
	
            	sugg.addEventListener('click', function(e) {
					chooseResult(e.target, response[i]['id'], response[i]['quartier'], response[i]['intitule_voie']);
	            });
	        }
	    }
	}
	
	function chooseResult(result, $id, $quartier, $street) { // Choisi un des résultats d'une requête et gère tout ce qui y est attaché
        searchElement.value = previousValue = result.innerHTML; // On change le contenu du champ de recherche et on enregistre en tant que précédente valeur
        fieldId.value = $id;
        fieldQuartier.value = $quartier;
        fieldStreet.value = $street;
        
        results.style.display = 'none'; // On cache les résultats
	    result.className = ''; // On supprime l'effet de focus
	    selectedResult = -1; // On remet la sélection à "zéro"
	    searchElement.focus(); // Si le résultat a été choisi par le biais d'un clique alors le focus est perdu, donc on le réattribue
	}
	
	searchElement.addEventListener('keyup', function(e) {
        var divs = results.getElementsByTagName('div');
        
        if (e.keyCode == 38 && selectedResult > -1) { // Si la touche pressée est la flèche "haut"
            divs[selectedResult--].className = '';
	
	        if (selectedResult > -1) { // Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
	            divs[selectedResult].className = 'result_focus';
	        }
	    }
	
	    else if (e.keyCode == 40 && selectedResult < divs.length - 1) { // Si la touche pressée est la flèche "bas"
	
        	results.style.display = 'block'; // On affiche les résultats
	
	        if (selectedResult > -1) { // Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
            	divs[selectedResult].className = '';
	        }
	
        	divs[++selectedResult].className = 'result_focus';
	    }
	
	    else if (e.keyCode == 13 && selectedResult > -1) { // Si la touche pressée est "Entrée"
            var id = resultsId.getElementsByTagName('div')[selectedResult].innerHTML,
                quartier = resultsQuartier.getElementsByTagName('div')[selectedResult].innerHTML,
                street = resultsStreet.getElementsByTagName('div')[selectedResult].innerHTML;
            
            chooseResult(divs[selectedResult], id, quartier, street);
	    }
	
    	else if (searchElement.value != previousValue) { // Si le contenu du champ de recherche a changé
	
	        previousValue = searchElement.value;
	
	        if (previousRequest && previousRequest.readyState < XMLHttpRequest.DONE) {
	            previousRequest.abort(); // Si on a toujours une requête en cours, on l'arrête
        	}
	
	        previousRequest = getResults(previousValue); // On stocke la nouvelle requête
	
	        selectedResult = -1; // On remet la sélection à "zéro" à chaque caractère écrit
    	}
	});
})();