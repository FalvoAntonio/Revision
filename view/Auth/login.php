<?php require_once APP_ROOT . '/../includes/MessageFlash.php'; ?>

<?php afficheMessageFlash("Message-confirmation-envoi-mail"); ?> 

    <section class="Connexion container">

        <h1>Se connecter</h1>
        <p>Accédez à votre espace beauté en toute simplicité.</p>
        <p>Gérez vos formations, suivez votre progression et découvrez nos astuces exclusives pour sublimer votre art.
        </p>
        
        <form id="loginForm" action="/verification-de-formulaire-connexion" method="post">
            <div class="Mail">
                <label for="email">Adresse E-mail</label>
                <input type="email" name="email" id="email" placeholder="Saisissez votre e-mail">
                <span class="error-message error"></span>
                <?php MessagesErrorsFormulaire("email") ?>
            </div>
            <div class="MDP">
                <label for="Mot de passe">Mot de passe</label>
                <div class="input-container">
                    <input class="inputMDP" type="password" name="mdp" id="mdp"
                        placeholder="Saisissez votre mot de passe">
                    <img class="LogoEyes1" src="/assets/Images/eyes.png" alt="Mot de passe visible">
                    <img class="LogoEyes2" src="/assets/Images/noeyes.png" alt="Mot de passe non visible">
                </div>
                <!-- La fonction MessagesErrorsFormulaire provient du fichier "Message-Flash.php" -->
                <span class="error-message error"></span>
                <?php MessagesErrorsFormulaire("mdp") ?>
                <!-- Pour le bruteforce -->
                <?php MessagesErrorsFormulaire("tentative") ?>
            </div>
            <input type="submit" value="Connectez-vous" name="boutonConnexion" class="btn-form">
            <a href="/mot-de-passe-oublie">
                <p>Mot de passe oublié ? </p>
            </a>
        </form>
    </section>

    <section class="Creation-compte container">
        <h1>Création d'un compte</h1>
        <p>Vous n’avez pas encore de compte ? Rejoignez notre communauté de passionnés de beauté.</p>
        <p>Créez votre compte et commencez votre voyage vers l’expertise esthétique.</p>
        <a href="/inscription"><input class="INPUT btn-form" type="submit"
                value="Je crée mon compte"></a>
    </section>
