
<section class="container form-reset-password">
    <h1>Bienvenue</h1>
    <h2>Vous pouvez réinitialiser votre mot de passe</h2>

    <form id="Formulaire-nouveau-mdp" action="/verification-de-formulaire-reinitialiser-motdepasse" method="POST" class="form-reinitialisation-mdp ">
      <div class="New-mdp">
      <label for="New-mdp">Entrer votre nouveau mot de passe :</label>
      <input type="password" name="New-mdp" id="New-mdp" required/>
    </div>
  <div class="Confirm-mdp">
    <label for="Confirm-mdp">Retaper votre nouveau mot de pass :</label>
    <input type="password" name="Confirm-mdp" id="Confirm-mdp" required/>
  </div>
  <div class="submit-new-mdp">
    <input type="submit" value="Envoyez" class="btn-form">
  </div>
    </form>
</section>    

