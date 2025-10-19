    
        <div class="title-section">
            <h1> Prendre votre rendez-vous</h1>
            <p class="subtitle"> Réservez votre rendez-vous en ligne </p>
        </div>
    <div class="container-image-header">     
    <img src="/assets/images/Page Mon Espace/rdv-header.jpg" alt="image rdv">   
</div>
<h2 class="h2-styled">Les catégories</h2>
        

    <div class="container">
    <div class="category-choice">
            <h2>Choissisez votre categorie</h2>
        </div>
       <!-- INTERFACE DE NAVIGATION PAR ONGLETS -->
    <!-- Bloc contenant les boutons de navigation entre catégories -->
    <!-- Chaque bouton utilise un attribut data-category pour l'interactivité JavaScript -->
        <div class="category-block">
        <!-- Bouton actif par défaut (classe "active" appliquée) -->
        <!-- data-category : attribut HTML5 personnalisé utilisé par JavaScript pour identifier la catégorie -->
            <button class="category-btn active" data-category="beauté-des-mains">Beauté des Mains</button>
        <!-- Autres boutons de catégories (inactifs au chargement) -->
            <button class="category-btn" data-category="rallongement-ongles">Rallongement</button>
            <button class="category-btn" data-category="beauté-des-pieds">Beauté des Pieds</button>
            <button class="category-btn" data-category="beauté-du-regard">Beauté du Regard</button>
            <button class="category-btn" data-category="épilations">Épilations</button>
        </div>

        
        <!-- BOUCLE PRINCIPALE : parcourt chaque catégorie dans $services -->
        <?php foreach($services as $categorie => $servicesCategorie): ?>
            
             <!-- Conteneur pour chaque catégorie de services -->
        <!-- Classe conditionnelle : seule la première catégorie ("BEAUTÉ DES MAINS") est visible au chargement -->
        <div class="service-category <?= $categorie === 'BEAUTÉ DES MAINS' ? 'active' : '' ?>" id="<?= mb_strtolower(str_replace(' ', '-', $categorie)) ?>">
             <!--
             Explications de l'ID dynamique :
             - str_replace(' ', '-', $categorie) : remplace les espaces par des tirets
             - mb_strtolower() : convertit en minuscules (support Unicode français)
             - Résultat : "BEAUTÉ DES MAINS" devient "beauté-des-mains"
             - Crée des IDs HTML valides pour le ciblage JavaScript
             -->

             <!-- Grille CSS pour l'affichage des cartes de services -->
            <div class="service-grid">

             <!-- BOUCLE INTERNE : génère chaque service de la catégorie courante -->
            <!-- $servicesCategorie contient tous les services d'une catégorie spécifique -->
                <?php foreach($servicesCategorie as $service): ?>

                    <!-- Lien de réservation avec ID dynamique du service -->
                    <!-- URL générée : /reservation/1, /reservation/5, etc. (architecture RESTful) -->
          

                    <!-- Carte d'affichage du service -->
                    <div class="service-card">
                        <!-- Nom du service avec échappement HTML pour la sécurité -->
                        <!-- htmlspecialchars() prévient les attaques XSS en échappant les caractères dangereux -->
                        <div class="service-name"><?= htmlspecialchars($service['nom']) ?></div>
                         <!-- Durée du service avec emoji et échappement de sécurité -->
                        <div class="service-duration">⏱️ <?= htmlspecialchars($service['duree']) ?> min</div>
                        <!-- Prix du service avec échappement de sécurité -->
                        <div class="service-price"><?= htmlspecialchars($service['prix']) ?>€</div>
          <a href="/reservation/<?= $service['id'] ?>" class="service-card-link">Prendre RDV</a>
                        <!-- AFFICHAGE CONDITIONNEL des notes -->
                        <!-- Vérifie si le champ 'note' existe ET n'est pas vide avant l'affichage -->
                        <!-- Évite les div vides et améliore les performances -->
                        <?php if (!empty($service['note'])): ?>
                            <div class="service-note"><?= htmlspecialchars($service['note']) ?></div>
                        <?php endif; ?>
                    </div>
                    <!-- Fin de la boucle des services individuels -->
                <?php endforeach; ?>
                <!-- Fin de la grille de services -->
            </div>
            <!-- Fin du conteneur de catégorie -->
        </div>
        <?php endforeach; ?>
        <!-- Fin de la boucle principale des catégories -->

        <!-- BEAUTÉ DES MAINS -->
        <!-- ! Ajoute de "active et id="mains" -->
        <!-- <div class="service-category active" id="mains">
            <div class="service-grid">
                <a href="/reservation/1" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Manucure Russe seule</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">35€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Gainage Nude</div>
                    <div class="service-duration">⏱️ 1h</div>
                    <div class="service-price">50€</div>
                    <div class="service-note">Sur ongle nu</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Gainage avec Couleur</div>
                    <div class="service-duration">⏱️ 1h10</div>
                    <div class="service-price">55€</div>
                    <div class="service-note">Sur ongle nu</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Remplissage Gainage Nude</div>
                    <div class="service-duration">⏱️ 1h10</div>
                    <div class="service-price">55€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Remplissage Gainage + Couleur</div>
                    <div class="service-duration">⏱️ 1h20</div>
                    <div class="service-price">60€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Dépose Complète</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">25€</div>
                </div>
            </div>
        </div> -->

        <!-- RALLONGEMENT ONGLES -->
        <!--  ! Ajout : id="category-rallongement" -->
        <!-- <div class="service-category" id="rallongement">
            <div class="service-grid">
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Rallongement Capsules Gel X</div>
                    <div class="service-duration">⏱️ 1h35</div>
                    <div class="service-price">65€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Dépose + Repose Capsules Gel X</div>
                    <div class="service-duration">⏱️ 1h40</div>
                    <div class="service-price">70€</div>
                </div>
            </div>
        </div> -->

        <!-- BEAUTÉ DES PIEDS -->
        <!-- ! Ajoute : id="category-pieds"> -->
        <!-- <div class="service-category" id="pieds">
            <div class="service-grid">
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Beauté des Pieds Russe seule</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">35€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Renfort Base Nude</div>
                    <div class="service-duration">⏱️ 1h</div>
                    <div class="service-price">45€</div>
                    <div class="service-note">Sur ongle nu</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Renfort avec Couleur</div>
                    <div class="service-duration">⏱️ 1h10</div>
                    <div class="service-price">50€</div>
                    <div class="service-note">Sur ongle nu</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Dépose + Renfort Base Nude</div>
                    <div class="service-duration">⏱️ 1h10</div>
                    <div class="service-price">50€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Dépose + Renfort + Couleur</div>
                    <div class="service-duration">⏱️ 1h15</div>
                    <div class="service-price">55€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Traitement Anti-Callosités + Renfort</div>
                    <div class="service-duration">⏱️ 1h35</div>
                    <div class="service-price">90€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Traitement Anti-Callosités + Beauté Russe</div>
                    <div class="service-duration">⏱️ 1h10</div>
                    <div class="service-price">80€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Dépose Complète</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">25€</div>
                </div>
            </div>
        </div> -->

        <!-- BEAUTÉ DU REGARD -->
        <!--  ! Ajout : id="category-regard" -->
        <!-- <div class="service-category" id="regard">
            <div class="service-grid">
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Teinture Cils ou Sourcils</div>
                    <div class="service-duration">⏱️ 15 min</div>
                    <div class="service-price">10€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Réhaussement des Cils</div>
                    <div class="service-duration">⏱️ 40 min</div>
                    <div class="service-price">40€</div>
                </div>
            </div>
        </div> -->

        <!-- ÉPILATIONS -->
        <!--  ! Ajout : id="category-epilation" -->
        <!-- <div class="service-category" id="epilation">
            <div class="service-grid">
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Sourcils</div>
                    <div class="service-duration">⏱️ 12 min</div>
                    <div class="service-price">10€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Lèvres & Sourcils</div>
                    <div class="service-duration">⏱️ 15 min</div>
                    <div class="service-price">15€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Lèvres ou Menton</div>
                    <div class="service-duration">⏱️ 10 min</div>
                    <div class="service-price">7€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Aisselles</div>
                    <div class="service-duration">⏱️ 10 min</div>
                    <div class="service-price">10€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">1/2 Jambes</div>
                    <div class="service-duration">⏱️ 20 min</div>
                    <div class="service-price">20€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Jambes Entières</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">30€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Maillot Intégral + Inter Fessier</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">25€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Maillot Américain</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">20€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Maillot Échancré</div>
                    <div class="service-duration">⏱️ 15 min</div>
                    <div class="service-price">15€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Sillon Inter Fessier (SIF)</div>
                    <div class="service-duration">⏱️ 5 min</div>
                    <div class="service-price">5€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">1/2 Bras</div>
                    <div class="service-duration">⏱️ 20 min</div>
                    <div class="service-price">15€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Bras Entiers</div>
                    <div class="service-duration">⏱️ 30 min</div>
                    <div class="service-price">20€</div>
                </div>
                <a href="#" class="service-card-link">Prendre RDV</a>
                <div class="service-card">
                    <div class="service-name">Bas du Dos</div>
                    <div class="service-duration">⏱️ 10 min</div>
                    <div class="service-price">15€</div>
                </div>
            </div>
        </div> -->
    </div>
    <!-- Fin du container principal -->

    <!--
RÉSUMÉ DE L'ARCHITECTURE :
1. MODÈLE MVC RESPECTÉ :
   - Les données $services viennent du Controller
   - Cette Vue se contente d'afficher les données
   - Aucune requête SQL directe dans la Vue

2. SÉCURITÉ WEB :
   - htmlspecialchars() utilisé sur toutes les données utilisateur
   - Protection contre les attaques XSS

3. INTERACTIVITÉ JAVASCRIPT :
   - Attributs data-category pour lier boutons et sections
   - Classe "active" gérée dynamiquement
   - IDs générés pour le ciblage JavaScript

4. PERFORMANCE :
   - Génération dynamique évite la duplication de code
   - Affichage conditionnel évite le HTML inutile
   - Structure optimisée pour le CSS et JavaScript
-->
