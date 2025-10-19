

   <section class="title-section">
    <div class="title-content">
        <h1><?= $formation["title"] ?></h1> 
        <p class="subtitle"> <?= $formation["description"] ?></p>
    </div> 
</section>

    <div class="content-container">
        <?= $formation["content"] ?>
    </div>

    <div class="action-container">
        <p>Prix : <?= $formation["price"] ?> €</p>
         <p>Durée :  <?= $formation["duration"]  ?></p>
        <a href="/liste-formations" class="btn">Retour aux formations</a>
    </div>

    <div class="info-section">
        <h2>Commentaires</h2>
       
        <p>Aucun commentaire pour le moment.</p>
    </div>
