<div class="container confirmation-mail">
        <h1>Confirmation d'email</h1>
        
        <?php if($success): ?>
          <!-- On vérifie si $success == true, donc si l'e-mail a bien été confirmé -->
            <div class="success">
                <h2>✅ Success !</h2>
                <p><?php echo $message; ?></p>
                <!-- Le message $message est affiché -->
                <a href="/connexion" class="btn">Se connecter maintenant</a>
              </div>
              <?php else: ?>
                <!-- Sinon si $success est faux, donc que l'email n'a pas pu être confirmé (token invalide,expiré ou manquant)-->
                <div class="error">
                <h2>❌ Erreur</h2>
                <!-- Donc si c'est faux on affiche alors le message d'erreur -->
                <p><?php echo $message; ?></p>
                <a href="/inscription" class="btn">Retour à l'inscription</a>
            </div>
        <?php endif; ?>
    </div>
    
