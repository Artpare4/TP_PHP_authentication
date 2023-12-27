<?php

namespace Html;

use Entity\User;

class UserProfile
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function toHtml(): string
    {
        $webPage = new WebPage();
        $res = (<<<HTML
    <div class="information">
        <p>Nom : {$webPage->escapeString($this->user->getLastName())}</p>
        <p>Prénom : {$webPage->escapeString($this->user->getFirstName())}</p>
        <p>Login  :  {$webPage->escapeString($this->user->getLogin())}</p>
        <p>Téléphone : {$webPage->escapeString($this->user->getPhone())}</p>
    </div>
HTML);

        return $res;
    }
}
