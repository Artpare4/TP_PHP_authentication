<?php

namespace Service;

use Service\Exception\SessionException;

class Session
{
    /**
     * @throws SessionException
     */
    public static function start(): void
    {
        if (2 != session_status()) {
            if (0 == session_status()) {
                throw new SessionException('La session est désactivée');
            } elseif (1 == session_status()) {
                if (headers_sent()) {
                    throw new SessionException();
                } else {
                    $test = session_start();
                    if (!$test) {
                        throw new SessionException();
                    }
                }
            } else {
                throw new SessionException();
            }
        }
    }
}
