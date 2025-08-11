<?php

namespace App\Shared\Infrastructure\Database\ORM;

abstract class AbstractRepository
{
    /**
     * Полное имя класса сущности/документа, для которых предназначен репозиторий.
     *
     * @return string
     */
    abstract protected function getFQDN(): string;
}
