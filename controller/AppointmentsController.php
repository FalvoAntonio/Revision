<?php

use App\Models\Appointments;
use App\Models\User;
use App\Models\Service;


require_once __DIR__."/../Includes/TimeSlotManagement.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
date_default_timezone_set('Europe/Paris');

class AppointmentsController{
    // L'orienté objet en PHP permet de structurer le code en regroupant des données et des comportements liés dans des classes.
    // La classe est une sorte de plan ou de modèle pour créer des objets spécifiques.
    // Une classe peut contenir des propriétés (variables) et des méthodes (fonctions)

    // == Affiche la liste des services ==
    public function listeServices()
    {
        $services = Service::getAllServices(); // Récupère tous les services
        $tabTitle = "Nos services - Nails Endless Beauty";
        $additionalCss = ["/assets/css/18-appointment-booking.css", "/assets/css/19-flash-message.css", "/assets/css/22-mails.css", "/assets/css/23-message-flash.css"];
        render('appointments.service-selection', compact('tabTitle', 'services', 'additionalCss'));
    }
   
    public function bookingForm($service_id){
        require_once APP_ROOT . '/../Includes/MessageFlash.php'; // Pour les messages flash
        require_once APP_ROOT . '/../Includes/FormSecurity.php'; // Pour les tokens CSRF
        // Vérifier que l'utilisateur est connecté 
        if (!isset($_SESSION["logged"]) || !isset($_SESSION["user_id"])) {
            header("Location: /connexion");
            exit;
        }




        $prestation = Service::getServiceById($service_id); // Récupère la prestation par son ID

        if(!$prestation) {
            // Si la prestation n'existe pas, rediriger vers la page des prestations
            header("Location: /liste-services");
            exit;
        }

        $user = User::getUserById($_SESSION["user_id"]); // Récupère les informations de l'utilisateur

        $nom_complet = $user["firstname"] . " " . $user["lastname"];
        $email_user = $user["email"];
        $telephone_user = $user["phone_prefix"] . $user["phone"];

        $tabTitle = "Réservation - Nails Endless Beauty";
        $additionalCss = ["/assets/css/21-appointment-confirm.css", "/assets/css/19-flash-message.css", "/assets/css/22-mails.css", "/assets/css/23-message-flash.css"];
        render('appointments.booking-form', compact('tabTitle', 'prestation', 'nom_complet', 'email_user', 'telephone_user', 'additionalCss'));
    }

    // == Traitement du formulaire de réservation ==
    public function traitementBookingForm()
    {
        require_once APP_ROOT . '/../includes/PHP_Mailer.php';
        // ? Liste des prothésistes disponibles
        $liste_prothesistes = ["Laura", "Cassandra", "Aléatoire"];

         // ? Variable pour stocker les messages d'erreur et de succès
        $error_message = "";
        $success_message = "";

         // ? Ici on va déclarer les variables pour stocker les données du formulaire
        // * Bien entendu on se fie à la table des "rendez-vous" de notre base de données (fichier "endless.sql")
        // * N'oublions pas que la notation $_POST en PHP est utilisée pour récupérer les données envoyées via la méthode POST.
        $service_id = $_POST["service_id"]; //  "service_id" est l'ID de la prestation sélectionnée
        $date_rdv = $_POST["date_rdv"]; //Date choisie pour le rendez-vous
        $heure_rdv = $_POST["heure_rdv"]; // Heure choisie pour le rendez-vous
        $notes = $_POST["notes"]; // Notes supplémentaires que l'utilisateur peut ajouter
        $prothesiste = $_POST["prothesiste"]; // Prothésiste sélectionné par l'utilisateur

        // * On vérifie que les données du formulaire sont présentes et valides
        if(empty($service_id) || empty($date_rdv) || empty($heure_rdv) || empty($prothesiste)) {
            // * Si l'un des champs requis est vide, on affiche un message d'erreur
            $error_message = "❌ Veuillez remplir tous les champs requis.";
        }
        elseif (DateTime::createFromFormat('Y-m-d', $date_rdv) === false) {
            $error_message = "❌ La date choisi n'est pas valide.";
        }
        elseif ($date_rdv < date('Y-m-d')) {
            $error_message = "❌ Vous ne pouvez pas réserver dans le passé.";
        }
        // Vérifier que ce n'est pas un dimanche
        elseif (date('w', strtotime($date_rdv)) == 0) {
            $error_message = "❌ Le salon est fermé le dimanche.";
        }
        elseif (!in_array($prothesiste, $liste_prothesistes)) {
            // * Si le prothésiste sélectionné n'est pas dans la liste, on affiche un message d'erreur
            $error_message = "❌ Prothésiste non valide.";
        }
        elseif (!preg_match('/^(09)|(1\d):(00)|(30)$/', $heure_rdv)) {
            // * Vérifier que l'heure est au format HH:MM
            $error_message = "❌ L'heure doit être au format HH:MM.";
        }

        $creneauxOccupes = Appointments::getBusyAppointments($date_rdv, $heure_rdv); // Vérifie les créneaux occupés
        if($creneauxOccupes)
        {
            $error_message = "Ce creneau n'est plus disponible";
        }

        $service = Service::getServiceById($service_id); // Récupère la prestation par son ID
        if(!$service) 
        {
            $error_message = "Service non trouvé.";
        }

        if(empty($error_message))
        {
            $user_id = $_SESSION["user_id"];
            $service_nom = $service["nom"];
            $service_prix = $service["prix"];
            $service_duree = $service["duree"];

            $appointmentCreated = Appointments::createAppointment($user_id, $service_id, $date_rdv, $heure_rdv, $notes, $prothesiste, $service_nom, $service_prix, $service_duree);
            if($appointmentCreated)
            {
                // * Si l'insertion a réussi, on affiche un message de succès
                $success_message = "✅ Votre rendez-vous a été réservé avec succès pour le " . date('d/m/Y', strtotime($date_rdv)) . " à " . $heure_rdv . ".";
                // * On peut aussi envisager de rediriger l'utilisateur vers une page de confirmation
                // * On envoie un email de confirmation à l'utilisateur
                $templatePath = '/../view/Mails/confirmation-rdv.php';
                $variables = [
                    'nom_complet' => $_SESSION["user_firstname"] . " " . $_SESSION["user_lastname"],
                    'date_rdv' => $date_rdv,
                    'heure_rdv' => $heure_rdv,
                    'service_nom' => $service_nom,
                    'prothesiste' => $prothesiste,
                    'service_duree' => $service_duree,
                    'service_prix' => $service_prix
                ];
                $message = renderMail($templatePath, $variables);
                $email_sent = EnvoyerMail($_SESSION["user_email"], "Confirmation de votre rendez-vous", $message);
                
                $tabTitle = "Confirmation de votre rendez-vous";
                render('appointments.confirmation-rdv', compact('date_rdv', 'heure_rdv', 'service', 'prothesiste', 'notes', 'email_sent', 'tabTitle'));
                exit;
            }
            else
            {
                // * Si l'insertion a échoué, on affiche un message d'erreur
                $error_message = "❌ Une erreur est survenue lors de la réservation de votre rendez-vous. Veuillez réessayer.";
            }
        }
        $_SESSION["error_message"] = $error_message;
        // * Si on arrive ici, c'est qu'il y a eu une erreur
        header("Location: /reservation/$service_id");
        die;
    }

    // === Gère les requêtes AJAX pour obtenir les créneaux disponibles ==
    public function getTimeSlots()
    {
        // Traitement AJAX pour mettre à jour les créneaux quand la date change
        if (isset($_GET['ajax']) && $_GET['ajax'] == 'creneaux' && isset($_GET['date'])) { // Vérifie si la requête est une requête AJAX pour les créneaux
            header('Content-Type: application/json');
            
            $date = $_GET['date'];
            $creneauxLibres = getCreneauxLibres($date);
            // sleep(5);
            echo json_encode([
                'success' => true,
                'date' => $date,
                'creneaux_libres' => $creneauxLibres,
                'nb_creneaux' => count($creneauxLibres)
            ]);
            exit;
        }
    }

 // === PAGE MES RENDEZ-VOUS ===
    public function mesRendezVous()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION["logged"]) || !isset($_SESSION["user_id"])) {
            header("Location: /connexion");
            exit;
        }

        // Récupérer les rendez-vous de l'utilisateur connecté
        $rendezVous = Appointments::getUserAppointments($_SESSION['user_id']);

        $tabTitle = "Mes Rendez-vous - Nails Endless Beauty";
        $additionalCss = ["/assets/css/26-appointment-user-menu.css"];
        render('appointments.appointment-user-menu', compact('tabTitle', 'additionalCss', 'rendezVous'));
    }

}