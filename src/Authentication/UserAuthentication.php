<?php

namespace Authentication;

use Authentication\Exception\AuthenticationException;
use Authentication\Exception\NotLoggedInException;
use Entity\Exception\EntityNotFoundException;
use Entity\User;
use Service\Exception\SessionException;
use Service\Session;

class UserAuthentication
{
    private const LOGIN_INPUT_NAME = 'login';
    private const PASSWORD_INPUT_NAME = 'password';
    private const SESSION_KEY = '__UserAuthentication__';
    private const SESSION_USER_KEY = 'user';
    private const LOGOUT_INPUT_NAME = 'logout';

    private ?User $user = null;

    public function loginForm(string $action, string $submitText = 'OK'): string
    {
        $login = self::LOGIN_INPUT_NAME;
        $password = self::PASSWORD_INPUT_NAME;
        $res = <<<HTML
    <form method="post" action="{$action}">
    <label>
    <input name="{$login}" type="text" placeholder="{$login}">
    </label>
    <label>
    <input name="{$password}" type="text" placeholder="pass">
    </label>
    <button type="submit">OK</button>
</form>
HTML;

        return $res;
    }

    /**
     * @throws AuthenticationException
     * @throws SessionException
     */
    public function getUserFromAuth(): User
    {
        $User = new User();
        try {
            $res = $User->findByCredentials($_POST['login'], $_POST['password']);
            $this->setUser($res);
        } catch (EntityNotFoundException $e) {
            throw new AuthenticationException();
        }

        return $res;
    }

    /**
     * @throws SessionException
     */
    protected function setUser(User $user): void
    {
        Session::start();
        $this->user = $user;
        $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] = $user;
    }

    /**
     * @throws SessionException
     */
    public function isUserConnected(): bool
    {
        Session::start();
        $res = false;
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]) && $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] instanceof User) {
            $res = true;
        }

        return $res;
    }

    public function logoutForm(string $action, string $text): string
    {
        $deco = self::LOGOUT_INPUT_NAME;

        return <<<HTML
    <form method="post" action="{$action}">
        <button name="{$deco}" type="submit" value="{$deco}">{$deco}</button>
    </form>
HTML;
    }

    public function logoutIfRequested(): void
    {
        $deco = self::LOGOUT_INPUT_NAME;
        Session::start();
        if (isset($_POST["{$deco}"])) {
            $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] = null;
            $this->user = null;
        }
    }

    /**
     * @throws NotLoggedInException
     */
    protected function getUserFromSession(): User
    {
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]) && $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] instanceof User) {
            $res = $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY];
        } else {
            throw new NotLoggedInException('Pas logged');
        }

        return $res;
    }

    public function __construct()
    {
        Session::start();
        try {
            $this->user = $this->getUserFromSession();
        } catch (NotLoggedInException) {
            $this->user = null;
        }
    }

    /**
     * @throws NotLoggedInException
     */
    public function getUser(): User
    {
        if (isset($this->user)) {
            $res = $this->user;
        } else {
            throw new NotLoggedInException('not logged');
        }

        return $res;
    }
}
