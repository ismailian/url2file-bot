<?php

namespace TeleBot\System\Interfaces;

interface IFile
{

    /**
     * get file temporary link
     *
     * @param string|int $idOrIndex
     * @return string|null
     */
    public function getLink(string|int $idOrIndex): ?string;

    /**
     * download context file
     *
     * @param string|null $filename name of the file, defaults to remote filename
     * @return string|null
     */
    public function saveAs(string $filename = null): ?string;

    /**
     * get file size
     *
     * @param bool $readable
     * @return int|string
     */
    public function getSize(bool $readable = false): int|string;

}