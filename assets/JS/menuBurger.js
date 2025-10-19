"use strict";
// ! J'ai utilisé la méthode fetch pour récupérer le contenu de l'HTML du header dans le fichier header.php
// ! Mais j'ai aussi utilisé la méthode fetch pour récupérer le contenu de la modale panier dans le fichier Modal-Panier.html avec les deux import
// ! Je récupère donc le contenu HTML pour éviter de le copier coller sur chaque page HTML que ja vais créer
// ! Donc juste avec le fichier script.js je vais pouvoir afficher le header avec le menu burger ainsi que les modales Recherche/Panier sur chaque page HTML


    startMenuBurger();


export function startMenuBurger(){
    const logomenuburger = document.querySelector(".logomenuburger")
 console.log(".logomenuburger span")




    logomenuburger.addEventListener("click", clickmenu)
    function clickmenu()
    {
        const span1 = document.querySelector(".span1")
        const span2 = document.querySelector(".span2")
        const span3 = document.querySelector(".span3")
        const menu = document.querySelector(".Menu-Titre ul")

        if(span1 && span2 && span3)

            // Je vérifie si j'ai mes span
        {
            if(span2.style.opacity != "0" ){ // Si le span2 n'est pas transparent
                span1.style.transform = "translateY(7px) rotate(45deg)";   // Je fais une rotation de 45° sur le span1 et -45° sur le span3
                span3.style.transform = "translateY(-7px) rotate(-45deg)" // Je fais une rotation de 45° sur le span1 et -45° sur le span3
                span2.style.opacity = "0" // Je le rend transparent
                menu.style.transform = "scale(1)" // Je fais apparaitre le menu

            }else{
                span1.style.transform = "none"; // Je remets les span dans leur position initiale
            span2.style.opacity = "1"; // Je remets le span2 visible
            span3.style.transform = "none";  // Je remets les span dans leur position initiale
             menu.style.transform = "scale(1, 0)" // Je fais disparaitre le menu

            }
          


        }
    }}

