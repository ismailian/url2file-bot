<?php

namespace TeleBot\System\Types;

class IncomingVideo extends File
{

    /**
     * get file id by index
     *
     * @return string
     */
    public function getFileId(): string
    {
        return $this->file['file_id'];
    }

    /**
     * get video duration in seconds
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->file['duration'];
    }

    /**
     * get video resolution
     *
     * @return string
     */
    public function getResolution(): string
    {
        return $this->file['height'];
    }

    /**
     * get video mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->file['mime_type'];
    }

    /**
     * download video
     *
     * @return string|null returns stored file name
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}