"use strict";
document.querySelector("#form-suppression")?.addEventListener("submit", function(event) {
    if (!confirmerSuppression()) {
        event.preventDefault(); // Empêche la soumission du formulaire si l'utilisateur annule
        // Stop n'envoie pas le formulaire, sans cette ligne le formulaire s'envoie et le compte est supprimé
    }
});
    // Function de confirmation avant suppression du compte
    function confirmerSuppression() {
        // confirm() affiche une popup avec OK et Annuler
        // Retourne true si l'utilisateur clique OK, false si Annuler
    
        return confirm("⚠️ ATTENTION ⚠️\n\nÊtes-vous absolument sûr(e) de vouloir supprimer votre compte ?\n\nCette action est IRRÉVERSIBLE et supprimera :\n- Vos informations personnelles\n- Vos achats de formations\n- Votre progression\n- Votre panier\n- Tous vos rendez-vous\n\nCliquez OK pour confirmer ou Annuler pour garder votre compte.");
    }

// Si l'utilisateur clique "OK" → retourne true (vrai)
// Si l'utilisateur clique "Annuler" → retourne false (faux)
// Le "!" devant "confirm()" inverse la valeur retournée
// Donc si l'utilisateur clique "OK" → !true devient false → on n'entre pas dans le if
// Si l'utilisateur clique "Annuler" → !false devient true → on entre dans le if et on empêche la soumission du formulaire
// 1. Clic sur "Supprimer mon compte"
// 2. Popup apparaît : "⚠️ ATTENTION ⚠️..."
// 3. L'utilisateur clique "OK"
// 4. confirm() retourne true
// 5. !true devient false
// 6. On N'entre PAS dans le if
// 7. Pas de preventDefault()
// 8. Résultat : Formulaire envoyé, compte supprimé


    // ❌ AVANT                    // ✅ APRÈS
// confirm-supp-compte.js    →    confirmDeleteAccount.js
// Flash-Message.js          →    flashMessage.js  
// login.js                  →    loginForm.js (plus descriptif)
// Menu-Burger.js            →    menuBurger.js
// Menu-Burger-Utilisateur.js →   userMenu.js
// Modal-Panier.js           →    cartModal.js
// Modal-Recherche.js        →    searchModal.js
// prefix.js                 →    phonePrefix.js (plus clair)
// Prise-de-rdv.js          →    appointmentBooking.js
// reservation-de-rdv.js     →    appointmentForm.js
// script.js                 →    main.js (ou index.js)
// Slider.js                 →    imageSlider.js