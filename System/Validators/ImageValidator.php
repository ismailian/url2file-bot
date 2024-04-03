<?php

namespace TeleBot\System\Validators;

use TeleBot\System\Interfaces\IValidator;

class ImageValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param int|null $minSize
     * @param int|null $maxSize
     * @param int|null $minWidth
     * @param int|null $maxWidth
     * @param int|null $minHeight
     * @param int|null $maxHeight
     */
    public function __construct(
        public ?int $minSize = null,
        public ?int $maxSize = null,
        public ?int $minWidth = null,
        public ?int $minHeight = null,
        public ?int $maxWidth = null,
        public ?int $maxHeight = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        $props = [
            'length' => max(array_column($data, 'file_size')),
            'width' => max(array_column($data, 'width')),
            'height' => max(array_column($data, 'height')),
        ];

        if (
            ($this->minSize && $this->minSize > $props['length'])
            || ($this->maxSize && $this->maxSize < $props['length'])
            || ($this->minWidth && $this->minWidth > $props['width'])
            || ($this->maxWidth && $this->maxWidth < $props['width'])
            || ($this->minHeight && $this->minHeight > $props['height'])
            || ($this->maxHeight && $this->maxHeight < $props['height'])
        ) return false;
        return true;
    }
}