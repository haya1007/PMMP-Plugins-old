<?php

namespace keep;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent as Quit;
use pocketmine\event\player\PlayerDeathEvent as Death;
use pocketmine\event\player\PlayerRespawnEvent as Respawn;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Item;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function PlayerQuit(Quit $event){
		$name = $event->getPlayer()->getName();
		if(isset($this->drops[$name])){
			unset($this->drops[$name]);
		}
	}

	public function PlayerDeath(Death $event){
		$entity = $event->getPlayer();
		$name = $entity->getName();
		$a0 = $entity->getArmorInventory()->getHelmet();
		$a1 = $entity->getArmorInventory()->getChestplate();
		$a2 = $entity->getArmorInventory()->getLeggings();
		$a3 = $entity->getArmorInventory()->getBoots();
		$this->drops[$name][0] = $a0;
		$this->drops[$name][1] = $a1;
		$this->drops[$name][2] = $a2;
		$this->drops[$name][3] = $a3;
		$this->drops[$name][4] = $entity->getInventory()->getContents();
		$event->setDrops(array());
	}

	public function PlayerRespawn(Respawn $event){
	    $player = $event->getPlayer();
	    if(isset($this->drops[$player->getName()])){
      	    $player->getArmorInventory()->setHelmet($this->drops[$player->getName()][0]);
            $player->getArmorInventory()->setChestplate($this->drops[$player->getName()][1]);
            $player->getArmorInventory()->setLeggings($this->drops[$player->getName()][2]);
          	$player->getArmorInventory()->setBoots($this->drops[$player->getName()][3]);
	    	$player->getInventory()->setContents($this->drops[$player->getName()][4]);
	    	unset($this->drops[$player->getName()]);
	    }
	}
}