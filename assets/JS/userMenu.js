"use strict";

// Je vais chercher mon icône utilisateur dans mon fichier layout.php
const IconeUtilisateur = document.querySelector(".icon-login");
/* Je vais récupérer mon id "menu-utilisateur" dans mon fichier layout.php */
const MenuUtilisateur = document.querySelector("#menu-utilisateur");

// ? Fonction pour la visibilité du menu :
/**
 * 
 * @param {Event} event 
 */
function AfficherMenuUtilisateur(event)
{
        event.preventDefault();
        // preventDefault() = Empêche le comportement par défaut d'un élément
        // Ici le comportement par défaut d'un lien est de changer de page
        // En gros cette ligne nous dit " Non ne change pas de page reste ici"

    
        // event = C'est un paramètre qui contient les infos sur ce qui s'est passé au clic et au survol, il fait donc
        // référence au addEventListener

    const MenuVisible = MenuUtilisateur.style.transform === "scale(1)";
    // Le "===" est une comparaison stricte : 'est ce que c'est exactement égal ?
    // En résumé MenuVisible vaut true si le menu est visible sinon il vaut false

    if(!MenuVisible)
    {
        // si le menu N'EST PAS visible alors :
            MenuUtilisateur.style.transform = "scale(1)"; // On agrandit le menu
            MenuUtilisateur.style.opacity = "1"; // On le rend opaque
            MenuUtilisateur.style.visibility = "visible"; // On le rend visible
    }
    else
        // Sinon le menu est invisible
    {
            MenuUtilisateur.style.transform = "scale(0)"; // On rétrécit le menu
            MenuUtilisateur.style.opacity = "0"; // On le rend transparent
            MenuUtilisateur.style.visibility = "hidden"; // On le rend invisible
    }
    
}

// ? Fonction pour ouvrir le menu :

export function StartMenuUtilisateur()
{
   
    if(IconeUtilisateur.classList.contains("connected"))
        // est-ce que l'élément a la classe 'connected' ?
        // on n'active le menu QUE si l'utilisateur est connecté

        // j'ajoute les évènements au survol et au clique
    {
        const canHover = window.matchMedia('(hover: hover)').matches;
        // La constante canHover vaudra true si l'appareil supporte le hover (survol)
        // et false s'il ne le supporte pas (ex : smartphone)
        if (canHover) { IconeUtilisateur.addEventListener("mouseenter", AfficherMenuUtilisateur)
        // "mouseenter" = quand la souris "entre" sur l'élément (survol)
        // Même fonction appelée = le menu va s'ouvrir/fermer au survol
        MenuUtilisateur.addEventListener("mouseleave", AfficherMenuUtilisateur)
        // "mouseleave" = quand la souris "quitte" l'élément
        // Même fonction = le menu va s'ouvrir/fermer quand on quitte le menu
    }else
    {
             IconeUtilisateur.addEventListener("click", AfficherMenuUtilisateur)
        // Quand on clique sur l'icône, on exécute la fonction AfficherMenuUtilisateur"
        // Pour format mobile (sans hover)
       
    // Quand je click sur le logo il se passe quelque chose
    // Ici ça sera le fait d'ouvrir et de fermer le menu
    }
}
   
}
/* 
Pour conclure :
Mon script fait ceci :

Si l'utilisateur est connecté (classe "connected")
Au clic sur l'icône → ouvre/ferme le menu
Au survol de l'icône → ouvre/ferme le menu
Quand on quitte le menu → ouvre/ferme le menu

 */





