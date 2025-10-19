"use strict";

// ! En faisant la méthode modules je vais devoir importer mes fichiers JS dans le fichier script.js
// ! Ce qui me permettra de ne pas avoir de code dupliqué sur mes pages HTML, ça me simplifie les choses car je n'ai plus qu'un seul fichier JS à gérer 
// ! Sur chaque page HTML je vais juste devoir importer le fichier script.js et il va tout gérer

import { createlist } from "./phonePrefix.js"; // J'importe la fonction pour créer la liste des préfixes téléphoniques
import { startMenuBurger } from "./menuBurger.js";  
import { ValidationDesChamps, MDPvisible } from "./loginForm.js";
import { SliderImage } from "./imageSlider.js";
import { StartMenuUtilisateur } from "./userMenu.js"
import { CloseMsgFlash } from "./flashMessage.js";
import "./appointmentBooking.js"    // Je n'ai pas besoin d'importer une fonction car le simple fait d'importer le fichier va lancer le code
import "./appointmentForm.js" // Je n'ai pas besoin d'importer une fonction car le simple fait d'importer le fichier va lancer le code
import "./confirmDeleteAccount.js" // Je n'ai pas besoin d'importer une fonction car le simple fait d'importer le fichier va lancer le code
import  "./searchModal.js";
import "./cartModal.js"

// createlist() // J'appel la fonction pour créer la liste des préfixes téléphoniques (dans le fichier login.php et inscription.php)

StartMenuUtilisateur() // J'appel la fonction pour le menu utilisateur (dans le header.php avec le fetch)
CloseMsgFlash() // J'appel la fonction pour fermer le message flash (dans le fichier MessageFlash.php)

const images = ["OPX05236.jpg", "OPX05238.jpg", "OPX05253.jpg", "OPX05257.jpg", "OPX05267.jpg", "OPX05269.jpg", "IMG_3863.jpeg", "IMG_3865.jpeg"]
/* J'insère directement mon tableau d'images, pour faciliter mon code, car si je veux
enlever une photo ou ajouer le code s'applique automatiquement */

const aboutMain = document.querySelector(".Slider-container"); // C'est pour éviter d'avoir le slider sur tous les body de mes autres pages HTML
if (aboutMain) // S'il trouve le main dans la page QuiSommesNous. Alors tu peux lancer :
{
    const AppelDuSlider = SliderImage(images, "/assets/Images/Photos QuiSommesNous/") // On appel la fonction de 
    // création de slider
    // ! Le premier paramètre pour le premier et le deuxièmee parametre pour le deuxième !
    // ! le deuxième paramètre "./Images/Photos QuiSommesNous/" nous permet d'avoir le chemin devant chaque images
    // ! Pour éviter de l'ecrire devant chaque image du tableau

    aboutMain.append(AppelDuSlider) // J'appel donc ma fonction pour faire apparaitre mon slider dans ma page
    // HTML QuiSommesNous
}


