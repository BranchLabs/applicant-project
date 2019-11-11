<?php

declare(strict_types=1);

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Exception\RedirectException;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class DeleteContactAction
{
    /** @var ContactRepository */
    private $repository;

    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $flashBag;

    public function __construct(ContactRepository $repository, RouterInterface $router, SessionInterface $session)
    {
        $this->repository = $repository;
        $this->flashBag = $session->getFlashBag();
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param string|null $id
     *
     * @throws RedirectException
     */
    public function __invoke(Request $request, ?string $id = null)
    {
        /** @var Contact|null $contact */
        $contact = $this->repository->find($id);

        if (!$contact) {
            $this->flashBag->add('error', 'Contact not found');
            throw new RedirectException($this->router->generate('index'));
        }

        $this->repository->delete($id);

        $this->flashBag->add('success', sprintf(
            'Contact %s has been deleted',
            $contact->getFullName()
        ));

        throw new RedirectException($this->router->generate('index'));
    }
}
