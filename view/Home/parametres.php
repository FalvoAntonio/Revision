<?php 
// MÊME TECHNIQUE que dans vos autres pages
require_once APP_ROOT . '/../includes/MessageFlash.php';
afficheMessageFlash("modification-email"); // "success-email" = correspond au nom utilisé dans $_SESSION["success-email"]
afficheMessageFlash("modification-password");
?>
<section class="title-section">
    <h1>Paramètres de votre compte</h1>
    <p class="subtitle">Personnalisez vos préférences et gérez vos informations en toute sécurité</p>
</section>

 <div class="container-image-header">     
   <img src="/assets/images/Page Mon Espace/parametres-header.jpg" alt="photo paramètres">
</div>


<h2 class="h2-styled ">Paramètres du compte</h2>

<div class="container settings-container">
    <h1>Mes modifications</h1>

    <!-- Modifier l'e-mail -->
    <section>
      <h2>Modifier l'adresse e-mail</h2>
      <form method="post" action="/modifier-email">
         <!-- SÉCURITÉ : même token CSRF que dans mes autres formulaires (fichier:FormSecurity.php-->
        <input type="hidden" name="csrf_token" value="<?= creationCSRFToken() ?>">
        <label for="email">Nouvel e-mail</label>
        <input type="email" id="email" name="email" placeholder="nouveau@mail.com" required>
          <!-- name="email" pour récupérer la valeur dans le $_POST -->
          <!-- required pour que le champ soit obligatoire -->
          <!-- Affichage des messages d'erreur spécifiques au champ email, même système que pour l'inscription -->
           <!-- fichier:MessageFlash.php -->
        <?php MessagesErrorsFormulaire("email"); ?>
        <button type="submit">Mettre à jour l'e-mail</button>
    </form>
    </section>

    <!-- Modifier le mot de passe -->
    <section>
      <h2>Changer le mot de passe</h2>
      <form method="post" action="/modifier-mot-de-passe">
       
        <input type="hidden" name="csrf_token" value="<?= creationCSRFToken() ?>">
        
        <label for="current-password">Mot de passe actuel</label>
        <input type="password" id="current-password" name="current-password" required>
    
        <?php MessagesErrorsFormulaire("current-password"); ?>

        <label for="new-password">Nouveau mot de passe</label>
        <input type="password" id="new-password" name="new-password" required>
    
        <?php MessagesErrorsFormulaire("new-password"); ?>
        
        <button type="submit">Mettre à jour le mot de passe</button>
    </form>
      <!-- <label for="current-password">Mot de passe actuel</label>
      <input type="password" id="current-password">

      <label for="new-password">Nouveau mot de passe</label>
      <input type="password" id="new-password">
      <button>Mettre à jour le mot de passe</button> -->
    </section>

    <!-- Notifications -->
    <section>
      <h2>Notifications</h2>
      <label for="notifications">Préférences</label>
      <select id="notifications">
        <option>Recevoir toutes les notifications</option>
        <option>Seulement les notifications importantes</option>
        <option>Ne rien recevoir</option>
      </select>
      <button>Enregistrer les préférences</button>
    </section>

    <!-- Suppression du compte -->
    <section>
      <h2>Supprimer mon compte</h2>
      <p><strong>Attention :</strong> cette action est irréversible. Toutes vos données seront supprimées définitivement.</p>
      <form id="form-suppression" method="post" action="/supprimer-compte">
        <!-- onsubmit="return confirmerSuppressionAvancee()" J'utilise cette méthode, sinon j'aurais du faire le addeventListener dans ma
         fonction JS "confirm-supp-compte.js" -->
        <input type="hidden" name="csrf_token" value="<?= creationCSRFToken() ?>"> 
        <!-- Création d'un token CSRF avec la fonction "creationCSRFToken()" qui crée un token unique pour chaque session 
         et le stocke dans un champ caché et le copie dans le formulaire -->
         <!-- Champ caché contenant le token CSRF -->
         <!-- Utilisation de la fonction "creationCSRFToken()" pour comparer le token-->
        <button class="danger" type="submit">Supprimer mon compte</button>
      </form>
    </section>
  </div>
