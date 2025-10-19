"use strict";

startModalRecherche() 
// Fonction pour afficher la modale de recherche

export function startModalRecherche() 
{
    const modalrecherche = document.querySelector(".Modal-Recherche");
    const recherche = document.querySelector(".icon-recherche");
    const visibilite = document.querySelector(".overlay-recherche")
    recherche.addEventListener("click",clickRecherche)  
    // Quand je clique sur l'icône de recherche, j'affiche la modale de recherche

    const FermerButton = document.querySelector(".Modal-Recherche .button-close");
    // const FermerButton = modalrecherche.querySelector(".button-close");
    FermerButton.addEventListener("click", closeModalRecherche);
    
    function clickRecherche(){
        visibilite.style.visibility = "visible"
        modalrecherche.style.transform = "translate(0)";
    
    }
    
    
     function closeModalRecherche(){
        visibilite.style.visibility = "hidden" // ! Je cache l'overlay
        modalrecherche.style.transform = "translate(100%)";
    
    }
    
}

