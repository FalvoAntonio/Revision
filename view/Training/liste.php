
<section class="title-section">
    <h1>Formations</h1>
    <p class="subtitle">Découvrez nos formations professionnelles en beauté et esthétique</p>
</section>

<h2 class="h2-styled">Nos Formations</h2>

<div class="container-formations">
    <section class="section">
        <div class="formations-grid">
            <!-- ici je vais inclure le code html de la carte de formation dans une fonction et l'appeler pour chaque formation 
                -->
            <!-- Boucle pour afficher chaque formation -->
            <?php foreach($formations as $formation): ?>
                <!--  $formations as $formation signifie que je parcours chaque formation dans mon tableau $formations.
                    le $formation est un tableau associatif qui contient les informations de chaque formation. Et $formations
                    est un tableau qui contient toutes les formations. 
                    $formations est déclaré dans le fichier PHP qui récupère les données de la base de données. (en haut de cette page)-->
                <div class="formation-card" style='background-image: url(/assets<?= $formation["image_path"] ?>)'>
                    <div>
                        <h3 class="formation-title"><?= $formation["title"] ?></h3>
                        <p class="formation-description"><?= $formation["description"] ?></p>
                        <p class="prix" >
                            <?php if(empty($formation["discount_price"])): ?>
                                <?= $formation["price"] ?>€
                            <?php else: ?>
                                <span class="prix-barre"><?= $formation["price"] ?>€</span>
                                <span class="prix-promo"><?= $formation["discount_price"] ?>€</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <!-- Je veux que à la fin de mon URL tu ajoutes formation = : le slug  -->
                    <!-- Ce qui me permet d'afficher la bonne formation correspondant -->
                    <a href="/formation/<?= $formation["slug"] ?>" class="btn-decouverte">Je découvre</a>
                    <a href="#" class="btn-achat">Acheter</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    
    <!-- Section Pourquoi Choisir -->
    <section class="section">
        <div class="why-choose">
            <h2>Pourquoi choisir nos formations ?</h2>
            
            <div class="advantages-grid">
                <div class="advantage-item">
                    <div class="advantage-icon">🏆</div>
                    <h3 class="advantage-title">Expertise Reconnue</h3>
                    <p class="advantage-description">Nos formateurs sont des professionnels reconnus dans le domaine de l'esthétique avec plus de 10 ans d'expérience.</p>
                </div>
                
                <div class="advantage-item">
                    <div class="advantage-icon">♾️</div>
                    <h3 class="advantage-title">Accès Illimité</h3>
                    <p class="advantage-description">Accédez à vie à vos formations et bénéficiez des mises à jour continues de nos contenus pédagogiques.</p>
                </div>
                
                <div class="advantage-item">
                    <div class="advantage-icon">🎓</div>
                    <h3 class="advantage-title">Certification</h3>
                    <p class="advantage-description">Obtenez un certificat reconnu à la fin de chaque formation pour valoriser vos compétences professionnelles.</p>
                </div>
                
                <div class="advantage-item">
                    <div class="advantage-icon">💬</div>
                    <h3 class="advantage-title">Support Personnalisé</h3>
                    <p class="advantage-description">Bénéficiez d'un accompagnement personnalisé avec nos experts pour répondre à toutes vos questions.</p>
                </div>
            </div>
        </div>
    </section>
</div>