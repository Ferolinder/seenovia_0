<section id="seenoviaPage">
  <section id="GreetingSection" class="section">
    <h1 id="Greeting" class="greeting">Bonjour admin</h1>
  </section>

  <br>

  </section>

    <section id="seenoviaControls" class="section agri-controls">
      <button id="modifyAllBtn" class="agri-button">Modifier</button>
      <button id="saveAllBtn" class="agri-button agri-button-secondary" disabled>Enregistrer</button>
      <button id="downloadSvgBtn" class="agri-button agri-button-secondary">Télécharger SVG</button>
      <button id="createAgriUserBtn" class="agri-button" style="margin-left: auto;">Créer un compte agri</button>
      <div id="seenoviaFeedback" class="agri-feedback"></div>
    </section>

    <!-- Modal for creating agri user -->
    <div id="createAgriUserModal" class="modal-overlay">
      <div class="modal-content">
        <button class="modal-close">&times;</button>
        <h2>Créer un compte agri</h2>
        <form id="createAgriUserForm" class="agri-form">
          <div class="form-group">
            <label for="agriNom">Nom *</label>
            <input type="text" id="agriNom" name="nom" required>
          </div>
          <div class="form-group">
            <label for="agriPrenom">Prénom *</label>
            <input type="text" id="agriPrenom" name="prenom" required>
          </div>
          <div class="form-group">
            <label for="agriEmail">Email *</label>
            <input type="email" id="agriEmail" name="adresseMail" required>
          </div>
          <div class="form-group">
            <label for="agriTelephone">Téléphone</label>
            <input type="tel" id="agriTelephone" name="telephone">
          </div>
          <div class="form-group">
            <label for="agriPassword">Mot de passe *</label>
            <input type="password" id="agriPassword" name="mdp" required>
          </div>
          <div id="createAgriUserError" class="form-error"></div>
          <button type="submit" class="agri-button agri-button-primary">Confirmer</button>
        </form>
      </div>
    </div>

    <br>

    <section id="seenoviaListSection" class="section">
      <div id="seenoviaList" class="seenovia-list"></div>
    </section>
</section>
