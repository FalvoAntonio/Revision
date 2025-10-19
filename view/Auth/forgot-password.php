 <div class="container">
        <h2>🔐 Vous avez oublié votre mot de passe ?</h2>
        <p>Saisissez votre adresse e-mail enregistrée. Nous vous enverrons un lien pour réinitialiser votre mot de
            passe.</p>
        <form action="/verification-de-confirmation-mail" method="POST">
          <!-- J'ajoute ma page PHP pour l'utilisation du bouton -->
            <input type="email" name="email" placeholder="Votre adresse e-mail" required />
            <button type="submit" class="btn-form">Envoyer</button>


            <a href="./accueil" class="button-accueil btn-form">Accueil</a>
        </form>
    </div>
