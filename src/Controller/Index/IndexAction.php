<?php

declare(strict_types=1);

namespace App\Controller\Index;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class IndexAction
{
    /** @var ContactRepository */
    private $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param Environment $twig
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Environment $twig): Response
    {
        $contacts = $this->repository->findAll();

        $response = $twig->render(
            'index/index.html.twig',
            [
                'contacts' => $contacts,
            ]
        );

        return new Response($response);
    }
}
