<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/item')]
class ItemController extends AbstractController
{
	public function __construct() {
		$this->encoders = [new XmlEncoder(), new JsonEncoder()];
		$this->normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($this->normalizers, $this->encoders);
	}

    #[Route('/', name: 'app_item_index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository): Response
    {
	$jsonContent = $this->serializer->serialize($itemRepository->findAll(), 'json');

	return new Response($jsonContent);    
    }

    #[Route('/new', name: 'app_item_new', methods: ['POST'])]
    public function new(Request $request, ItemRepository $itemRepository): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemRepository->add($item);
        }

	return new Response();
    }

    #[Route('/{id}', name: 'app_item_show', methods: ['GET'])]
    public function show(Item $item): Response
    {
	    $jsonContent = $this->serializer->serialize($item, 'json');
	    return new Response($jsonContent);
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['POST'])]
    public function edit(Request $request, Item $item, ItemRepository $itemRepository): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemRepository->add($item);
        }

	return new Response();
    }

    #[Route('/{id}', name: 'app_item_delete', methods: ['DELETE'])]
    public function delete(Request $request, Item $item, ItemRepository $itemRepository): Response
    {
        $itemRepository->remove($item);
	return new Response();
    }
}
