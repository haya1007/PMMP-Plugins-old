<?php

namespace inv;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$drop = $event->getDrops();
		$event->setDrops([]);
		foreach($drop as $item){
			$this->sendItem($player, $item);
		}
	}

	public function sendItem($player, $item) {
		if ($player->getInventory()->canAddItem($item)) {
			$player->getInventory()->addItem($item);
		}else{
			$level = $player->getLevel();
			$x = $player->x;
			$y = $player->y;
			$z = $player->z;
			$pos = new Vector3($x, $y, $z);
			$level->dropItem($pos, $item);
		}
	}
}