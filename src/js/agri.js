const USER_ID = getUserIdFromUrl();

const modifyRowsBtn = document.getElementById('modifyRowsBtn');
const saveRowsBtn = document.getElementById('saveRowsBtn');
const feedbackBox = document.getElementById('agriFeedback');

let editMode = false;
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

if (modifyRowsBtn) {
    modifyRowsBtn.addEventListener('click', () => setEditMode(true));
}

if (saveRowsBtn) {
    saveRowsBtn.addEventListener('click', saveAgriData);
}

document.addEventListener('DOMContentLoaded', () => {
    if (USER_ID) {
        addAggriData(USER_ID);
    }
});

function getUserIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const user = params.get('user');
    return user ? Number(user) : null;
}

function addAggriData(userId = null) {
    const id = userId || USER_ID;
    if (!id || Number.isNaN(id)) {
        return;
    }

    ajax_req(
        'GET',
        'php/request.php/data_agri/',
        reactDataAgri,
        '&id_user=' + encodeURIComponent(id)
    );
}

function reactDataAgri(data) {
    const greeting = document.getElementById('agriGreeting');
    const table = document.getElementById('data_agri');
    const feedback = document.getElementById('agriFeedback');

    if (!greeting || !table) {
        return;
    }

    if (!Array.isArray(data) || data.length === 0) {
        greeting.textContent = 'Bonjour agriculteur';
        table.innerHTML = '<tr><td>Aucune donnée disponible pour ce profil.</td></tr>';
        toggleEditButtons(false);
        if (feedback) {
            feedback.textContent = 'Aucune donnée agricole trouvée pour cet utilisateur.';
        }
        return;
    }

    const first = data[0];
    const prenom = first.user_prenom || first.prenom || '';
    const nom = first.user_nom || first.nom || '';
    greeting.textContent = `Bonjour ${prenom} ${nom}`.trim();

    table.innerHTML = '';
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
    data.forEach(row => {
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
    toggleEditButtons(true);
    if (feedback) {
        feedback.textContent = '';
    }
}

function setEditMode(enabled) {
    editMode = enabled;
    const table = document.getElementById('data_agri');
    if (!table) {
        return;
    }

    const rows = Array.from(table.querySelectorAll('tbody tr'));
    rows.forEach(row => {
        visibleColumns.forEach(key => {
            if (key === 'crop_nom') {
                return;
            }
            const cell = row.querySelector(`.cell-${key}`);
            if (!cell) {
                return;
            }
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
                if (input) {
                    cell.textContent = input.value;
                }
            }
        });
    });

    if (modifyRowsBtn) {
        modifyRowsBtn.disabled = enabled;
    }
    if (saveRowsBtn) {
        saveRowsBtn.disabled = !enabled;
    }
}

function toggleEditButtons(enabled) {
    if (modifyRowsBtn) {
        modifyRowsBtn.disabled = !enabled;
    }
    if (saveRowsBtn) {
        saveRowsBtn.disabled = true;
    }
}

function saveAgriData() {
    const table = document.getElementById('data_agri');
    const feedback = document.getElementById('agriFeedback');
    if (!table) {
        return;
    }

    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const updates = [];

    rows.forEach(row => {
        const specId = row.dataset.specId;
        if (!specId) {
            return;
        }

        const update = { spec_id: Number(specId) };
        visibleColumns.forEach(key => {
            if (key === 'crop_nom') {
                return;
            }
            const cell = row.querySelector(`.cell-${key}`);
            const input = cell && cell.querySelector('input');
            if (input) {
                update[key] = input.value === '' ? null : Number(input.value);
            }
        });

        updates.push(update);
    });

    if (updates.length === 0) {
        if (feedback) {
            feedback.textContent = 'Aucune modification à enregistrer.';
        }
        return;
    }

    ajax_req(
        'POST',
        'php/request.php/update_agri/',
        handleUpdateResponse,
        'updates=' + encodeURIComponent(JSON.stringify(updates))
    );
}

function handleUpdateResponse(data) {
    const feedback = document.getElementById('agriFeedback');
    if (!feedback) {
        return;
    }

    if (!data || data.error) {
        feedback.textContent = data && data.error ? data.error : 'Erreur lors de la sauvegarde.';
        feedback.style.color = '#ff6f6f';
        return;
    }

    feedback.textContent = data.message || 'Modification enregistrée.';
    feedback.style.color = '#d4f8d4';
    setEditMode(false);
    addAggriData(USER_ID);
}
