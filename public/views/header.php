<header>
    <div class="logo">😊 Opinio</div>

    <div class="menu">
        <a href="/questions"><button>Pytania</button></a>
        <a href="/friends"><button>Znajomi</button></a>
        <a href="/discover"><button>Odkrywaj</button></a>

        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="/adminPanel" class="admin-link">PANEL ADMINA</a>
        <?php endif; ?>
    </div>

    <div class="icons">
        <div class="settings-wrapper">
            <button id="settingsBtn" class="settings">⚙️</button>
            <ul id="settingsDropdown" class="dropdown">
                <li><a href="/logout">Wyloguj</a></li>
                <li>
                    <button id="themeBtn">Zmień styl</button>
                </li>
            </ul>
        </div>
        <a href="/profile" class="profile">👤</a>
    </div>

    <script src="/public/scripts/header.js"></script>
    <script src="/public/scripts/themes.js"></script>
</header>