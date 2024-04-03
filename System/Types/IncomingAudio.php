<?php

namespace TeleBot\System\Types;

class IncomingAudio extends File
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
     * get file name
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->file['file_name'];
    }

    /**
     * get audio title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->file['title'] ?? null;
    }

    /**
     * get audio performer
     *
     * @return string|null
     */
    public function getPerformer(): ?string
    {
        return $this->file['performer'] ?? null;
    }

    /**
     * get audio duration in seconds
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->file['duration'];
    }

    /**
     * get audio mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->file['mime_type'];
    }

    /**
     * download audio
     *
     * @return string|null returns stored file name
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}