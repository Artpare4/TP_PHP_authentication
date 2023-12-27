<?php

declare(strict_types=1);

use Authentication\Exception\NotLoggedInException;
use Authentication\UserAuthentication;
use Html\AppWebPage;

$authentication = new UserAuthentication();

$user = new UserAuthentication();

try {
    $res = $user->getUser();
} catch (NotLoggedInException) {
    $res = null;
}
// Un utilisateur est-il connecté ?
if (null == $res) {
    header('HTTP 1.1 302 Found');
    header('Location: http://localhost:8000/form.php ');
    exit; // Fin du programme
}
if (isset($_POST['logout'])) {
    $authentication->logoutIfRequested();
}
$title = 'Zone membre connecté';
$p = new AppWebPage($title);
$form = $authentication->logoutForm('form.php', 'zergfzer');
$p->appendContent(<<<HTML
        <div>Bonjour {$res->getLastName()}<br></div>
        <a href="user.php">{$res->getFirstName()}</a>
        <div>{$form}</div>
HTML
);

echo $p->toHTML();
