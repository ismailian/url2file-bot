<?php

namespace TeleBot\System\Types;

class IncomingVoice extends File
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
     * get voice duration in seconds
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->file['duration'];
    }

    /**
     * get voice mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->file['mime_type'];
    }

    /**
     * download voice
     *
     * @return string|null returns stored file name
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}