<?php

namespace App\Command\MarketName;

use App\Entity\MarketName\HistoryParsing;
use App\Repository\ItemEntityRepository;
use App\Service\MarketName\RequestService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'market-name:parse:history-by-id')]
class HistoryByIdParsingCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ItemEntityRepository $itemRepository,
        private RequestService $requestService,
    ){
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Start id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $output->writeln([
            'Start parsing item history at ' . (new \DateTime())->format('Y-m-d H:i:s'),
            '============',
            '',
        ]);

        $id = $input->getArgument('id'); 
        //Main parsing function
        $this->processItem($id);

        $output->writeln(['Succsessfully parsed items history at ' . (new \DateTime())->format('Y-m-d H:i:s')]);
        $output->writeln(['Total memory used: ' . memory_get_usage()]);
        
        $this->entityManager->flush();
        
        return 0;
    }

    private function processItem($id) {
        //Get item entity from repository
        $item = $this->itemRepository->findOneBy(['id' => $id]);
        //Get item offers history
        $this->processOffers($item);
    }

    private function processOffers($item, $limit = 50) {
        $title = $item->getTitle();

        //Making request using requestService to get 50 last records, in order from newest to oldest
        $request = $this->requestService->getItemHistory('a8db', $title, $limit, 'Offer');
        $request = json_decode($request, true);

        //Validation
        if (!array_key_exists('sales', $request)) {
            return;
        }
        if (!$request['sales']) {
            return;
        }
        $allParsedRecords = $request['sales'];

        //getLastTimeStampOffer => timestamp of latest record from PREVIOUS parsings
        //Update lastTimeStampOffer if it's smaller than timestamp of latest record from CURRENT parsing
        $lastTimeStamp = $item->getMarketName()->getLastTimeStamp();
        if ($lastTimeStamp < $allParsedRecords[0]['date']) {
            $item->setLastTimeStamp($allParsedRecords[0]['date']);
        } else {
            return;
        }

        //Process records
        foreach ($allParsedRecords as &$parsedRecord) {
            //If record timestamp is lower than last timestamp, it's mean we already have it in database and we don't have to process all next records
            if ($lastTimeStamp >= $parsedRecord['date']) {
                break;
            }
            $newRecord = new HistoryParsing;
            
            $newRecord->setDate(date('Y-m-d', $parsedRecord['date']));
            $newRecord->setPrice($this->transformPrice($parsedRecord['price']));
            $item->getMarketName()->addHistoryParsing($newRecord);

            $this->entityManager->persist($item);
        }
    }

    //Different markets return data in different format, this function is different for each parsers
    //Platform in example return price in form string "dollar.cents", standart price form for this project is int cents
    //For example: from string $price = "24.75" to int $price = 2475
    private function transformPrice($price) {
        $price = floatval($price)*100;
        return intval($price);
    }
}