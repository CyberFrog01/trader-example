<?php

namespace App\Service;

use App\Entity\ItemEntity;
use App\Service\RequestService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemService
{
    public function __construct(
        private RequestService $rs,
    ) {
    }

    public function createItem($item) {
        //Save data that needed for API request to market
        $item = $this->setItemAdditionalData($item);
        //Create target for item
        $this->createTarget($item, $item->getMaxAmount(), $item->getMaxPrice());
        //return updated 
        return $item;
    }

    public function createTarget($item, $amount, $price) {
        //Filling request body with item data
        $body = $this->buildTargetBodyFromItem($item, $amount, strval($price));
        //Send requst using RequestService
        $this->rs->createTargetRequest($body);
    }

    public function setItemAdditionalData($item) 
    {
        //Get data for item, that we needed for request, 
        //From first offer with same title, as item using RequestService
        $result = $this->rs->getOffersByTitleRequest($item->getTitle(), 1);

        //Transform responce from market to get offer data
        $result = json_decode($result,true);
        $offer = $result['objects'][0];

        //This's data, that needed for request
        //Fill it in an item
        $item->setGameId($offer['gameId']);
        $item->setCategoryPath($offer['extra']['categoryPath']);
        $item->setImage($offer['image']);

        return $item;
    }

    function buildTargetBodyFromItem(ItemEntity $item, $amount = 1, $price = "1") {
        //Request body
        return array
        (
            "targets" => array
            (
                array
                (
                    "amount" => $amount,
                    "gameId" => $item->getGameId(),
                    "price" => array("amount" => $price, "currency" => "USD"),
                    "attributes" => array(
                        "gameId" => $item->getGameId(),
                        "categoryPath" => $item->getCategoryPath(),
                        "title" => $item->getTitle(),
                        "name" => $item->getTitle(),
                        "image" => $item->getImage(),
                        "ownerGets" => array("amount" => "10", "currency" => "USD"))
                )
            )
        );
    }
}