<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;

class UserAvatar
{
    private int $id;
    private ?string $avatar;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @throws EntityNotFoundException
     */
    public static function findById(int $userId): UserAvatar
    {
        $request = MyPdo::getInstance()->prepare(<<<SQL
    SELECT id,avatar FROM user WHERE id=:iduser;
SQL);
        $request->execute([':iduser' => $userId]);
        $res = $request->fetchObject(UserAvatar::class);
        if (!$res) {
            throw new EntityNotFoundException();
        }

        return $res;
    }

    public function setAvatar(?string $avatar): UserAvatar
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function save(): UserAvatar
    {
        $request = MyPdo::getInstance()->prepare(<<<SQL
        UPDATE user
        set avatar=:avatarUser
        WHERE id=:iduser;
SQL);
        $request->execute([':avatarUser' => $this->getAvatar(), ':iduser' => $this->getId()]);

        return $this;
    }
}
