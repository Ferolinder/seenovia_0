<section id="seenoviaPage">
  <section id="GreetingSection" class="section">
    <h1 id="Greeting" class="greeting">Bonjour admin</h1>
  </section>

  <br>

  </section>

    <section id="seenoviaControls" class="section agri-controls">
      <button id="modifyAllBtn" class="agri-button">Modifier</button>
      <button id="saveAllBtn" class="agri-button agri-button-secondary" disabled>Enregistrer</button>
      <!-- <button id="downloadCsvBtn" class="agri-button agri-button-secondary">Télécharger CSV</button> -->
      <button id="createAgriUserBtn" class="agri-button" style="margin-left: auto;">Créer un compte agri</button>
      <button id="Modifier tableau" class="agri-button" style="margin-left: auto;" onclick="setTablePage()">Modifier le tableau</button>
      <div id="seenoviaFeedback" class="agri-feedback"></div>
    </section>

    <!-- Modal for creating agri user -->
    <div id="createAgriUserModal" class="modal-overlay">
      <div class="modal-content">
        <button class="modal-close">&times;</button>
        <div class="modal-mode-tabs">
          <button type="button" id="modal-button-mode">Change mode</button>
        </div>

        <!-- Mode : Insertion manuelle -->
        <div id="modeManual" class="modal-mode active">
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

        <!-- Mode : Création depuis CSV -->
        <div id="modeCsv" class="modal-mode">
          <h2>Créer des comptes depuis CSV</h2>
          <form id="createAgriCsvForm" class="agri-form">
            <div class="form-group">
              <label for="agriCsvFile">Sélectionner un fichier CSV</label>
              <input type="file" id="agriCsvFile" name="agriCsvFile" accept=".csv,text/csv" required>
              <small>Format : nom;prenom;adresseMail;telephone;mdp;admin</small>
            </div>
            <div id="createAgriCsvError" class="form-error"></div>
            <button type="button" id="downloadAgriTemplateBtn" class="agri-button agri-button-secondary">Télécharger modèle CSV</button>
            <button type="submit" class="agri-button agri-button-primary">Confirmer</button>
          </form>
        </div>
      </div>
    </div>

    <br>

    <section id="seenoviaListSection" class="section">
      <div id="seenoviaList" class="seenovia-list"></div>
    </section>
</section>
