<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


#[Route('/person')]
class PersonController extends AbstractController
{

        public function __construct() {
                $this->encoders = [new XmlEncoder(), new JsonEncoder()];
                $this->normalizers = [new ObjectNormalizer()];
                $this->serializer = new Serializer($this->normalizers, $this->encoders);
        }


    #[Route('/', name: 'app_person_index', methods: ['GET'])]
    public function index(PersonRepository $personRepository): Response
    {
        $jsonContent = $this->serializer->serialize($personRepository->findAll(), 'json');

        return new Response($jsonContent);
    }

    #[Route('/new', name: 'app_person_new', methods: ['POST'])]
    public function new(Request $request, PersonRepository $personRepository): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personRepository->add($person);
        }

        return new Response();
    }

    #[Route('/{id}', name: 'app_person_show', methods: ['GET'])]
    public function show(Person $person): Response
    {
	$jsonContent = $this->serializer->serialize($person, 'json');
	return new Response($jsonContent);
    }

    #[Route('/{id}/edit', name: 'app_person_edit', methods: ['POST'])]
    public function edit(Request $request, Person $person, PersonRepository $personRepository): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personRepository->add($person);
        }

        return new Response();
    }

    #[Route('/{id}', name: 'app_person_delete', methods: ['DELETE'])]
    public function delete(Request $request, Person $person, PersonRepository $personRepository): Response
    {
            $personRepository->remove($person);
        return new Response();
    }
}
