<?php

namespace TeleBot\System\Validators;

use TeleBot\System\Interfaces\IValidator;

class VideoValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param string|null $type
     * @param int|null $minDuration
     * @param int|null $maxDuration
     * @param int|null $minSize
     * @param int|null $maxSize
     * @param int|null $minQuality
     */
    public function __construct(
        public ?string $type = null,
        public ?int    $minDuration = null,
        public ?int    $maxDuration = null,
        public ?int    $minSize = null,
        public ?int    $maxSize = null,
        public ?int    $minQuality = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        if (
            ($this->type && $this->type !== explode(('/'), $data['mime_type'])[1])
            || ($this->minDuration && $this->minDuration > $data['duration'])
            || ($this->maxDuration && $this->maxDuration < $data['duration'])
            || ($this->minSize && $this->minSize > $data['file_size'])
            || ($this->maxSize && $this->maxSize < $data['file_size'])
            || ($this->minQuality && $this->minQuality > $data['height'])
        ) return false;
        return true;
    }
}