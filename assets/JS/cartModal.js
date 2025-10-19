"use strict";

startModalPanier();
// Mon CSS fournit :
// .overlay-panier > visibility: hidden; : modale fermée par défaut
// .Modal-container > transform: translate(100%); Position fermée
// transition: 0.5s > Animation fluide


export function startModalPanier(){

    const modal = document.querySelector(".overlay-panier .Modal-container");
    // Sélectionne la modale
    const panier = document.querySelector(".icon-panier");
    // Sélectionne l'icône panier sur laquelle on clique
    const visibility = document.querySelector(".overlay-panier")
    // Le fond sombre derrière la modale 
    panier.addEventListener("click",clickPanier)
    // Quand quelqu'un clique sur l'icône panier, cela va exécuter la fonciton "clickPanier"
    
    function clickPanier(){
        console.log(modal);
        visibility.style.visibility = "visible"
        // Rend le fond sombre visible
        modal.style.transform = "translate(0)";
        // Fait glisser la modale de la droite vers le centre
        // Au début : transform = translate(100%) donc cachée à droite
        // Maintenant: transform = translate(0) donc visible au centre
    
    }
      
    const closeButton = document.querySelector(".overlay-panier .button-close");
    // C'est le bouton "X" dans la modale
    closeButton.addEventListener("click", closeModal);
    // Quand quelqu'un clique sur le bouton "X", la fonction closeModal est exécutée.
    
    function closeModal(){
        visibility.style.visibility = "hidden"
        // Cache le fond sombre
        modal.style.transform = "translate(100%)";
        // Fait glisser la modale vers la droite (hors de l'écran)
        // On retourne à la position cachée 
    
    }
}
