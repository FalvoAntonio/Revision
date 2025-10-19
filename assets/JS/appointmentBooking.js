"use strict";

// * Création de la fonction pour l'affichage du contenu de mes catégories, pour la prise de rendez-vous

//  La premire catégorie " Beauté des Mains" sera directement en mode "active" (visible) par défaut,
//  Et les autres catégories seront cachées

const categoryButtons = document.querySelectorAll(".category-btn"); // Boutons de catégories

const serviceCategories = document.querySelectorAll(".service-category"); // Sections de catégories de services


// * On execute la fonction pour activer les boutons de catégorie
ActiverLesBoutonsDeCategorie();

// * Fonction pour initialiser les événements de clic sur les boutons de catégorie
function ActiverLesBoutonsDeCategorie() {
  // Pour chaque bouton de catégorie, on ajoute un écouteur d'événement pour le clic
  categoryButtons.forEach(function (button) {
    button.addEventListener("click", ClicSurCategorie);
  });
}



// Fonction appelée quand on cique sur un bouton de catégorie
function ClicSurCategorie(event) {
  // event = paramètre quand tu cliques sur un élément. JS va crée un objet "event"
  // qui contient plein d'informations sur ce qui vient de se passer (le clic)

  //    ⚠️ Pourquoi currentTarget et pas juste target ?
  // event.target = l’élément réellement cliqué (même un span à l’intérieur d’un bouton).

  // event.currentTarget = l’élément sur lequel on a mis le .addEventListener().

  // 👉 Dans ton cas, tu veux manipuler le bouton lui-même, pas un élément HTML à l’intérieur du bouton. Donc on utilise currentTarget.

  // On recupère le bouton cliqué et l'ID de la catégorie associée
  // event.currentTarget = l'élément sur lequel l'événement a été déclenché (le bouton cliqué)
  const button = event.currentTarget; // Récupère le bouton cliqué
  const categoryId = button.getAttribute("data-category"); // Récupère l'ID de la catégorie depuis l'attribut data-category
  const serviceCategory = document.getElementById(categoryId); // Récupère la catégorie de service correspondante à l'ID

  // * Quand je clique ça me fait disparaitre toutes les catégories de services
  // On retire la classe "active" de tous les boutons de catégorie
  removeActiveClassFromAllButtons();
  // On cache toutes les catégories de services
  CacherToutesLesCategoriesServices();

  
  // On ajoute la classe "active" au bouton cliqué
  button.classList.add("active");

  // Si la section existe bien, on lui ajoute La classe "active" pour l'afficher
  if (serviceCategory) {
    serviceCategory.classList.add("active");
  }
}




// * Voici la fonction pour rétirer la classe "active" de tous les boutons de catégorie
// ? Une fois que l'on clique sur un bouton de catégorie, on retire la classe "active" de tous les boutons

function removeActiveClassFromAllButtons() {
  // Pour chaque bouton de catégorie, je retire la classe "active"
  categoryButtons.forEach(function (button) {
    button.classList.remove("active");
  });
}

// * Ma fonction pour cacher toutes les catégories de services

function CacherToutesLesCategoriesServices() {
  // ? Une fois que l'on clique sur un bouton de catégorie, on cache toutes les catégories de services
  // Pour chaque catégorie de service, je retire la classe "active"
  serviceCategories.forEach(function (ServicesCategory) {
    // Je retire la classe "active" de chaque catégorie de service
    ServicesCategory.classList.remove("active");
  });
}