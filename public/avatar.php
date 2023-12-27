<?php

declare(strict_types=1);

use Entity\Exception\EntityNotFoundException;
use Entity\UserAvatar;

try {
    if (!isset($_GET['id'])) {
        throw new Exception('The id is not define');
    }

    $userId = (int) $_GET['id'];
    $userAvatar = new UserAvatar();
    $avatar = $userAvatar->findById($userId);
    $displayAvatar = $avatar->getAvatar();
} catch (EntityNotFoundException|Exception) {
    $displayAvatar = file_get_contents('img/default_avatar.png');
}
header('Content-Type: image/png');
echo $displayAvatar;
