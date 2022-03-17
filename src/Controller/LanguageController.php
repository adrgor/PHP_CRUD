<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageType;
use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


#[Route('/language')]
class LanguageController extends AbstractController
{
        public function __construct() {
                $this->encoders = [new XmlEncoder(), new JsonEncoder()];
                $this->normalizers = [new ObjectNormalizer()];
                $this->serializer = new Serializer($this->normalizers, $this->encoders);
        }


    #[Route('/', name: 'app_language_index', methods: ['GET'])]
    public function index(LanguageRepository $languageRepository): Response
    {
        $jsonContent = $this->serializer->serialize($languageRepository->findAll(), 'json');

        return new Response($jsonContent);

    }

    #[Route('/new', name: 'app_language_new', methods: ['POST'])]
    public function new(Request $request, LanguageRepository $languageRepository): Response
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $languageRepository->add($language);
        }

        return new Response();
    }

    #[Route('/{id}', name: 'app_language_show', methods: ['GET'])]
    public function show(Language $language): Response
    {
	$jsonContent = $this->serializer->serialize($language, 'json');
        return new Response($jsonContent);
    }

    #[Route('/{id}/edit', name: 'app_language_edit', methods: ['POST'])]
    public function edit(Request $request, Language $language, LanguageRepository $languageRepository): Response
    {
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $languageRepository->add($language);
        }

        return new Response();
    }

    #[Route('/{id}', name: 'app_language_delete', methods: ['DELETE'])]
    public function delete(Request $request, Language $language, LanguageRepository $languageRepository): Response
    {
        $languageRepository->remove($language);

        return new Response();
    }
}
