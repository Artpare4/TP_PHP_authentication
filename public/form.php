<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Html\AppWebPage;

// Création de l'authentification
$authentication = new UserAuthentication();

$p = new AppWebPage('Authentification');

// Production du formulaire de connexion
$p->appendCSS(<<<CSS
    form input {
        width : 4em ;
    }
CSS
);

$user = new UserAuthentication();

if (isset($_POST['logout'])) {
    $authentication->logoutIfRequested();
    $res = null;
} else {
    try {
        $res = $user->getUser();
    } catch (NotLoggedInException) {
        $res = null;
    }
}

if (null == $res) {
    $form = $authentication->loginForm('auth.php');
    $p->appendContent(<<<HTML
        {$form}
        <p>Pour faire un test : essai/toto
    HTML
    );
} else {
    $form = $authentication->logoutForm('form.php', 'zergfzer');
    $p->appendContent(<<<HTML
<div>Bonjour {$res->getFirstName()}
    {$form}</div>
HTML
    );
}

echo $p->toHTML();
