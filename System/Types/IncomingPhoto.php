<?php

namespace TeleBot\System\Types;

class IncomingPhoto extends File
{

    /**
     * get file ids
     *
     * @return array
     */
    public function getFileIds(): array
    {
        return array_map(fn($variant) => $variant['file_id'], $this->file);
    }

    /**
     * get file id by index
     *
     * @param int $index
     * @return string|null
     */
    public function getFileId(int $index): ?string
    {
        return $this->file[$index]['file_id'] ?? null;
    }

    /**
     * download the highest quality photo
     *
     * @return string|null
     */
    public function save(): ?string
    {
        usort($this->file, fn($a, $b) => $a['file_size'] > $b['file_size'] ? $a : $b);
        $this->getLink(0);

        return $this->saveAs();
    }

}