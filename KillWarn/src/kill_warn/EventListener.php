<?php

namespace kill_warn;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener{
	private $main;

	private $db;

	public function __construct(main $main, DB $db){
		$this->main = $main;
		$this->db = $db;
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$register = $this->db->isRegister($player);
		if($register){
			$warn = $this->db->getWarn($player);
			if(!is_null($warn)) $this->main->NewNameTag($player, $warn);
		}else{
			$this->db->register($player);
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		$entity = $event->getEntity();
		$damager = $event->getPlayer();
		if($entity instanceof Player && $damager instanceof Player){
			$now = $this->db->getWarn($damager);
			$new = $now + 1;
			$this->db->updateWarn($entity, $new);
		}
	}
}