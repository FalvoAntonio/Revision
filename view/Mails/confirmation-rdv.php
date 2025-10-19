<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV Confirmé - Endless Beauty</title>
</head>
<body>
    <div>
        <h1>💅 ENDLESS BEAUTY</h1>
        <h2>✅ Votre rendez-vous est confirmé !</h2>
        
        <p>Bonjour <strong>$nom_complet$ </strong>,</p>
        
        <p>Nous avons le plaisir de confirmer votre rendez-vous chez Endless Beauty.</p>
        
        <h3>📋 Récapitulatif de votre RDV :</h3>
        <p><strong>📅 Date :</strong> $date_rdv$ </p>
        <p><strong>🕐 Heure :</strong> $heure_rdv$</p>
        <p><strong>💅 Prestation :</strong> $service_nom$ </p>
        <p><strong>👩‍💼 Prothésiste :</strong> $prothesiste$</p>
        <p><strong>⏱️ Durée :</strong> $service_duree$ minutes</p>
        <p><strong>💰 Prix :</strong> $service_prix$€</p>

        <h3>📍 Adresse du salon :</h3>
        <p><strong>Endless Beauty</strong><br>
        37 Rue de la Cousinerie<br>
        59650 Villeneuve-d'Ascq</p>
        
        <h3>ℹ️ Informations importantes :</h3>
        <ul>
            <li><strong>Arrivée :</strong> Merci d'arriver 5 minutes avant votre RDV</li>
            <li><strong>Préparation :</strong> Retirez votre vernis si nécessaire</li>
            <li><strong>Annulation :</strong> Prévenez-nous au moins 24h à l'avance</li>
            <li><strong>Contact :</strong> Pour toute question, contactez-nous</li>
        </ul>
        
        <h3>📞 Besoin d'aide ?</h3>
        <p>Une question ? Un empêchement ?<br>
        📧 Email : <strong>Endlessbeauty.lc@gmail.com</strong></p>
        
        <hr>
        <p><strong>💖 Merci de votre confiance !</strong><br>
        Nous avons hâte de vous accueillir chez Endless Beauty.</p>
        
        <p><em>À très bientôt,<br>
        L'équipe Endless Beauty</em></p>
        
        <p><small>Email automatique - Ne pas répondre<br>
        Envoyé le <?= date('d/m/Y à H:i') ?> </small></p>
    </div>
</body>
</html>

