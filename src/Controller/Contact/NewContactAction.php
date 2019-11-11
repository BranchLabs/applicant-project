<?php

declare(strict_types=1);

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Exception\RedirectException;
use App\Repository\ContactRepository;
use App\Form\ContactForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewContactAction extends AbstractController
{
    /** @var ContactRepository */
    private $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws RedirectException
     */
    public function __invoke(Request $request): Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactForm::class, $contact);
        $this->handleForm($request, $form, $contact);

        return new Response($this->render(
            'contact/new.html.twig',
            [
                'contact' => $contact,
                'form' => $form->createView(),
            ]
        ));
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @param Contact $contact
     *
     * @throws RedirectException
     */
    private function handleForm(Request $request, FormInterface $form, Contact $contact)
    {
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        $this->repository->create($contact);

        $this->addFlash('success', sprintf(
            'Contact <strong>%s</strong> added successfully',
            $contact->getFullName()
        ));

        throw new RedirectException($this->generateUrl('index'));
    }
}
