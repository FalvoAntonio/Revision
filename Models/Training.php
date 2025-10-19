<?php

namespace App\Models;

use App\Includes\Database;

class Training
{
public static function getAllTrainings()
{
        $pdo = Database::pdo();
// Ici je fais une requête SQL pour récupérer toutes les formations de la base de données
    // Je vais utiliser PDO pour me connecter à la base de données et récupérer les données
    // Dans cette requête je demande à PDO de me récupérer les colonnes title, description, price, discount_price, slug et image_path de la table formations
    // Je vais utiliser CAST pour convertir les prix en entiers, car dans la base de données les prix sont stockés en tant que chaînes de caractères (VARCHAR)
    // Je vais utiliser fetchAll() pour récupérer toutes les formations sous forme de tableau associatif
    $sql = $pdo->query("SELECT title, description, CAST(price AS SIGNED) AS price, CAST(discount_price AS SIGNED) AS discount_price, slug, image_path FROM formations");
    $formations = $sql->fetchAll();  // C'est ici que la variable est déclarée comme contenant le tableau des 5 formations
    // Nous allons récupèré toutes les formations (5) | $formations = [formation1, formation2, formation3, formation4, formation5]
    return $formations;
}
public static function getTrainingBySlug($slug) // Pour récupérer une formation par son slug
{
    $pdo = Database::pdo();
    $sql = $pdo->prepare("SELECT * FROM formations WHERE slug=?"); // Je récupère toutes les colonnes de la table formations où le slug correspond au slug passé en paramètre
    $sql->execute([$slug]); // Exécution de la requête avec le slug
    $formation = $sql->fetch(); // Récupération de la formation
    return $formation;
}   
}