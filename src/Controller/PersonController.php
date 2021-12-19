<?php

namespace App\Controller;

use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    protected $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @Route("/person", name="person_edit")
     */
    public function edit(Request $request): Response
    {
        $form = $this->createForm(PersonType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
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
        dd($persons);
    }
}
