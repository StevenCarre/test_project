<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonController extends AbstractController
{
    protected $personRepository;


    protected $entityManager;

    public function __construct(PersonRepository $personRepository, EntityManagerInterface $entityManager)
    {
        $this->personRepository = $personRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/person/edit/{id}", name="person_edit")
     */
    public function edit(?int $id = null, Request $request): Response
    {
        $person = $id ? $this->personRepository->find($id) : new Person;

        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($person);
                $this->entityManager->flush();
                
                return $this->redirectToRoute('person_list');
            }
        }
        return $this->render('person/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/person/list", name="person_list")
     */
    public function index(Request $request): Response
    {
        $persons = $this->personRepository->findAll();

        return $this->render("person/list.html.twig", [
            'persons' => $persons,
        ]);
    }
}
