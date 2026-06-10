<div id="content_group" class="content">

    <section id="user_research">
        <div id="user_research_container">
            <input type="text" id="user_research_input" placeholder="Rechercher un utilisateur">
        </div>
        <div id="manager_list">
            <h2>Managers</h2>
            <div id="manager_list_container">
            </div>
        </div>
        <div id="user_list">
            <h2>Agriculteurs</h2>
            <div id="user_list_container">
            </div>
        </div>
    </section>

    <section id="group_research">
        <div id="group_research_container">
            <input type="text" id="group_research_input" placeholder="Rechercher un groupe">
            <button id="create_group_btn" class="agri-button" style="margin-left: auto;">Créer un groupe</button>
        </div>
        <div id="group_list">
        </div>
    </section>

    <section id="modals">
        <!-- Modal de création de groupe -->
        <div id="create_group_modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close_create_group_modal">&times;</span>
                <h2>Créer un groupe de données</h2>
                <input type="text" id="new_group_name" placeholder="Nom du groupe">
                <button id="confirm_create_group_btn" class="agri-button">Créer</button>
            </div>
        </div>
        <!-- Modal de gestion d'un groupe -->
        <div id="manage_group_modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close_manage_group_modal">&times;</span>
                <h2 id="manage_group_title">Gérer le groupe</h2>
                <div id="group_users_list">
                    <!-- Liste des utilisateurs du groupe -->
                </div>
                <button id="add_user_to_group_btn" class="agri-button">Ajouter un utilisateur</button>
            </div>
        </div>
    </section>
</div>