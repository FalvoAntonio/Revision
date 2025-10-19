<?php

namespace App\Models;

use App\Includes\Database;

class Appointments{
// == Pour vérifier les créneaux occupés ==
    public static function getBusyAppointments($date, $time): array  
    {
        $pdo = Database::pdo(); 
        $stmt = $pdo->prepare("
            SELECT heure_rdv FROM rendez_vous 
            WHERE date_rdv = ? 
            AND heure_rdv = ?
            AND statut IN ('confirmé', 'en_attente')
        ");
        $stmt->execute([$date, $time]);
        return $stmt->fetchAll();
    }
    // == Pour vérifier les créneaux occupés par date ==
    public static function getBusyAppointmentsByDate($date): array 
    {
        $pdo = Database::pdo();
         // == Récupérer les créneaux déjà occupés pour cette date ==
        $sql = $pdo->prepare("
            SELECT heure_rdv 
            FROM rendez_vous 
            WHERE date_rdv = ? 
            AND statut IN ('confirmé', 'en_attente')
        ");
        $sql->execute([$date]);
        return $sql->fetchAll(\PDO::FETCH_COLUMN);
    }
    public static function createAppointment($user_id, $service_id, $date_rdv, $heure_rdv, $notes, $prothesiste, $service_nom, $service_prix, $service_duree): bool
    { // == Pour créer un rendez-vous ==
        
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("
            INSERT INTO rendez_vous (user_client, service_id, date_rdv, heure_rdv, notes, prothesiste, service_nom, service_prix, service_duree) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $service_id, $date_rdv, $heure_rdv, $notes, $prothesiste, $service_nom, $service_prix, $service_duree]);
    }

    
    // === POUR RÉCUPÉRER LES RENDEZ-VOUS D'UN UTILISATEUR ===
public static function getUserAppointments($user_id): array 
{
    $pdo = Database::pdo();
    $stmt = $pdo->prepare("
        SELECT * FROM rendez_vous 
        WHERE user_client = ? 
        ORDER BY date_rdv ASC, heure_rdv ASC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}
}