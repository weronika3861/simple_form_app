<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\InvalidFormException;
use App\Message\CustomerCreatedMessage;
use App\Repository\CustomerRepositoryInterface;
use App\Service\FileUploaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Customer;
use App\Form\CustomerFormType;

class CustomerController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly FileUploaderInterface $fileUploader,
    ) {
    }

    #[Route('/', name: 'main_page', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('customer/index.html.twig');
    }

    #[Route('/customer/create', name: 'customer_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        try {
            $customer = new Customer();
            $form = $this->createForm(CustomerFormType::class, $customer);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $this->checkIfFormIsValid($form);

                $attachmentPath = null;

                $attachment = $form->get('attachment')->getData();
                if ($attachment) {
                    $attachmentPath = $this->fileUploader->upload($attachment);
                }

                $this->bus->dispatch(new CustomerCreatedMessage(
                    $customer->getFirstName(),
                    $customer->getLastName(),
                    $attachmentPath
                ));

                $this->addFlash('success', 'Customer created successfully!');

                return $this->redirectToRoute('main_page');
            }
        } catch (InvalidFormException $exception) {
            $this->addFlash('error', 'Invalid form: ' . $exception->getMessage());

            return $this->redirectToRoute('customer_create');
        } catch (FileException $exception) {
            $this->addFlash('error', 'File upload failed: ' . $exception->getMessage());

            return $this->redirectToRoute('customer_create');
        } catch (\Exception | ExceptionInterface $exception) {
            $this->addFlash('error', 'Creating customer failed.');

            return $this->redirectToRoute('customer_create');
        }

        return $this->render('customer/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/customer/list', name: 'customer_list', methods: ['GET'])]
    public function list(): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $customers = $this->customerRepository->findAll();

        return $this->render('customer/list.html.twig', [
            'customers' => $customers,
        ]);
    }

    /**
     * @param FormInterface $form
     * @throws InvalidFormException
     */
    private function checkIfFormIsValid(FormInterface $form): void
    {
        if (!$form->isValid()) {
            throw new InvalidFormException((string)$form->getErrors(true, false));
        }
    }
}
