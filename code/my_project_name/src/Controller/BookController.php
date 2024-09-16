<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'book_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $books = $entityManager->getRepository(Book::class)->findAll();

        // Serializar los objetos a JSON
        $booksArray = [];
        foreach ($books as $book) {
            $booksArray[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'publishedAt' => $book->getPublishedAt()->format('Y-m-d H:i:s')
            ];
        }

        return new JsonResponse($booksArray);
    }

    #[Route('/new', name: 'book_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setPublishedAt(new \DateTime($data['publishedAt']));

        $entityManager->persist($book);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Book created successfully',
            'book' => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'publishedAt' => $book->getPublishedAt()->format('Y-m-d H:i:s')
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'book_show', methods: ['GET'])]
    public function show(Book $book): JsonResponse
    {
        return new JsonResponse([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'publishedAt' => $book->getPublishedAt()->format('Y-m-d H:i:s')
        ]);
    }

    #[Route('/{id}/edit', name: 'book_edit', methods: ['PUT'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setPublishedAt(new \DateTime($data['publishedAt']));

        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Book updated successfully',
            'book' => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'publishedAt' => $book->getPublishedAt()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    #[Route('/{id}', name: 'book_delete', methods: ['DELETE'])]
    public function delete(Book $book, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($book);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Book deleted successfully']);
    }
}
