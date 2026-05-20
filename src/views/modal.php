<div id="modal">
    <div id="connexion-modal">
        <div id="top">
            <p>CONNECTION</p>
            <span id="cross">X</span>
        </div>
        <div class="input">
            <img src="image/username.png">
            <input type="text" placeholder="Enter your username" class="username" required>
        </div>
        <div class="input">
            <img src="image/password.png">
            <input type="password" placeholder="Enter your password" class="password" required>
        </div>
        <a class="button-modal" id="buttonConnect">CONNECT</a>
        <div class="error"></div>
    </div>

    <div id="inscription-modal">
        <div id="top">
            <p>REGISTRATION</p>
            <span id="cross2">X</span>
        </div>
        <div class="input">
            <img src="image/username.png">
            <input type="text" placeholder="Enter your username" class="username" required>
        </div>
        <div class="input">
            <img src="image/password.png"> 
            <input type="password" placeholder="Enter your password" class="password" required>
        </div>
        <div class="input">
            <img src="image/password.png"> 
            <input type="password" placeholder="Confirm your password" class="password" required>
        </div>
        <a class="button-modal" id="buttonRegister">REGISTER</a>
        <div class="error"></div>
    </div>

    <div id="modalgraph">
        <div id="top">
            <span id="cross3">X</span>
            <a href="#" class="button-modal2" id="graph">GRAPH</a>
            <a href="#" class="button-modal2" id="table">TABLE</a>
            <a href="#" class="button-modal2" id="info_box">INFOS</a>
            <a href="#" class="button-modal2" id="bottle">BOTTLES</a>
        </div>
        <div id="result">
        </div>
    </div>

    <div id="modalmodify">
        <div id="top">
            <span id="cross4">X</span>
        </div>
        <div id="modify">
            <select class="selectedDepth">
                <option selected>Choose depth</option>
            </select>
            <select class="selectedTime">
                <option selected>Choose duration</option>
            </select>
            <input type="date" class="date"/>
            <div>
                <input type="checkbox" class="private"/>
                <label for="scales">PRIVATE</label>
            </div>
            <div id="info">
            </div>
            <a class="button-modal" id="buttonModify">MODIFY</a>
        </div>
    </div>
</div>
