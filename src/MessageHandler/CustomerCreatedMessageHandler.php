<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Customer;
use App\Message\CustomerCreatedMessage;
use App\Repository\CustomerRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CustomerCreatedMessageHandler
{
    public function __construct(private readonly CustomerRepository $customerRepository)
    {}

    public function __invoke(CustomerCreatedMessage $message): void
    {
        $customer = new Customer();
        $customer->setFirstName($message->getFirstName());
        $customer->setLastName($message->getLastName());

        if ($message->getAttachmentPath()) {
            $customer->setAttachment($message->getAttachmentPath());
        }

        $this->customerRepository->save($customer);
    }
}
