<?php

namespace kill_warn\task;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\scheduler\Task;

use kill_warn\main;

class checkWarn extends Task{

	function __construct(main $owner){
		$this->owner = $owner;
	}

	function onRun(int $currentTick){
		foreach($this->owner->getServer()->getOnlinePlayers() as $player){
			$warn = $this->owner->db->getWarn($player);
			if(!is_null($warn)){
				if($warn >= 4){
					$this->owner->getServer()->getNameBans()->addBan($player, "警告が4以上に達した", null, $player->getName());
					$player->kick("警告が4以上に達した", false);
					foreach($this->owner->getServer()->getOnlinePlayers() as $players) $players->sendMessage("§l§a[KillWarn] §c".$player->getName()."が警告4以上に達したのでBanされました");
				}
			}
		}
	}
}
