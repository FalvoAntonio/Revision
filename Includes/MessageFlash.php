<?php
// MESSAGES + ERREURS D'AFFICHAGE
if (session_status() !== PHP_SESSION_ACTIVE)
// On vérifie si la session n'est pas déjà démarrée
// Si la session n'est pas démarrée, on la démarre
{
  return; // Si pas de session, on sort 
}
/* 
Création de la function "afficheMessageFlash" qui me permet d'afficher les messages flash lorsque je fais la demande pour 
réinitialiser mon mot de passe par exemple.

? NE PAS OUBLIER QUE J'AI FAIT UNE FONCTION JS "Flash-Message.js" POUR POUVOIR ENLEVER LE MESSAGE QUAND JE CLIQUE SUR LE X
*/
function afficheMessageFlash(string $titreMessage)
{
  $message = $_SESSION[$titreMessage] ?? "";
  // L’opérateur ?? (null coalescent) retourne une chaîne vide si la variable n’existe pas.
  // Exemple : $_SESSION["Message-confirmation-envoi-mail"]  -> $_SESSION["Message-confirmation-envoi-mail"]
  if (!empty($message))
  // Si le message n'est pas vide, on l'affiche dans la div
  {
  //  Affiche le HTML du message avec la croix de fermeture
    echo "<div class='flash-message'>
        <span class='close-msg-flash'>X</span>$message</div>";
    // Ici avec l'unset on supprime le message de la session pour qu'il ne s'affiche qu'une seule fois, si on refresh il ne s'affichera plus 
    unset($_SESSION[$titreMessage]);
  }
}

function MessagesErrorsFormulaire($champ)
{
  static $error = [];
  // Initialise un tableau vide local appelé $error, que je vais utiliser pour stocker les erreurs.
  if (isset($_SESSION["error"])) // Récupère le tableau complet
  // Vérifie si des erreurs ont été stockées dans la session
  {
    $error = $_SESSION["error"]; // On récupère les erreurs stocké "HomeController.php"
    // Si c’est le cas, tu les récupères dans ta variable locale $error.
    unset($_SESSION["error"]);
    // Et je les supprimes de la session pour éviter de les réafficher à chaque rechargement de la page.

    // + Pourquoi faire ça ? : Pour transférer les erreurs d’une page de traitement vers une page d’affichage (comme un formulaire).
    // + On prend les erreurs de la page "Formulaire-creation-compte.php"

  }
  /* 
    ! Pourquoi utiliser "global" ? Parce que $error est une variable déclarée en dehors de la fonction.
    ! En PHP, pour accéder à une variable globale depuis l’intérieur d’une fonction, tu dois utiliser global.

  On vérifie si une erreur existe pour la clé donnée (par exemple : "mail" etc.)
  Si oui, on affiche un span HTML contenant le message d'erreur
  Sinon, on n'affiche rien (chaîne vide)
  */
  // Par exemple: Cherche si la clé "mail" existe dans le tableau
  // Affiche le message !!!
  echo isset($error[$champ]) ? "<span class='error'>{$error[$champ]}</span>" : "";
  // var_dump($error);
}
