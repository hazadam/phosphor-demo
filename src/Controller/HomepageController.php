<?php

namespace Hazadam\PhosphorDemo\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class HomepageController
{
    public function __construct(private readonly Environment $twig)
    {
    }

    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function homepageAction(): Response
    {
        return new Response($this->twig->render('Page/homepage.html.twig'));
    }
}
