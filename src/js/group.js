document.getElementById("group_link").classList.add("active");
window.onload = () => {
    console.log("group.js cloaded");
    getGroups();
    getUsers();
    getManagers();
    console.log("data received");
};

document.addEventListener("DOMContentLoaded", () => {

    // ===== Modales =====
    const createGroupModal = document.getElementById("create_group_modal");
    const manageGroupModal = document.getElementById("manage_group_modal");

    // ===== Boutons ouverture =====
    const createGroupBtn = document.getElementById("create_group_btn");

    // ===== Boutons fermeture =====
    const closeCreateGroupBtn = document.getElementById("close_create_group_modal");
    const closeManageGroupBtn = document.getElementById("close_manage_group_modal");

    // -------------------------
    // Ouvrir création groupe
    // -------------------------
    createGroupBtn.addEventListener("click", () => {
        createGroupModal.style.display = "flex";
    });

    // -------------------------
    // Fermer création groupe
    // -------------------------
    closeCreateGroupBtn.addEventListener("click", () => {
        createGroupModal.style.display = "none";
    });

    // -------------------------
    // Fermer gestion groupe
    // -------------------------
    closeManageGroupBtn.addEventListener("click", () => {
        manageGroupModal.style.display = "none";
    });

    // -------------------------
    // Fermer en cliquant dehors
    // -------------------------
    window.addEventListener("click", (event) => {

        if (event.target === createGroupModal) {
            createGroupModal.style.display = "none";
        }

        if (event.target === manageGroupModal) {
            manageGroupModal.style.display = "none";
        }

    });

});


/**
 * Ouvre la modal de gestion d'un groupe
 * @param {number} groupId
 * @param {string} groupName
 */
function openManageGroupModal(groupId, groupName) {

    const modal = document.getElementById("manage_group_modal");
    const title = document.getElementById("manage_group_title");

    title.textContent = `Gérer le groupe : ${groupName}`;

    // Stockage éventuel de l'id du groupe
    modal.dataset.groupId = groupId;

    modal.style.display = "flex";
}


/* FONCTION AJAX POUR LES GROUPES */

// ==========================================
// USERS
// ==========================================

function getUsers() {
    ajax_req(
        "GET",
        "php/request.php/users/",
        react_getUsers,
        ""
    );
}

function react_getUsers(data) {
    console.log("getUsers :", data);

    const container = document.getElementById("user_list_container");

    container.innerHTML = "";

    data.forEach(user => {

        const card = document.createElement("div");

        card.className = "user-card";
        card.draggable = true;

        card.dataset.userId = user.id;
        card.dataset.isAdmin = user.admin ? "1" : "0";

        card.innerHTML = `
            <div class="user-card-name">
                ${user.prenom} ${user.nom}
            </div>
        `;

        card.addEventListener("dragstart", (e) => {

            e.dataTransfer.setData(
                "userId",
                user.id
            );

            e.dataTransfer.setData(
                "isAdmin",
                user.admin ? "1" : "0"
            );

            e.dataTransfer.effectAllowed = "move";

            card.classList.add("dragging");
        });

        card.addEventListener("dragend", () => {
            card.classList.remove("dragging");
        });

        container.appendChild(card);
    });
}

// ==========================================
// MANAGERS
// ==========================================

function getManagers() {
    ajax_req(
        "GET",
        "php/request.php/managers/",
        react_getManagers,
        ""
    );
}

function react_getManagers(data) {
    console.log("getManagers :", data);

    const container = document.getElementById("manager_list_container");

    container.innerHTML = "";

    data.forEach(user => {

        const card = document.createElement("div");

        card.className = "user-card";
        card.draggable = true;

        card.dataset.userId = user.id;
        card.dataset.isAdmin = "1";

        card.innerHTML = `
            <div class="user-card-name">
                👑 ${user.prenom} ${user.nom}
            </div>
        `;

        card.addEventListener("dragstart", (e) => {

            e.dataTransfer.setData(
                "userId",
                user.id
            );

            e.dataTransfer.setData(
                "isAdmin",
                "1"
            );

            e.dataTransfer.effectAllowed = "move";

            card.classList.add("dragging");
        });

        card.addEventListener("dragend", () => {
            card.classList.remove("dragging");
        });

        container.appendChild(card);
    });
}

// ==========================================
// USER SEARCH
// ==========================================

document.addEventListener("DOMContentLoaded", () => {

    document
        .getElementById("user_research_input")
        .addEventListener(
            "input",
            handleSearchUser
        );

});

function handleSearchUser() {
    const search = document
        .getElementById("user_research_input")
        .value
        .trim()
        .toLowerCase();

    const users = document.querySelectorAll(".user-card");

    users.forEach(user => {

        const userNameInput =
            user.querySelector(".user-card-name");

        const userName =
            userNameInput.textContent.toLowerCase();

        if (userName.includes(search)) {

            user.classList.remove("user-hidden");

            setTimeout(() => {
                user.style.display = "";
            }, 10);

        } else {

            user.classList.add("user-hidden");

            setTimeout(() => {

                if (
                    user.classList.contains("user-hidden")
                ) {
                    user.style.display = "none";
                }

            }, 250);
        }
    });
}


// ==========================================
// GROUPS
// ==========================================

function getGroups() {
    ajax_req(
        "GET",
        "php/request.php/groups/",
        react_getGroups,
        ""
    );
}

function react_getGroups(data) {
    console.log("getGroups :", data);

    const container = document.getElementById("group_list");
    container.innerHTML = "";

    const groups = {};

    data.forEach(row => {

        if (!groups[row.id]) {
            groups[row.id] = {
                id: row.id,
                nom: row.nom,
                managers: [],
                members: []
            };
        }

        const user = {
            id: row.user_id,
            nom: row.user_nom,
            prenom: row.user_prenom
        };

        if (row.is_manager == 1)
            groups[row.id].managers.push(user);
        else
            groups[row.id].members.push(user);
    });

    Object.values(groups).forEach(group => {

        const card = document.createElement("div");

        card.className = "group-card";
        card.dataset.groupId = group.id;

        // Liste des ids déjà présents
        card.dataset.users = JSON.stringify([
            ...group.managers.map(u => String(u.id)),
            ...group.members.map(u => String(u.id))
        ]);

        card.innerHTML = `
            <div class="group-header">

                <div class="group-name-container">
                    <input
                        type="text"
                        class="group-name-input"
                        value="${group.nom}"
                    >

                    <button
                        class="group-confirm-btn"
                        onclick="changeGroupName(
                            ${group.id},
                            this.previousElementSibling.value
                        )"
                    >
                        ✓
                    </button>
                </div>

                <button
                    class="group-contact-btn"
                    onclick="alert('TODO : contacter le groupe')"
                >
                    Contacter le groupe
                </button>

            </div>

            <div class="group-section">
                <h4>Responsables</h4>
                <div class="group-managers"></div>
            </div>

            <div class="group-section">
                <h4>Membres</h4>
                <div class="group-members"></div>
            </div>
        `;

        const managersContainer =
            card.querySelector(".group-managers");

        const membersContainer =
            card.querySelector(".group-members");

        // ==========================
        // RESPONSABLES
        // ==========================

        group.managers.forEach(manager => {

            const div = document.createElement("div");

            div.className = "group-member manager";

            div.innerHTML = `
                <span>
                    👑 ${manager.prenom} ${manager.nom}
                </span>

                <button
                    class="member-remove-btn"
                    onclick="
                        alert(
                            'TODO : retirer le responsable ${manager.prenom} ${manager.nom}'
                        )
                    "
                >
                    ✕
                </button>
            `;

            managersContainer.appendChild(div);
        });

        // ==========================
        // MEMBRES
        // ==========================

        group.members.forEach(member => {

            const div = document.createElement("div");

            div.className = "group-member";

            div.innerHTML = `
                <span>
                    ${member.prenom} ${member.nom}
                </span>

                <button
                    class="member-remove-btn"
                    onclick="
                        alert(
                            'TODO : retirer ${member.prenom} ${member.nom}'
                        )
                    "
                >
                    ✕
                </button>
            `;

            membersContainer.appendChild(div);
        });

        // ==========================
        // DRAG & DROP
        // ==========================

        card.addEventListener("dragenter", (e) => {
            e.preventDefault();
            card.classList.add("drag-hover");
        });

        card.addEventListener("dragover", (e) => {
            e.preventDefault();
            card.classList.add("drag-hover");
        });

        card.addEventListener("dragleave", () => {
            card.classList.remove("drag-hover");
        });

        card.addEventListener("drop", (e) => {

            e.preventDefault();

            card.classList.remove("drag-hover");

            const userId =
                e.dataTransfer.getData("userId");

            const isAdmin =
                e.dataTransfer.getData("isAdmin");

            const groupId =
                card.dataset.groupId;

            const existingUsers =
                JSON.parse(card.dataset.users);

            // Empêche les doublons
            if (existingUsers.includes(userId)) {

                console.log(
                    "Utilisateur déjà présent dans le groupe"
                );

                return;
            }

            if (isAdmin === "1") {

                addManagerToGroup(
                    userId,
                    groupId
                );

            } else {

                addUserToGroup(
                    userId,
                    groupId
                );
            }
        });

        container.appendChild(card);
    });
}

// ==========================================
// GROUP SEARCH
// ==========================================

document.addEventListener("DOMContentLoaded", () => {

    document
        .getElementById("group_research_input")
        .addEventListener(
            "input",
            handleSearchGroup
        );

});

function handleSearchGroup() {

    const search = document
        .getElementById("group_research_input")
        .value
        .trim()
        .toLowerCase();

    const groups = document.querySelectorAll(".group-card");

    groups.forEach(group => {

        const groupNameInput =
            group.querySelector(".group-name-input");

        const groupName =
            groupNameInput.value.toLowerCase();

        if (groupName.includes(search)) {

            group.classList.remove("group-hidden");

            setTimeout(() => {
                group.style.display = "";
            }, 10);

        } else {

            group.classList.add("group-hidden");

            setTimeout(() => {

                if (
                    group.classList.contains("group-hidden")
                ) {
                    group.style.display = "none";
                }

            }, 250);
        }
    });
}


// ==========================================
// CREATE GROUP
// ==========================================

function createGroup(groupName) {
    ajax_req(
        "POST",
        "php/request.php/groups/",
        react_createGroup,
        "group_name=" + encodeURIComponent(groupName)
    );
}

function react_createGroup(data) {
    console.log("createGroup :", data);
}


// ==========================================
// ADD USER TO GROUP
// ==========================================

function addUserToGroup(userId, groupId) {
    const payload = JSON.stringify({
        groupId: groupId,
        userId: userId
    });

    ajax_req(
        "PUT",
        "php/request.php/add_user_to_group/",
        react_addUserToGroup,
        payload
    );
}

function react_addUserToGroup(data) {
    console.log("addUserToGroup :", data);
}


// ==========================================
// ADD MANAGER TO GROUP
// ==========================================

function addManagerToGroup(userId, groupId) {
    const payload = JSON.stringify({
        groupId: groupId,
        userId: userId
    });

    ajax_req(
        "PUT",
        "php/request.php/add_manager_to_group/",
        react_addManagerToGroup,
        payload
    );
}

function react_addManagerToGroup(data) {
    console.log("addManagerToGroup :", data);
}


// ==========================================
// REMOVE USER FROM GROUP
// ==========================================

function removeUserFromGroup(userId, groupId) {

    const payload = JSON.stringify({
        userId: userId,
        groupId: groupId
    });

    ajax_req(
        "PUT",
        "php/request.php/remove_user_from_group/",
        react_removeUserFromGroup,
        'groupId='+groupId + '&userId='+userId
    );
}

function react_removeUserFromGroup(data) {
    console.log("removeUserFromGroup :", data);
}


// ==========================================
// CHANGE GROUP NAME
// ==========================================

function changeGroupName(groupId, newName) {

    const payload = JSON.stringify({
        group_d: groupId,
        new_name: newName
    });

    ajax_req(
        "PUT",
        "php/request.php/change_group_name/",
        react_changeGroupName,
        payload
    );
}

function react_changeGroupName(data) {
    console.log("changeGroupName :", data);
}


// ==========================================
// DELETE GROUP
// ==========================================

function deleteGroup(groupId) {

    const payload = JSON.stringify({
        group_id: groupId
    });

    ajax_req(
        "PUT",
        "php/request.php/delete_group/",
        react_deleteGroup,
        payload
    );
}

function react_deleteGroup(data) {
    console.log("deleteGroup :", data);
}


