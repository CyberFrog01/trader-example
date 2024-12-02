<?php

namespace App\Controller;

use App\Repository\ItemEntityRepository;
use App\Entity\ItemEntity;
use App\Entity\TargetEntity;
use App\Form\ItemType;
use App\Form\TargetType;
use App\Service\ItemService;
use App\Service\RequestService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MainController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ItemService $itemService,
        ItemEntityRepository $itemRepository
    ) {
    }

    #[Route('/create', name: 'create')]
    public function createItemAction(Request $request) {
        //Create new item element
        //Item is parent class with basic data
        $item = new ItemEntity;
        //Create form for item
        //This form is for filling new item data and create target for it
        $itemForm = $this->createForm(ItemType::class, $item);

        //Create new target
        //Target or buy order is child class, it's content information with price and amount of our buy order
        $target = new TargetEntity;
        //Create form for target
        //This form is to filling target data for existing item
        $targetForm = $this->createForm(TargetType::class, $target);

        
        $itemForm->handleRequest($request);
        //Validate data
        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $item = $itemForm->getData();
            
            //Process item creation in ItemService
            $item = $this->itemService->createItem($item);
            //Save target data
            $target->setItem($item);
            $target->setAmount($item->getMaxAmount());
            $target->setPrice($item->getMaxPrice());

            //Save entities to DB
            $this->entityManager->persist($item);
            $this->entityManager->persist($target);
            $this->entityManager->flush();

            return $this->redirectToRoute('create');
        }

        
        $targetForm->handleRequest($request);
        //Validate data
        if ($targetForm->isSubmitted() && $targetForm->isValid()) {
            $target = $targetForm->getData();
            //Process target creation in ItemService
            $this->itemService->createTarget($target->getItem(), $target->getAmount(), $target->getPrice());

            //Save entities to DB
            $this->entityManager->persist($item);
            $this->entityManager->flush();

            return $this->redirectToRoute('create');
        }

        //Simple twig file to render forms
        return $this->render('base.html.twig', [
            'form' => $itemForm,
            'form2' => $targetForm
        ]);
    }

    #[Route('/csv')]
    public function createTargetsFromCSV() {
        //Init serializer to decode csv file
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        //You can see test data inside test.csv file
        $targets = $serializer->decode(file_get_contents('test.csv'), 'csv');
        
        //Process data
        foreach ($targets as $targetData) {
            //Get item from repository
            $item = $this->entityManager->getRepository(ItemEntity::class)->findOneBy(['title' => $targetData['title']]);
            
            //Create target and fill it with data
            $target = new TargetEntity;
            $item->getMarketName()->addTarget($target);
            $target->setAmount($targetData['amount']);
            $target->setPrice($targetData['price']);
            
            //Process target creation
            $this->itemService->createTarget($target);
            
            //Save entities to DB
            $this->entityManager->persist($item);
            $this->entityManager->persist($target);
            $this->entityManager->flush();
        }
        //No render yeet :)
    }
}
