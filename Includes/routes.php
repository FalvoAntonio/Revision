<?php
require_once APP_ROOT . '/../includes/router.php';

// === ACCUEIL === 
get('/accueil', 'HomeController@accueil'); // Accueil
get('/', 'HomeController@accueil'); // Accueil

// === ABOUT ===
get('/about', 'HomeController@about');  // Qui sommes nous ?

// === CONNEXION LOGIN ===
post('/verification-de-formulaire-connexion', 'AuthController@traitementConnexion'); // Traitement du formulaire de connexion
get('/connexion', 'AuthController@connexion'); // Page de connexion
get('/deconnexion', 'AuthController@deconnexion'); // Déconnexion de l'utilisateur

// === CREATION COMPTE ===
get("/inscription", 'UserController@inscription'); // Page d'inscription
post("/verification-de-formulaire-inscription", 'UserController@traitementInscription'); // Traitement du formulaire d'inscription
get("/confirmer-votre-mail", 'UserController@confirmerEmail'); 

// === MOT DE PASSE OUBLIE ===
get("/mot-de-passe-oublie", 'AuthController@motDePasseOublie'); // Page mot de passe oublié
get("/reinitialiser-mot-de-passe", 'AuthController@reinitialiserMotDePasse'); // Page réinitialiser mot de passe
post("/verification-de-confirmation-mail", 'AuthController@verificationConfirmationMail'); // Vérification de l'e-mail pour réinitialiser le mot de passe
post("/verification-de-formulaire-reinitialiser-motdepasse", 'AuthController@traitementReinitialisationMotDePasse'); // Traitement du formulaire de réinitialisation du mot de passe

// === MON ESPACE ===
get("/mon-espace", 'HomeController@monEspace'); // Page mon espace
get("/parametres", 'HomeController@parametres'); // Page paramètres du compte
post('/supprimer-compte', 'UserController@supprimerCompte'); // Suppression du compte
post('/modifier-email', 'ParametersController@modifierEmail'); // Modification email
post('/modifier-mot-de-passe', 'ParametersController@modifierMotDePasse'); // Modification mot de passe
get('/mes-rendez-vous', 'AppointmentsController@mesRendezVous'); // Mes rendez-vous
get('/mon-profil', 'HomeController@monProfil'); // Mon profil

// === CONTACT ===
get('/contact', 'HomeController@contact'); // Page contact

// === FORMATIONS ===
get('/liste-formations', 'TrainingController@listeFormations'); // Liste des formations
get('/formation/$slug', 'TrainingController@detailFormation'); // Détail d'une formation


// == PAGE SELECTION DES SERVICES ==
get('/liste-services', 'AppointmentsController@listeServices');

// == FORMULAIRE DE RESERVATION ==
get('/reservation/$service_id', 'AppointmentsController@bookingForm');
post('/reservation', 'AppointmentsController@traitementBookingForm');

// == PAGE DE CONFIRMATION ==
// get('/reservation/confirmation', 'AppointmentsController@bookingConfirmation');

// == AJAX CRENEAUX ==
get('/api/timeslots', 'AppointmentsController@getTimeSlots');