This is showcase of my trading project. I'm using this website to trade Counter Strike skins between different platforms. Also i'm parsing some data from platforms, like history of purchases, available offers and other. \n
In ItemEntity stored all skins, it's parent class for "market" entities. Market entity is parent class for it's own entities, such as HistoryParsing, TargetEntity, OfferEntity and others. Content of market entities, commands, services... is edited for market reqs. 
In this repository only example for a single market.



You can start from here [MainController](App/Controller/MainController.php)
You can also see parsers here [ParsingCommands](App/Command/MarketName/)
