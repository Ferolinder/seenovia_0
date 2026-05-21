// Admin view: list all farmers and their crops, editable, export SVG
const modifyAllBtn = document.getElementById('modifyAllBtn');
const saveAllBtn = document.getElementById('saveAllBtn');
const downloadSvgBtn = document.getElementById('downloadSvgBtn');
const seenoviaList = document.getElementById('seenoviaList');
const feedbackBox = document.getElementById('seenoviaFeedback');

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

if (modifyAllBtn) modifyAllBtn.addEventListener('click', () => setEditMode(true));
if (saveAllBtn) saveAllBtn.addEventListener('click', saveAllData);
if (downloadSvgBtn) downloadSvgBtn.addEventListener('click', downloadSvg);

document.addEventListener('DOMContentLoaded', () => {
    // load all data (non-admin users)
    ajax_req('GET', 'php/request.php/data_agri_all/', reactDataAll, '');
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
        if (!grouped[uid]) grouped[uid] = { user: { id: row.user_id, nom: row.user_nom, prenom: row.user_prenom }, rows: [] };
        grouped[uid].rows.push(row);
    });

    // render
    seenoviaList.innerHTML = '';
    Object.keys(grouped).forEach(uid => {
        const group = grouped[uid];
        const container = document.createElement('div');
        container.className = 'seenovia-user';
        container.dataset.userId = group.user.id;

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

function downloadSvg() {
    const container = document.getElementById('seenoviaList');
    if (!container) return;
    // clone and inline minimal styles
    const clone = container.cloneNode(true);
    const wrapper = document.createElement('div');
    wrapper.appendChild(clone);
    const serialized = new XMLSerializer().serializeToString(wrapper);

    const width = wrapper.scrollWidth || 1200;
    const height = wrapper.scrollHeight || 800;
    const svg = `<?xml version="1.0" encoding="UTF-8"?>\n<svg xmlns='http://www.w3.org/2000/svg' width='${width}' height='${height}'>\n  <foreignObject width='100%' height='100%'>\n    ${serialized}\n  </foreignObject>\n</svg>`;

    const blob = new Blob([svg], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'seenovia_table.svg';
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
}
