<?php

declare(strict_types=1);

namespace App\Message;

class CustomerCreatedMessage
{
    private string $firstName;
    private string $lastName;
    private ?string $attachmentPath;

    public function __construct(string $firstName, string $lastName, ?string $attachmentPath = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->attachmentPath = $attachmentPath;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAttachmentPath(): ?string
    {
        return $this->attachmentPath;
    }
}
