<?php

namespace join;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJoinEvent;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginmanager()->registerEvents($this, $this);
	}

	public function join(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$player->sendMessage("§aようこそ");
	}
}