<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;

class User
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $login;
    private string $phone;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @throws EntityNotFoundException
     */
    public static function findByCredentials(string $login, string $password): User
    {
        $request = MyPdo::getInstance()->prepare(<<<SQL
    SELECT id,lastName,firstName,login,phone FROM user
        WHERE login=:log AND sha512pass=SHA2(:mdp,512);
SQL);
        $request->execute([':log' => $login, ':mdp' => $password]);
        $res = $request->fetchObject(User::class);
        if (!$res) {
            throw new EntityNotFoundException();
        }

        return $res;
    }
}
