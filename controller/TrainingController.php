<?php


use App\Includes\DebugBar;
use App\Models\Training;

session_start();

class TrainingController
{
    public function listeFormations()
    {
        $formations = Training::getAllTrainings(); // Récupère toutes les formations
        // DebugBar::info($formations); // Affiche les formations dans la DebugBar pour le débogage

        // Inclure la vue pour afficher les formations
        $tabTitle = "Liste des Formations";
        $additionalCss = ["/assets/css/09-course-list.css"];
        render('training.liste', compact('tabTitle', 'formations', 'additionalCss'));
    }
    public function detailFormation($slug)
    {
        if (!isset($slug))
        // Si je n'ai pas de formation (dans l'URL) > http://localhost:8000/HTML/Formations/Formation-manucure-russe.php?formation=extension_gel_x
        // Je suis redirigé vers la page formations
        {
            header("Location: /liste-formations");
            exit;
        }
        $formation = Training::getTrainingBySlug($slug);
        if (!$formation)
        // Si l'id ne correspond, si tu ne trouves pas de formations 
        // Admetons j'ai que 5 formation donc id= 1 ,2 ,3 ,4 ,5 et j'ai un id=65 je serais redirigé vers la page formation
        {
            header("Location: /liste-formations");
            exit;
        }
        // Ici, vous pouvez récupérer les détails de la formation en fonction du slug
        // Par exemple, vous pouvez faire une requête à la base de données pour obtenir les informations de la formation
        // Pour l'instant, nous allons juste afficher le slug
        $tabTitle = "Détail de la Formation";
        $additionalCss = ["/assets/css/08-course-detail.css"];
        render('training.detail', compact('tabTitle', 'formation', 'additionalCss'));
    }
}