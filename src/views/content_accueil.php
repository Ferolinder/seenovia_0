<div id="home" class="center connection">
  <div id="top">
    <h1>CONNECTION</h1>
  </div>
  <div class="input">
    <img src="pictures/username.png">
    <input type="text" placeholder="Enter your username" class="username" required>
  </div>
  <div class="input">
    <img src="pictures/password.png">
    <input type="password" placeholder="Enter your password" class="password" required>
    <button type="button" class="toggle-password" id="togglePassword" aria-label="Afficher/masquer le mot de passe">
      <img src="pictures/hidden.png" alt="Afficher" class="toggle-icon">
    </button>
  </div>
  <a class="button-modal" id="buttonConnect">CONNECT</a>
  <div class="error"></div>
  <div class="accounts-box">
    <h3>Comptes de démonstration</h3>
  
    <div class="account-card">
      <div class="account-title">Agri</div>
  
      <div class="credential">
        <span>alice@test.fr</span>
        <button type="button" class="copy-btn" onclick="copyText('alice@test.fr')" title="Copier l'identifiant">
          📋
        </button>
      </div>
  
      <div class="credential">
        <span>aaa</span>
        <button type="button" class="copy-btn" onclick="copyText('aaa')" title="Copier le mot de passe">
          📋
        </button>
      </div>
    </div>
  
    <div class="account-card">
      <div class="account-title">Admin</div>
  
      <div class="credential">
        <span>admin1@test.fr</span>
        <button type="button" class="copy-btn" onclick="copyText('admin1@test.fr')" title="Copier l'identifiant">
          📋
        </button>
      </div>
  
      <div class="credential">
        <span>abc</span>
        <button type="button" class="copy-btn" onclick="copyText('abc')" title="Copier le mot de passe">
          📋
        </button>
      </div>
    </div>
  </div>
</div>

