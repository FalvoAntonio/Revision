"use strict";

import { polyfillCountryFlagEmojis } from "https://cdn.skypack.dev/country-flag-emoji-polyfill";
polyfillCountryFlagEmojis();


prefixtel();
// ! ICI : Il faut que le fichier JSON soit dans le même dossier que le fichier JS
// ! Grossomodo nous allons utiliser le fetch pour récupérer le contenu de "prefixtel.json" donc le fichier JSON, ce qui m'évitera de toujours copier/coller mon élément HTML
// ! Sur toutes mes pages HTML
 async function prefixtel()
{
    const select = document.querySelector("#prefix")
    if(!select)return;
    // Si tu trouves select, tu lances la fonction sinon tu t'arrêtes là, c'est à dire que tu ne fais rien
    // Sinon sans cette ligne  le code va faire planter les autres pages JS
    // const prefix = window.location.href.includes("HTML")? "../" : "./";
    const response = await fetch("/assets/JSON/prefixtel.json");
    // console.log(response);
    if(!response.ok)return;
    // ! On vérifie si la réponse est ok, c'est à dire que le fichier a bien été trouvé et qu'il n'y a pas eu d'erreur 404 ou autre
    // ! Si la réponse n'est pas ok, on ne fait rien et on sort de la fonction
    // ! On va donc récupérer le contenu du fichier JSON et le transformer en objet JS
    // ! On va utiliser la méthode json() de l'objet response pour récupérer le contenu du fichier JSON
    // await ne gère pas, le catch, donc on utilisera généralement un "try catch"
    try 
    {
        const data = await response.json();
        console.log(data); 
        createlist(data)       
    } catch (error) 
    {
        console.error(error);       

    }
}

/**
 * 
 * @param {Array} data 
 */

export function createlist(data){ 
// ! On va créer une liste déroulante avec les pays et leurs préfixes téléphoniques
// ! On va utiliser la méthode forEach pour parcourir le tableau de pays et créer un élément <option> pour chaque pays
    const select = document.querySelector("#prefix")
    // ! On va utiliser la méthode querySelector pour récupérer l'élément <select> dans le fichier HTML
    // ! On va utiliser la méthode createElement pour créer un élément <option> pour chaque pays
data.forEach(pays => {
    // ! On va créer un élément <option> pour chaque pays
    const option = document.createElement("option") 
    // ! On va utiliser la méthode createElement pour créer un élément <option> pour chaque pays
    // ! On va utiliser la méthode textContent pour ajouter le nom du pays et le préfixe téléphonique dans l'élément <option>
    select.append(option)
    option.textContent = pays.code + pays.dial_code
    option.value = pays.dial_code

})

}