"use strict";

let index = 0;
let nombreImages = 0;
// Déclare une variable globale 'index' initialisée à 0
// Cette variable garde en mémoire quelle image est actuellement affichée
// Par défaut, c'est la première image (index 0)

// ? On Utilise cette fonction pour créer la partie HTML du container d'images, et lui permettre
// ? d'ajouter chaque photo que je vais ajouter dedans.
/**
 * Création de mes éléments HTML de mon slider
 * @param {Array} images //  Tableau contenant les noms des fichiers d'images
 * @param {string} cheminDossierImage // Chemin du dossier où se trouvent les images
 * @returns {HTMLElement} // Retourne l'élément HTML complet du slider
 */
export function SliderImage(images, cheminDossierImage){
    nombreImages = images.length
    // Elle prend en paramètres un tableau d'images et le chemin du dossier

    const sliderContainer = document.createElement("div"); 
    // Crée une div qui servira de conteneur principal pour notre slider
    sliderContainer.classList.add("slider-container");
    // Ajoute une classe CSS à notre conteneur

    images.forEach((ChaqueImage,indextableau) => {
    // Parcourt chaque nom d'image dans le tableau fourni
    // chaqueImage = le nom du fichier (ex: "IMG_3863.jpeg")
    // indexTableau = la position dans le tableau (0, 1, 2...)
        const img = document.createElement("img"); 
        img.src = cheminDossierImage + ChaqueImage;  
        // Définit le chemin de l'image en combinant le nom du fichier
        // Par exemple: "./Images/Photos QuiSommesNous/IMG_3863.jpeg"
        img.alt = "Image de diaporama";
        img.classList.add("slide");  // Ajoute une classe CSS "slide" à l’image
        
        if (indextableau !== index) { // ? Si l'image n'est pas la première (index=0), alors elle est cachée (display:none)
        // ? Si l'image n'est pas celle qui doit être affichée (index actuel)
        //  ? Au démarrage, seule la première image (index 0) sera visible
            img.style.display = "none";

        }
        sliderContainer.appendChild(img);  
        // ? Ajoute l'image au conteneur du slider
        // ? L'image devient un "enfant" du conteneur dans la structure HTML
    });
    CreationDeFleches(sliderContainer)
    // Appelle la fonction pour créer les flèches de navigation et les ajouter au conteneur
    return sliderContainer; 
   // Retourne le conteneur complet avec toutes les images et les flèches
}

// ! Désormais je veux créer des flèches permettant de passer à l'image suivante ou précédente:

/**
 * Crée des flèches de navigation pour le slider
 * @param {HTMLElement} container - Conteneur principal où ajouter les flèches
 */

function CreationDeFleches(container)
{
    // Création d'un conteneur pour regrouper les flèches
    let FlechesContainer = document.createElement("div")
     // Ajout d'une classe CSS pour pouvoir styliser le conteneur des flèches
    FlechesContainer.classList.add("Fleches-container")

    // Création de la flèche gauche
    let FlecheGauche = document.createElement("img")
     // Définit le chemin vers l'image de la flèche gauche
    FlecheGauche.src = "/assets/Images/Fleche-gauche.png"
    // Ajoute un texte alternatif pour l'accessibilité
    FlecheGauche.alt = "Flèche Gauche"
    // Ajoute une classe CSS pour styliser la flèche gauche
    FlecheGauche.classList.add("fleche-gauche")
    // Ajoute la flèche gauche au conteneur de flèches
    FlechesContainer.append(FlecheGauche);

    // Création de la flèche droite 
    let FlecheDroite = document.createElement("img")
    // Définit le chemin vers l'image de la flèche droite
    FlecheDroite.src = "/assets/Images/Fleche-droite.png"
    // Ajoute un texte alternatif pour l'accessibilité
    FlecheDroite.alt = "Flèche Droite"
    // Ajoute une classe CSS pour styliser la flèche droite
    FlecheDroite.classList.add("right-arrow")
    // Ajoute la flèche droite au conteneur de flèches
    FlechesContainer.append(FlecheDroite);

    // Ajoute le conteneur de flèches au conteneur principal du slider
    container.append(FlechesContainer);


// Ajout d'un écouteur d'événement (détecteur de clic) sur la flèche gauche
FlecheGauche.addEventListener("click", function() {
     // Cette fonction s'exécute chaque fois que l'utilisateur clique sur la flèche gauche
    
    // ÉTAPE 1: Calcul du nouvel index (image précédente)
    // Pour aller à l'image précédente, on soustrait 1 de l'index actuel
    // Formule: (index - 1 + nombreImages) % nombreImages
    
    // Expliquons cette formule avec un exemple:
    // Supposons que nous avons 5 images (nombreImages = 5)
    // Les indices sont: 0, 1, 2, 3, 4
    
    // Cas normal - Si nous sommes à l'image 3, on veut aller à l'image 2:
    // (3 - 1 + 5) % 5 = 7 % 5 = 2 ✓
    
    // Cas spécial - Si nous sommes à l'image 0 (première), on veut aller à l'image 4 (dernière):
    // (0 - 1 + 5) % 5 = 4 % 5 = 4 ✓
    
    // Le "+ nombreImages" garantit que nous n'obtenons jamais un nombre négatif
    // Le "% nombreImages" (modulo) garantit que nous restons dans les limites du tableau

    let newIndex = (index - 1 + nombreImages) % nombreImages;

    // ÉTAPE 2: On appelle la fonction changeImage avec le nouvel index
    // Cette fonction va cacher l'image actuelle et afficher la nouvelle
    changeImage(newIndex);
});

// Ajout d'un écouteur d'événement (détecteur de clic) sur la flèche droite
FlecheDroite.addEventListener("click", function() {
   // Cette fonction s'exécute chaque fois que l'utilisateur clique sur la flèche droite
    
    // ÉTAPE 1: Calcul du nouvel index (image suivante)
    // Pour aller à l'image suivante, on ajoute 1 à l'index actuel
    // Formule: (index + 1) % nombreImages
    
    // Expliquons cette formule avec un exemple:
    // Supposons que nous avons 5 images (nombreImages = 5)
    // Les indices sont: 0, 1, 2, 3, 4
    
    // Cas normal - Si nous sommes à l'image 2, on veut aller à l'image 3:
    // (2 + 1) % 5 = 3 % 5 = 3 ✓
    
    // Cas spécial - Si nous sommes à l'image 4 (dernière), on veut aller à l'image 0 (première):
    // (4 + 1) % 5 = 5 % 5 = 0 ✓
    
    // Le "% nombreImages" (modulo) garantit que nous revenons à 0 après avoir atteint la dernière image
    
    let newIndex = (index + 1) % nombreImages;

    // ÉTAPE 2: On appelle la fonction changeImage avec le nouvel index
    // Cette fonction va cacher l'image actuelle et afficher la nouvelle
    changeImage(newIndex);
});

/*  
Explications supplémentaires:

L'opérateur modulo (%) est la clé pour comprendre ces fonctions:

L'opérateur modulo (%) donne le reste de la division entre deux nombres
Par exemple: 7 % 5 = 2 (car 7 divisé par 5 donne 1 avec un reste de 2)
C'est très utile pour créer des cycles ou des boucles dans un intervalle fixe


Pour la flèche gauche (précédent):

Le problème: si on est à l'index 0 et qu'on soustrait 1, on obtient -1, ce qui n'est pas un index valide
La solution: ajouter nombreImages avant de faire le modulo garantit que le résultat reste positif
Exemple avec 5 images:

À l'index 0: (0 - 1 + 5) % 5 = 4 (dernière image)
À l'index 2: (2 - 1 + 5) % 5 = 6 % 5 = 1 (image précédente)


Pour la flèche droite (suivant):

Le problème: si on est à la dernière image et qu'on ajoute 1, on dépasse le nombre d'images
La solution: utiliser le modulo pour "revenir à zéro" lorsqu'on dépasse le nombre d'images
Exemple avec 5 images:

À l'index 4: (4 + 1) % 5 = 0 (première image)
À l'index 2: (2 + 1) % 5 = 3 (image suivante)


La fonction changeImage:

Cette fonction fait le travail de changer l'affichage des images
Elle cache l'image actuelle (celle à l'index avant le clic)
Elle affiche la nouvelle image (celle à l'index après le clic)
Elle met à jour la variable globale 'index' pour garder trace de l'image actuellement affichée
*/

}
/**
 * Change l'image affichée dans le slider
 * @param {number} newIndex - Index de la nouvelle image à afficher
 */
function changeImage(newIndex){
    // ÉTAPE 1: Récupèrer toutes les images du slider
    // querySelector sélectionne tous les éléments avec la classe "slide"
    // ? la classe "slide" se trouve dans la function SliderImage
const ImageActuelle = document.querySelectorAll(".slide"); 
    // ! Je dois bien mettre querySelectorAll car c'est un tableau que je souhaite avoir ([index].style.display = "none")
    // ÉTAPE 2: Cache l'image actuellement visible
    // On utilise la variable globale 'index' pour savoir quelle image est actuellement affichée
    ImageActuelle[index].style.display = "none";
    
    // ÉTAPE 3: Met à jour la variable globale index avec le nouvel index
    // Ceci est important pour les prochains clics
    index = newIndex;
    
    // ÉTAPE 4: Afficher la nouvelle image
    // On change le style display de "none" à "block" pour rendre l'image visible
    ImageActuelle[index].style.display = "block";


/* Pourquoi avons-nous besoin de newIndex ?

Deux variables différentes :

index : C'est une variable globale qui garde en mémoire l'index de l'image actuellement affichée dans le slider
newIndex : C'est un paramètre local à la fonction changeImage qui indique quel sera le nouvel index après le changement


Flux du processus :

Avant d'appeler changeImage : index contient l'indice de l'image actuellement visible
Lors de l'appel à changeImage(newIndex) : on indique quelle image on veut afficher maintenant
Dans la fonction : on cache d'abord l'image actuelle (index), puis on met à jour index = newIndex, et enfin on affiche la nouvelle image


Exemple concret :

Supposons que nous avons 5 images (indices 0, 1, 2, 3, 4)
Actuellement, l'image à l'indice 2 est affichée, donc index = 2
L'utilisateur clique sur la flèche droite pour passer à l'image suivante
La fonction du clic calcule newIndex = (2 + 1) % 5 = 3
Elle appelle ensuite changeImage(3)
Dans changeImage :

On cache l'image à l'indice 2 : slides[index].style.display = "none";
On met à jour index = 3
On affiche l'image à l'indice 3 : slides[index].style.display = "block";




Pourquoi nous ne pouvons pas simplement modifier index directement :

Nous avons besoin de connaître à la fois l'ancienne valeur de index (pour cacher l'image actuelle) et la nouvelle valeur
(pour afficher la nouvelle image)
Si nous modifions index directement avant d'appeler changeImage, nous perdrions l'information sur quelle image était affichée précédemment



Visualisation du processus
Prenons un exemple avec 5 images et voyons ce qui se passe quand on clique sur la flèche droite :
État initial :

index = 2 (l'image à l'indice 2 est visible)
Images : [img0, img1, img2, img3, img4]
États d'affichage : [none, none, block, none, none]

Après clic sur flèche droite :

On calcule newIndex = (2 + 1) % 5 = 3
On appelle changeImage(3)
Dans changeImage :

On cache slides[index] soit slides[2] → style.display = "none"
On met à jour index = 3
On affiche slides[index] soit slides[3] → style.display = "block"



Nouvel état :

index = 3 (l'image à l'indice 3 est maintenant visible)
Images : [img0, img1, img2, img3, img4]
États d'affichage : [none, none, none, block, none]

En termes simplifiés
Imaginez que vous lisez un livre et que vous utilisez un marque-page pour vous rappeler où vous en êtes :

index est comme votre marque-page actuel (page que vous êtes en train de lire)
newIndex est la page où vous voulez aller
changeImage est l'action de :

Fermer la page actuelle (cachée)
Déplacer votre marque-page à la nouvelle page
Ouvrir la nouvelle page (affichée)



Sans newIndex, ce serait comme si vous déplaciez votre marque-page avant de savoir quelle page vous étiez en train de lire,
ce qui vous ferait perdre votre position actuelle.
Est-ce que cette explication vous aide à mieux comprendre pourquoi nous avons besoin de newIndex dans la fonction changeImage ? */
}
