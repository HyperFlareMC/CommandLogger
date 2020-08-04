<?php

declare(strict_types=1);

namespace HyperFlareMC\CommandLogger;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

    /**
     * @var Config
     */
    private static $config;

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        self::$config = $this->getConfig()->getAll();
    }

    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if($message[0] === "/"){
            $command = str_replace(["{player}", "{command}], [$player->getName(), $message], self::$config["logger-message"]);
            $this->getLogger()->info($command);
        }
    }
}
