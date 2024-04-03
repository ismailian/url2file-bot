<?php

namespace TeleBot\System\Types;

class IncomingDocument extends File
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
     * get document file name
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->file['file_name'];
    }

    /**
     * get document mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->file['mime_type'];
    }

    /**
     * get document thumbnail
     *
     * @return Thumbnail
     */
    public function getThumbnail(): Thumbnail
    {
        return new Thumbnail($this->file['thumbnail']);
    }

    /**
     * download document
     *
     * @return string|null returns stored file name
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}