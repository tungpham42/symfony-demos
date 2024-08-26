<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProverbController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/', name: 'proverb')]
    public function index(): Response
    {
        // Fetching a random proverb/quote from the Quotable API
        $response = $this->client->request(
            'GET',
            'https://api.quotable.io/random'
        );

        // Decoding the JSON response to an associative array
        $data = $response->toArray();

        // Structuring the proverb data based on the provided API response
        $proverb = [
            'id' => $data['_id'],
            'content' => $data['content'],
            'author' => $data['author'],
            'tags' => $data['tags'],
            'authorSlug' => $data['authorSlug'],
            'length' => $data['length'],
            'dateAdded' => $data['dateAdded'],
            'dateModified' => $data['dateModified'],
        ];

        return $this->render('proverb/index.html.twig', [
            'proverb' => $proverb,
        ]);
    }
}
