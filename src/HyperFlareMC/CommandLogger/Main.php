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
    private $config;

    /**
     * @var string
     */
    private $loggerMessage;

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->loggerMessage = $this->config->get("logger-message");
    }

    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $console = new ConsoleCommandSender();
        $message = $event->getMessage();
        if($message[0] === "/"){
            $command = $this->loggerMessage;
            $replacements = [
                "{command_name}" => $message,
                "{player_name}" => $player->getName()
            ];
            foreach($replacements as $tag => $def){
                $command = str_replace($tag, $def, $command);
            }
            $console->sendMessage($command);
        }
    }
}