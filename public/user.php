<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Html\AppWebPage;
use Html\UserProfileWithAvatar;

$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

try {
    // Tentative de connexion
    $user = $authentication->getUser();
    // Si connexion réussie, affichage du profil
    $userProfile = new UserProfileWithAvatar($user, $_SERVER['PHP_SELF']);
    $userProfile->updateAvatar();
    $p->appendContent(<<<HTML
    <div class="user">
HTML);
    $information = $userProfile->toHtml();
    $p->appendContent(<<<HTML
    {$information}
HTML);
    $p->appendContent(<<<HTML
    </div>
HTML);
} catch (NotLoggedInException $e) {
    // Redirection vers le formulaire si on est connecté
    header('HTTP 1.1 302 Found');
    header('Location: http://localhost:8000/form.php ');
} catch (Exception $e) {
    $p->appendContent("Un problème est survenu&nbsp;: {$e->getMessage()}");
}

// Envoi du code HTML au navigateur du client
echo $p->toHTML();
