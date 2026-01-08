<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odkrywaj</title>
           <link rel="stylesheet" type="text/css" href="public/styles/Questions.css">

        <link rel="stylesheet" type="text/css" href="public/styles/header.css">
    <link rel="stylesheet" type="text/css" href="public/styles/Discover.css">
</head>
<body>
    
    <?php if (isset($_GET['match'])): ?>
    <div class="match-banner" id="matchBanner">
        <div class="match-banner-content">
            <span class="heart">❤️</span>
            <p>Mamy to! Nowy Match!</p>
            <button onclick="closeMatch()">OK</button>
        </div>
    </div>

    <script>
        // Automatyczne ukrywanie po 5 sekundach
        setTimeout(() => {
            closeMatch();
        }, 5000);

        function closeMatch() {
            const banner = document.getElementById('matchBanner');
            banner.style.transform = 'translateY(-100%)';
            banner.style.opacity = '0';
            // Usuwamy parametr z URL bez odświeżania strony
            window.history.replaceState({}, document.title, "/discover");
        }
    </script>
<?php endif; ?>



    <?php
    $backgroundFilename = $user['background_picture'] ?: 'default-bg.jpg';
    $backgroundPath = "public/uploads/backgrounds/" . $backgroundFilename;
?>


    <?php include 'header.php'; ?>

     <div class="message">
        Odkrywaj
    </div>
    
<div class="discover-container" id="card-start">
    <?php if ($user) :?>
        <div class="card">
            <div class="card-header-bg" style="background-image: url('<?= $backgroundPath ?>');"></div>
            
            <div class="card-content">
                <div class="card-avatar-wrapper">
                    <img src="/public/uploads/avatars/<?= $user['profile_picture'] ?: 'default-avatar.png' ?>" alt="Profilowe" class="card-avatar">
                </div>

                <div class="profile-info-main">
                    <span class="profile-name"><?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?></span>
                    <span class="profile-location">📍 <?= htmlspecialchars($user['location'] ?: 'Lokalizacja nieznana') ?></span>
                </div>

                <div class="card-description">
                    <p><?= htmlspecialchars($user['description'] ?: 'Ten użytkownik nie dodał jeszcze opisu.') ?></p>
                </div>

                <div class="compatibility centered">
                    <?php if (isset($similarity) && $similarity !== null) : ?>
                        <div class="compatibility-bar">
                            <div class="compatibility-fill" style="width: <?= $similarity ?>%;"></div>
                        </div>
                        <div class="compatibility-percent"><?= $similarity ?>% podobieństwa</div>
                    <?php else: ?>
                        <div class="compatibility-empty">
                            <p>⚠️ Wypełnij ankiety, aby sprawdzić podobieństwo!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

            <form action="swipe" method="POST" class="card-buttons">
                <input type="hidden" name="target_id" value="<?= $user['id'] ?>">
                <button type="submit" name="action" value="dislike" class="reject">Nie</button>
                <button type="submit" name="action" value="maybe" class="maybe">Może</button>
                <button type="submit" name="action" value="like" class="accept">Bardzo tak</button>
            </form>
       <?php else: ?>
            <div class="no-more-users">
                <h2>To już wszyscy!</h2>
                <p>Wróć później, może pojawią się nowe osoby.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
