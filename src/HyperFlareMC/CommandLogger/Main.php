<?php

declare(strict_types=1);

namespace HyperFlareMC\CommandLogger;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class Main extends PluginBase implements Listener{

    /** @var mixed[] */
    private static array $config;

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        self::$config = $this->getConfig()->getAll();
    }

    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event) : void{
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if($message[0] === "/"){
            $consoleMessage = str_replace(["{player}", "{command}"], [$player->getName(), $message], self::$config["formats"]["console-message"]);
            $discordMessage = str_replace(["{player}", "{command}"], [$player->getName(), $message], self::$config["formats"]["discord-message"]);
            switch(true){
                case self::$config["settings"]["console"]:
                    $this->getLogger()->info($consoleMessage);
                case self::$config["settings"]["discord"]:
                    $this->sendCommandMessage($discordMessage);
                    break;
                default:
                    $this->getLogger()->critical("CommandLogger being disabled, no config options enabled...");
                    $this->getServer()->getPluginManager()->disablePlugin($this);
                    break;
            }
        }
    }

    public function sendCommandMessage(string $msg) : void{
        $name = self::$config["settings"]["webhook-name"];
        $url = self::$config["settings"]["webhook-url"];
        $curlopts = [
            "content" => $msg,
            "username" => $name
        ];
        $this->getServer()->getAsyncPool()->submitTask(new WebhookAsyncTask($url, serialize($curlopts)));
    }
}