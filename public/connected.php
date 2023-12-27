<?php

declare(strict_types=1);

use Authentication\UserAuthentication;
use Html\AppWebPage;

$authentication = new UserAuthentication();

// Un utilisateur est-il connecté ?
if (!$authentication->isUserConnected()) {
    header('HTTP 1.1 302 Found');
    header('Location: http://localhost:8000/form.php ');
    exit; // Fin du programme
}

$title = 'Zone membre connecté';
$p = new AppWebPage($title);

$p->appendContent(<<<HTML
        <h1>$title</h1>
        <h2>Page 1</h2>
HTML
);

echo $p->toHTML();
