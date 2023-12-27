<?php

namespace Html;

use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Entity\UserAvatar;

class UserProfileWithAvatar extends UserProfile
{
    private const AVATAR_INPUT_NAME = 'avatar';
    private string $formAction;

    public function toHtml(): string
    {
        $const = self::AVATAR_INPUT_NAME;
        $res = parent::toHtml();
        $res .= (<<<HTML
            <form method="POST" action="{$this->formAction}" enctype="multipart/form-data">
                <label>
                    <input name="{$const}" type="file" accept="image/png">
                </label>
                <button type="submit">Mettre à jour</button>
            </form>
            <img src="avatar.php?id={$this->getUser()->getId()}"/>
        HTML);

        return $res;
    }

    public function __construct(User $user, string $formAction)
    {
        parent::__construct($user);
        $this->formAction = $formAction;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateAvatar(): bool
    {
        $const = self::AVATAR_INPUT_NAME;
        if (isset($_FILES["{$const}"]) && UPLOAD_ERR_OK == $_FILES["{$const}"]['error'] && $_FILES["{$const}"]['size'] > 0 && is_uploaded_file($_FILES["{$const}"]['tmp_name'])) {
            $imageAvatar = UserAvatar::findByID($this->getUser()->getId());
            $imageAvatar->setAvatar(file_get_contents($_FILES["{$const}"]['tmp_name']));
            $imageAvatar->save();
            unlink($_FILES["{$const}"]['tmp_name']);
            $res = true;
        } else {
            $res = false;
        }

        return $res;
    }
}
