<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /*
     * /article/en/2010/my-post
     * */
    #[Route('/article/{_locale}/{year}/{slug}.{_format}', name: 'article', requirements: ['_locale' => 'en|fr', '_format' => 'html|rss', 'year' => '\d+'], defaults: ['_format' => 'html'])]
    public function index($_locale, $year, $slug, $_format): Response
    {
        $message = "language Selected: ".$_locale.", year selected: ".$year.", slug selected: ".$slug.", format selected: ".$_format;
        return $this->json( [
            'message' => $message,
            'path' => 'src/Controller/ArticleController.php',
        ]);
    }
}
