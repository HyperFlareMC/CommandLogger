<?php

declare(strict_types=1);

namespace HyperFlareMC\CommandLogger;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

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
            $consoleMessage = str_replace(["{player}", "{command}"], [$player->getName(), $message], self::$config["formats"]["console-message"]);
            $discordMessage = str_replace(["{player}", "{command}"], [$player->getName(), $message], self::$config["formats"]["discord-message"]);
            if(self::$config["settings"]["console"] === true && self::$config["settings"]["discord"] !== true){
                $this->getLogger()->info($consoleMessage);
                return;
            }elseif(self::$config["settings"]["discord"] === true && self::$config["settings"]["console"] !== true){
                $this->sendCommandMessage($discordMessage);
                return;
            }elseif(self::$config["settings"]["console"] && self::$config["settings"]["discord"] === true){
                $this->getLogger()->info($consoleMessage);
                $this->sendCommandMessage($discordMessage);
            }else{
                $this->getLogger()->critical("CommandLogger being disabled, no config options enabled...");
                $this->getServer()->getPluginManager()->disablePlugin($this);
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
