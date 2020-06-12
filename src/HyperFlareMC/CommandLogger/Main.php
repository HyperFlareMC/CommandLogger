<?php

declare(strict_types=1);

namespace HyperFlareMC\CommandLogger;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $console = new ConsoleCommandSender();
        $message = $event->getMessage();
        if($message[0] === "/"){
            $console->sendMessage(TextFormat::WHITE . "COMMAND: <" . $player->getName() . "> " . TextFormat::clean($message));
        }
    }
}