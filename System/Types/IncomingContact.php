<?php

namespace TeleBot\System\Types;

class IncomingContact
{

    /** @var array $contact */
    protected array $contact;

    public function __construct(array $contact)
    {
        $this->contact = $contact;
    }

    /**
     * get contact phone number
     *
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->contact['phone_number'];
    }

    /**
     * get contact first name
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->contact['first_name'] ?? null;
    }

    /**
     * get contact last name
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->contact['last_name'] ?? null;
    }

    /**
     * get contact vcard
     *
     * @return string
     */
    public function getVCard(): string
    {
        return $this->contact['vcard'];
    }

}