<?php

declare(strict_types=1);

namespace HyperFlareMC\CommandLogger;

use pocketmine\scheduler\AsyncTask;

class WebhookAsyncTask extends AsyncTask{

    private string $webhook;
    private string $curlopts;

    public function __construct(string $webhook, string $curlopts){
        $this->webhook = $webhook;
        $this->curlopts = $curlopts;
    }

    public function onRun() : void{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webhook);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(unserialize($this->curlopts)));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($curl);
    }
}