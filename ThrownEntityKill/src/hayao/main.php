<?php

namespace hayao;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\Item;
use pocketmine\entity\Arrow;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->server = $this->getServer();
		$this->server->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->notice("ThrownEntityKill稼働確認　製作者:hayao");
	}

	public function Hit(ProjectileHitEvent $event){
		$entity = $event->getEntity();
		$entity->kill();
	}
}