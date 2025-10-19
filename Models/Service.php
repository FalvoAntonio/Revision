<?php

namespace App\Models;

use App\Includes\Database;

class Service
{
    public static function getServiceById($service_id)
    { // Pour récupérer un service par son ID
        $pdo = Database::pdo();
        $sql_service = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $sql_service->execute([$service_id]);
        return $sql_service->fetch();
    }
    public static function getAllServices()
    {
        // Récupère TOUS les services et les groupe par catégorie directement en SQL
        $pdo = Database::pdo();
        /**
         * REQUÊTE SQL:
         * 
         * 1. SELECT categorie : On récupère le nom de la catégorie
         * 
         * 2. JSON_ARRAYAGG() : Fonction MySQL qui crée un tableau JSON
         *    - Groupe tous les services d'une même catégorie dans un tableau
         *    - Évite de faire plusieurs requêtes ou du code PHP complexe
         * 
         * 3. JSON_OBJECT() : Fonction MySQL qui crée un objet JSON
         *    - Transforme chaque ligne de service en objet JSON structuré
         *    - Format : {"id": 1, "nom": "Manucure", "duree": 30, "prix": 35}
         * 
         * 4. GROUP BY categorie : Regroupe les résultats par catégorie
         *    - Tous les services "BEAUTÉ DES MAINS" ensemble
         *    - Tous les services "BEAUTÉ DES PIEDS" ensemble, etc.
         * 
         * 5. ORDER BY categorie : Trie les catégories par ordre alphabétique
         */
        $sql = $pdo->query("
    SELECT categorie, 
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'id', id,
            'nom', nom,
            'duree', duree,
            'prix', prix
        )
    ) as services
    FROM services
    GROUP BY categorie
    ORDER BY categorie
    ");
        /**
         * RÉCUPÉRATION AVEC PDO::FETCH_GROUP
         * 
         * PDO::FETCH_GROUP : Fonction PDO qui groupe automatiquement par la première colonne
         * - Première colonne = 'categorie'
         * - Résultat : ['BEAUTÉ DES MAINS' => [...], 'BEAUTÉ DES PIEDS' => [...]]
         * 
         * PDO::FETCH_ASSOC : Retourne des tableaux associatifs au lieu de tableaux indexés
         * - Plus facile à utiliser : $service['nom'] au lieu de $service[0]
         */
        $services = $sql->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_ASSOC);

        /**
         * TRAITEMENT POST-REQUÊTE : DÉCODAGE JSON
         * 
         * PROBLÈME : Les données JSON sont stockées comme chaîne de caractères
         * SOLUTION : json_decode() pour les transformer en tableau PHP utilisable
         */

        // decode le json
        foreach ($services as $categorie => $data) {
            /**
             * EXPLICATION DU FOREACH :
             * - $categorie = nom de la catégorie (ex: "BEAUTÉ DES MAINS")
             * - $data = tableau contenant les données JSON de cette catégorie
             * 
             * STRUCTURE AVANT DÉCODAGE :
             * $services['BEAUTÉ DES MAINS'][0]['services'] = '{"id":1,"nom":"Manucure",...}'
             * 
             * STRUCTURE APRÈS DÉCODAGE :
             * $services['BEAUTÉ DES MAINS'] = [
             *     ['id' => 1, 'nom' => 'Manucure', 'duree' => 30, 'prix' => 35],
             *     ['id' => 2, 'nom' => 'Gainage', 'duree' => 60, 'prix' => 50]
             * ]
             */
            $services[$categorie] = json_decode($data[0]['services'], true);
            // json_decode(..., true) = convertit le JSON en tableau PHP (pas en objet)
        }
        /**
         * RETOUR FINAL :
         * Structure organisée parfaite pour l'affichage en boucles dans la vue :
         * 
         * [
         *   'BEAUTÉ DES MAINS' => [
         *       ['id' => 1, 'nom' => 'Manucure Russe', 'duree' => 30, 'prix' => 35],
         *       ['id' => 2, 'nom' => 'Gainage nude', 'duree' => 60, 'prix' => 50]
         *   ],
         *   'BEAUTÉ DES PIEDS' => [
         *       ['id' => 8, 'nom' => 'Beauté des pieds', 'duree' => 30, 'prix' => 35]
         *   ]
         * ]
         */
        return $services;
    }
    
}
/**
 * AVANTAGES DE CETTE APPROCHE:
 * 
 * 1. PERFORMANCE : Une seule requête au lieu de plusieurs
 * 2. LISIBILITÉ : Code PHP simple et clair
 * 3. MAINTENABILITÉ : Ajout automatique de nouvelles catégories
 * 4. SÉCURITÉ : Requêtes préparées contre les injections SQL
 * 5. ARCHITECTURE MVC : Séparation claire des responsabilités
 * 
 * UTILISATION DANS LE CONTRÔLEUR :
 * $services = Service::getAllServices();
 * 
 * UTILISATION DANS LA VUE :
 * foreach($services as $categorie => $servicesCategorie) {
 *     echo "<h2>$categorie</h2>";
 *     foreach($servicesCategorie as $service) {
 *         echo $service['nom'] . " - " . $service['prix'] . "€";
 *     }
 * }
 */


