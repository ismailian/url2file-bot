<?php

namespace TeleBot\System\Types;

class Thumbnail extends File
{

    /**
     * get file id
     *
     * @return string
     */
    public function getFileId(): string
    {
        return $this->file['file_id'];
    }

    /**
     * get thumbnail width
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->file['width'];
    }

    /**
     * get thumbnail height
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->file['height'];
    }

    /**
     * download thumbnail
     *
     * @return string|null
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}