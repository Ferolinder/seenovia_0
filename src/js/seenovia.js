// Admin view: list all farmers and their crops, editable, export CSV
const modifyAllBtn = document.getElementById('modifyAllBtn');
const saveAllBtn = document.getElementById('saveAllBtn');
const downloadCsvBtn = document.getElementById('downloadCsvBtn');
const createAgriUserBtn = document.getElementById('createAgriUserBtn');
const downloadAgriTemplateBtn = document.getElementById('downloadAgriTemplateBtn');
const agriCsvFileInput = document.getElementById('agriCsvFile');
const seenoviaList = document.getElementById('seenoviaList');
const feedbackBox = document.getElementById('seenoviaFeedback');

// Modal elements
const createAgriUserModal = document.getElementById('createAgriUserModal');
const createAgriUserForm = document.getElementById('createAgriUserForm');
const createAgriCsvForm = document.getElementById('createAgriCsvForm');
const modalCloseBtn = document.querySelector('.modal-close');
const createAgriUserError = document.getElementById('createAgriUserError');
const createAgriCsvError = document.getElementById('createAgriCsvError');
const modalTabs = document.querySelectorAll('.modal-tab');
const modeManual = document.getElementById('modeManual');
const modeCsv = document.getElementById('modeCsv');
const modalButtonMode = document.getElementById('modal-button-mode');

let editMode = false;
let currentModalMode = 'manual';
const visibleColumns = ['crop_nom', 'surface', 'engrais', 'phyto', 'A', 'B', 'C'];
const columnLabels = {
    crop_nom: 'Culture',
    surface: 'Surface',
    engrais: 'Engrais',
    phyto: 'Phyto',
    A: 'A',
    B: 'B',
    C: 'C'
};

if (modifyAllBtn) modifyAllBtn.addEventListener('click', () => setEditMode(true));
if (saveAllBtn) saveAllBtn.addEventListener('click', saveAllData);
if (createAgriUserBtn) createAgriUserBtn.addEventListener('click', openCreateAgriUserModal);
if (downloadAgriTemplateBtn) downloadAgriTemplateBtn.addEventListener('click', downloadAgriTemplate);
if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeCreateAgriUserModal);
if (createAgriUserModal) createAgriUserModal.addEventListener('click', (e) => {
  if (e.target === createAgriUserModal) closeCreateAgriUserModal();
});
if (createAgriUserForm) createAgriUserForm.addEventListener('submit', handleCreateAgriUserSubmit);
if (createAgriCsvForm) createAgriCsvForm.addEventListener('submit', handleCreateAgriCsvSubmit);
modalTabs.forEach(tab => {
  tab.addEventListener('click', (e) => switchModalMode(e.target.dataset.mode));
});

document.addEventListener('DOMContentLoaded', () => {
    // load all data (non-admin users)
    ajax_req('GET', 'php/request.php/data_agri_all/', reactDataAll, '');
});

modalButtonMode.addEventListener('click', () => {
    if (currentModalMode === 'manual') {
        switchModalMode('csv');
    } else {
        switchModalMode('manual');
    }
});


function reactDataAll(data) {
    if (!Array.isArray(data) || data.length === 0) {
        if (feedbackBox) feedbackBox.textContent = 'Aucune donnée à afficher.';
        return;
    }

    // group by user_id
    const grouped = {};
    data.forEach(row => {
        const uid = row.user_id || row.user_id === 0 ? String(row.user_id) : 'unknown';
        if (!grouped[uid]) grouped[uid] = {
            user: {
                id: row.user_id,
                nom: row.user_nom,
                prenom: row.user_prenom,
                email: row.user_email || '',
                telephone: row.user_telephone || ''
            },
            rows: []
        };
        grouped[uid].rows.push(row);
    });

    // render
    seenoviaList.innerHTML = '';
    Object.keys(grouped).forEach(uid => {
        const group = grouped[uid];
        const container = document.createElement('div');
        container.className = 'seenovia-user';
        container.dataset.userId = group.user.id;
        container.dataset.userNom = group.user.nom;
        container.dataset.userPrenom = group.user.prenom;
        container.dataset.userEmail = group.user.email;
        container.dataset.userTelephone = group.user.telephone;

        const title = document.createElement('h3');
        title.className = 'seenovia-user-title';
        title.textContent = `${group.user.prenom || ''} ${group.user.nom || ''}`.trim() || `Utilisateur ${group.user.id}`;
        container.appendChild(title);

        const tableWrapper = document.createElement('div');
        tableWrapper.className = 'agri-table-wrapper';
        const table = document.createElement('table');
        table.className = 'agri-table';
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        visibleColumns.forEach(key => {
            const th = document.createElement('th');
            th.textContent = columnLabels[key] || key;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        group.rows.forEach(row => {
            const tr = document.createElement('tr');
            tr.dataset.linkId = row.id;
            tr.dataset.specId = row.spec_id;
            visibleColumns.forEach(key => {
                const td = document.createElement('td');
                td.className = `cell-${key}`;
                td.textContent = row[key] !== undefined ? row[key] : '';
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        table.appendChild(tbody);
        tableWrapper.appendChild(table);
        container.appendChild(tableWrapper);
        seenoviaList.appendChild(container);
    });

    if (feedbackBox) feedbackBox.textContent = '';
}

function setEditMode(enabled) {
    editMode = enabled;
    const rows = Array.from(document.querySelectorAll('#seenoviaList tbody tr'));
    rows.forEach(row => {
        visibleColumns.forEach(key => {
            if (key === 'crop_nom') return;
            const cell = row.querySelector(`.cell-${key}`);
            if (!cell) return;
            if (enabled) {
                const input = document.createElement('input');
                input.type = 'number';
                input.step = 'any';
                input.value = cell.textContent;
                input.className = 'agri-input-cell';
                cell.textContent = '';
                cell.appendChild(input);
            } else {
                const input = cell.querySelector('input');
                if (input) cell.textContent = input.value;
            }
        });
    });

    if (modifyAllBtn) modifyAllBtn.disabled = enabled;
    if (saveAllBtn) saveAllBtn.disabled = !enabled;
}

function saveAllData() {
    const rows = Array.from(document.querySelectorAll('#seenoviaList tbody tr'));
    const updates = [];
    rows.forEach(row => {
        const specId = row.dataset.specId;
        if (!specId) return;
        const update = { spec_id: Number(specId) };
        visibleColumns.forEach(key => {
            if (key === 'crop_nom') return;
            const cell = row.querySelector(`.cell-${key}`);
            const input = cell && cell.querySelector('input');
            if (input) update[key] = input.value === '' ? null : Number(input.value);
        });
        updates.push(update);
    });

    if (updates.length === 0) {
        if (feedbackBox) feedbackBox.textContent = 'Aucune modification à enregistrer.';
        return;
    }

    ajax_req('POST', 'php/request.php/update_agri/', handleSaveResponse, 'updates=' + encodeURIComponent(JSON.stringify(updates)));
}

function handleSaveResponse(data) {
    if (!data || data.error) {
        if (feedbackBox) { feedbackBox.textContent = data && data.error ? data.error : 'Erreur lors de la sauvegarde.'; feedbackBox.style.color = '#ff6f6f'; }
        return;
    }
    if (feedbackBox) { feedbackBox.textContent = data.message || 'Modification enregistrée.'; feedbackBox.style.color = '#607063'; }
    setEditMode(false);
    // reload data
    ajax_req('GET', 'php/request.php/data_agri/', reactDataAll, '');
}

function downloadAgriTemplate() {
    const formData = new FormData(createAgriUserForm);
    const values = Object.fromEntries(formData);

    const headers = [
        'nom',
        'prenom',
        'adresseMail',
        'telephone',
        'mdp',
        'admin',
        'Culture',
        'Surface',
        'Engrais',
        'Phyto',
        'A',
        'B',
        'C'
    ];

    // Fonction échappement CSV
    const escapeCsv = (value) =>
        `"${String(value || '').replace(/"/g, '""')}"`;

    // Cultures par défaut
    const cultures = ['Blé', 'Maïs', 'Colza'];

    // Nombre d'exemples d'agriculteurs dans le fichier
    const numberOfFarmers = 3;

    const csvRows = [];

    // Ajout des colonnes
    csvRows.push(headers.map(escapeCsv).join(';'));

    for (let farmerIndex = 0; farmerIndex < numberOfFarmers; farmerIndex++) {

        cultures.forEach((culture, cultureIndex) => {

            const row = [
                cultureIndex === 0
                    ? (values.nom || `ExempleNom${farmerIndex + 1}`)
                    : '',

                cultureIndex === 0
                    ? (values.prenom || `ExemplePrenom${farmerIndex + 1}`)
                    : '',

                cultureIndex === 0
                    ? (values.adresseMail || `exemple${farmerIndex + 1}@agri.fr`)
                    : '',

                cultureIndex === 0
                    ? (values.telephone || '+33 6 00 00 00 00')
                    : '',

                cultureIndex === 0
                    ? (values.mdp || 'motdepasse')
                    : '',

                cultureIndex === 0
                    ? 'false'
                    : '',

                culture,
                '0',
                '0',
                '0',
                '0',
                '0',
                '0'
            ];

            csvRows.push(row.map(escapeCsv).join(';'));
        });

        // Ligne vide entre chaque agriculteur
        csvRows.push('');
    }

    // UTF-8 BOM pour Excel
    const csvContent = '\uFEFF' + csvRows.join('\n');

    const blob = new Blob([csvContent], {
        type: 'text/csv;charset=utf-8;'
    });

    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = 'agri_template.csv';

    document.body.appendChild(a);
    a.click();

    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function parseCsv(text) {
    const lines = text
        .replace(/\r/g, '')
        .split('\n')
        .filter(line => line.trim() !== '');

    if (lines.length < 2) return [];

    const headers = lines[0]
        .split(';')
        .map(h => h.replace(/"/g, '').trim());

    const users = [];
    let currentUser = null;

    for (let i = 1; i < lines.length; i++) {

        const values = lines[i]
            .split(';')
            .map(v => v.replace(/"/g, '').trim());

        const row = {};
        headers.forEach((header, index) => {
            row[header] = values[index] || '';
        });

        // Nouvelle personne
        if (row.nom !== '') {

            currentUser = {
                nom: row.nom,
                prenom: row.prenom,
                adresseMail: row.adresseMail,
                telephone: row.telephone,
                mdp: row.mdp,
                cultures: []
            };

            users.push(currentUser);
        }

        if (!currentUser) continue;

        // Culture
        if (row.Culture !== '') {

            currentUser.cultures.push({
                Culture: row.Culture,
                Surface: row.Surface,
                Engrais: row.Engrais,
                Phyto: row.Phyto,
                A: row.A,
                B: row.B,
                C: row.C
            });
        }
    }

    return users;
}

// Functions for creating agri user modal
function openCreateAgriUserModal() {
    if (createAgriUserModal) {
        createAgriUserModal.classList.add('active');
        currentModalMode = 'manual';
        switchModalMode('manual');
        createAgriUserForm.reset();
        createAgriCsvForm.reset();
        if (createAgriUserError) createAgriUserError.textContent = '';
        if (createAgriCsvError) createAgriCsvError.textContent = '';
    }
}

function closeCreateAgriUserModal() {
    if (createAgriUserModal) {
        createAgriUserModal.classList.remove('active');
        createAgriUserForm.reset();
        createAgriCsvForm.reset();
        if (createAgriUserError) createAgriUserError.textContent = '';
        if (createAgriCsvError) createAgriCsvError.textContent = '';
    }
}

function switchModalMode(mode) {
    currentModalMode = mode;
    
    // Update tabs
    modalTabs.forEach(tab => {
        if (tab.dataset.mode === mode) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
    
    // Update mode sections
    if (mode === 'manual') {
        if (modeManual) modeManual.classList.add('active');
        if (modeCsv) modeCsv.classList.remove('active');
    } else if (mode === 'csv') {
        if (modeManual) modeManual.classList.remove('active');  
        if (modeCsv) modeCsv.classList.add('active');
    }
}

function handleCreateAgriUserSubmit(e) {
    e.preventDefault();
    
    if (createAgriUserError) createAgriUserError.textContent = '';
    
    const formData = new FormData(createAgriUserForm);
    const data = Object.fromEntries(formData);
    
    // Validation
    if (!data.nom || !data.nom.trim()) {
        if (createAgriUserError) createAgriUserError.textContent = 'Le nom est requis.';
        return;
    }
    if (!data.prenom || !data.prenom.trim()) {
        if (createAgriUserError) createAgriUserError.textContent = 'Le prénom est requis.';
        return;
    }
    if (!data.adresseMail || !data.adresseMail.trim()) {
        if (createAgriUserError) createAgriUserError.textContent = 'L\'email est requis.';
        return;
    }
    if (!data.mdp || !data.mdp.trim()) {
        if (createAgriUserError) createAgriUserError.textContent = 'Le mot de passe est requis.';
        return;
    }
    
    // Send data
    ajax_req('POST', 'php/request.php/create_agri_user/', handleCreateAgriUserResponse, 'userData=' + encodeURIComponent(JSON.stringify(data)));
}

function handleCreateAgriUserResponse(data) {
    const errorElement = currentModalMode === 'manual' ? createAgriUserError : createAgriCsvError;
    
    if (!data || data.error) {
        if (errorElement) errorElement.textContent = data && data.error ? data.error : 'Erreur lors de la création du compte.';
        return;
    }
    
    if (feedbackBox) {
        feedbackBox.textContent = data.message || 'Compte(s) agri créé(s) avec succès.';
        feedbackBox.style.color = '#607063';
    }
    closeCreateAgriUserModal();
    // Reload data
    ajax_req('GET', 'php/request.php/data_agri_all/', reactDataAll, '');
}

function handleCreateAgriCsvSubmit(e) {

    e.preventDefault();

    if (createAgriCsvError) {
        createAgriCsvError.textContent = '';
    }

    if (
        !agriCsvFileInput ||
        !agriCsvFileInput.files ||
        agriCsvFileInput.files.length === 0
    ) {
        createAgriCsvError.textContent =
            'Veuillez sélectionner un fichier CSV.';
        return;
    }

    const file = agriCsvFileInput.files[0];
    const reader = new FileReader();

    reader.onload = (event) => {

        const text = event.target.result;

        const users = parseCsv(text);

        if (!Array.isArray(users) || users.length === 0) {

            createAgriCsvError.textContent =
                'Le fichier CSV est vide ou invalide.';
            return;
        }

        ajax_req(
            'POST',
            'php/request.php/create_agri_users/',
            handleCreateAgriUserResponse,
            'usersData=' + encodeURIComponent(JSON.stringify(users))
        );
    };

    reader.onerror = () => {

        createAgriCsvError.textContent =
            'Impossible de lire le fichier CSV.';
    };

    reader.readAsText(file, 'UTF-8');
}


function setTablePage(){
    window.location.href = 'table.php';
}

