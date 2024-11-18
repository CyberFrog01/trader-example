<?php

namespace App\Controller;

use App\Service\RequestService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user')]
    public function getUserData(RequestService $rs) {
        dump('na rahunku ot stiki groshei');
        dump($rs->createRequest('GET', '/account/v1/balance'));

        dump('taki targety stojat');
        $r = $rs->createRequest('GET', '/marketplace-api/v1/user-targets?GameID=a8db&BasicFilters.Status=TargetStatusActive&SortType=UserTargetsSortTypeDefault');
        $r = $this->rta($r);
        
        foreach ($r as $item) {
            dump($item['Title'], $item['Amount'], $item['Price']);
        }
        dd('vsio');
    }

    function rta($data) {
        $r = json_decode($data,true);
        return $r['Items'];
    }
}
