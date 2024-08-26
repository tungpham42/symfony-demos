<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class TodoController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'todo_index')]
    public function index(): Response
    {
        // Fetch all todos from the database
        $todos = $this->entityManager->getRepository(Todo::class)->findAll();

        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
        ]);
    }

    #[Route('/new', name: 'todo_create')]
    public function create(Request $request): Response
    {
        // Create a new Todo entity
        $todo = new Todo();

        // Create a form for the Todo entity
        $form = $this->createForm(TodoType::class, $todo);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set the creation date
            $todo->setCreatedAt(new \DateTime());

            // Persist the new Todo entity
            $this->entityManager->persist($todo);
            $this->entityManager->flush();

            // Redirect to the todo list
            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'todo_edit')]
    public function edit(Request $request, Todo $todo): Response
    {
        // Ensure that the title is accessed correctly
        if (!$todo->getTitle()) {
            throw $this->createNotFoundException('No title found for this Todo.');
        }

        // Create a form for the Todo entity
        $form = $this->createForm(TodoType::class, $todo);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the Todo entity
            $this->entityManager->flush();

            // Redirect to the todo list
            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'todo_delete', methods: ['POST'])]
    public function delete(Request $request, Todo $todo): Response
    {
        // Validate the CSRF token for security
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            // Remove the Todo entity
            $this->entityManager->remove($todo);
            $this->entityManager->flush();
        }

        // Redirect to the todo list
        return $this->redirectToRoute('todo_index');
    }
}
