// On appelle la fonction pour activer l'écouteur d'événement au chargement du script
export function CloseMsgFlash()
{
// Récupération des éléments HTML (dans le fichier MessageFlash.php)
const Croix = document.getElementsByClassName("close-msg-flash")
const flashMessage = document.getElementsByClassName("flash-message")
// On vérifie si le premier élément "Croix" existe (Croix[0]) avec ?., ça évite une erreur si aucun élément trouvé
// Si Croix[0] n’existe pas (par exemple il n’y a pas de croix dans la page), alors il n’essaie pas de faire la suite, évitant une erreur.
Croix[0]?.addEventListener("click", function(){
    // La fonction anonyme "function" C'est ce qui se passe au moment du clic : Donc le fait de cacher le message
    flashMessage[0].style.display = "none"; // On cache le message 
})

}

