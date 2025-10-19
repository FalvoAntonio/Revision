<?php

use App\Models\Appointments;

require_once __DIR__ . "/../Models/Appointments.php"; // Connexion à la base de données
function getCreneauxLibres($date = null) {
    $derniereHeureRDV = 19; // Par défaut, dernier créneau possible est 19h 
    // Si aucune date n'est fournie, utiliser aujourd'hui
    if (!$date) {
        $date = date('Y-m-d');
    }
    
    // Vérifier que la date n'est pas dans le passé
    if ($date < date('Y-m-d')) {
        return [];
    }
    
    // On récupère le jour de la semaine pour la date donnée
    // 0 = dimanche, 1 = lundi, ..., 6 = samedi
    $dayOfWeek = date('w', strtotime($date));

    // Vérifier que ce n'est pas un dimanche (jour fermé)
    if ($dayOfWeek == 0) { // 0 = dimanche
        return [];
    }
    // Si c'est un samedi, on s'arrête plus tôt
    if ($dayOfWeek == 6) { // 6 = samedi
        $derniereHeureRDV = 15; // Dernier créneau possible est 17h30
    }
    
    // Générer tous les créneaux possibles (9h à 18h par tranches de 30min)
    $creneauxPossibles = [];
    for ($h = 9; $h <= $derniereHeureRDV; $h++) { // Jusqu'à 17h pour finir à 18h max
        for ($m = 0; $m < 60; $m += 30) {
            $heure = sprintf("%02d:%02d", $h, $m);
            
            $creneauxPossibles[] = $heure;
        }

    }
    
    // Récupérer les créneaux déjà occupés pour cette date
    $creneauxOccupesAvecSecondesAffiché = Appointments::getBusyAppointmentsByDate($date);
    // [0=>["heure_rdv"=>"12:30:00"], 1=>["heure_rdv"=>"14:30:00"]] $creneauxOccupesBruts[0]["heure_rdv"]
    // [0=>"12:30:00", 1=>"14:30:00"] $creneauxOccupesBruts[0]

    // Convertir le format HH:MM:SS de la bdd en HH:MM pour la comparaison
    // array_map permet de boucler sur chaque élément du tableau $creneauxOccupesBruts
    // et d'appliquer une fonction de transformation à chaque élément.
    // Ici, on garde seulement les 5 premiers caractères de chaque créneau occupé
    $creneauxOccupes = array_map(function($heure) {
            return substr($heure, 0, 5); // Garde seulement les 5 premiers caractères (HH:MM)
            // Par exemple, "14:30:00" devient "14:30"
    }, $creneauxOccupesAvecSecondesAffiché);

    // Filtrer les créneaux libres
    // array_diff permet de comparer les deux tableaux et de retourner les éléments
    // qui sont dans $creneauxPossibles mais pas dans $creneauxOccupes
    $creneauxLibres = array_diff($creneauxPossibles, $creneauxOccupes);
    
    // Si la date donnée en paramètre est celle d'aujourd'hui
    if ($date == date('Y-m-d')) {
        // Si c'est aujourd'hui, filtrer les heures déjà passées
        $heureActuelle = date('H:i');
        // array_filter permet de filtrer les éléments du tableau $creneauxLibres
        // en gardant seulement ceux qui sont supérieurs à l'heure actuelle
        $creneauxLibres = array_filter($creneauxLibres, function($creneau) use ($heureActuelle) {
            // On compare les créneaux libres avec l'heure actuelle
            // si la comparaison est vraie, on garde le créneau
            // sinon, on le filtre (on le supprime du tableau)
            return $creneau > $heureActuelle;
        });
    }
    
    return array_values($creneauxLibres); // Réindexer le tableau
}

// Fonction pour afficher les créneaux sous forme d'options HTML
function afficherOptionsCreneaux($pdo, $date = null) {
    $creneauxLibres = getCreneauxLibres($pdo, $date);
    
    if (empty($creneauxLibres)) {
        if (!$date) {
            echo '<option value="">Sélectionnez d\'abord une date</option>';
        } else {
            $dayOfWeek = date('w', strtotime($date));
            if ($dayOfWeek == 0) {
                echo '<option value="">Le salon est fermé le dimanche</option>';
            } else {
                echo '<option value="">Aucun créneau disponible ce jour</option>';
            }
        }
        return;
    }
    
    echo '<option value="">Choisir un créneau</option>';
    foreach ($creneauxLibres as $creneau) {
        echo "<option value='$creneau'>$creneau</option>";
    }
}
