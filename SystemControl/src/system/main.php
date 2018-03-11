<?php

namespace system;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\PlayerInventory;
use pocketmine\item\Item;
use pocketmine\utils\Config;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\NoDynamicFieldsTrait;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDropItemEvent;

class main extends PluginBase implements Listener{
	function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){mkdir($this->getDataFolder(), 0744, true);}
		$this->setting = new Config($this->getDataFolder().'setting.yml', Config::YAML, array(
			"ブロック破壊無効化" => "op",
			"ブロック設置無効化" => "op",
			"アイテムクラフト無効化" => "op",
			"アイテムを投げる無効化" => "op",
			"落下ダメージ無効化" => "true"#,
			#"死亡時にアイテム保持" => "true"
		));
	}

	function Break(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$setting = $this->setting->get("ブロック破壊");
		if($setting === "true"){
			$event->setCancelled();
		}elseif($setting === "op"){
			if(!$player->isOp()){
				$event->setCancelled();
			}
		}
	}

	function Place(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		$setting = $this->setting->get("ブロック設置");
		if($setting === "true"){
			$event->setCancelled();
		}elseif($setting === "op"){
			if(!$player->isOp()){
				$event->setCancelled();
			}
		}
	}

	function Carft(CraftItemEvent $event){
		$player = $event->getPlayer();
		$setting = $this->setting->get("アイテムクラフト");
		if($setting === "true"){
			$event->setCancelled();
		}elseif($setting === "op"){
			if(!$player->isOp()){
				$event->setCancelled();
			}
		}	
	}

	function Drop(PlayerDropItemEvent $event){
		$player = $event->getPlayer();
		$setting = $this->setting->get("アイテムを投げる");
		if($setting === "true"){
			$event->setCancelled();
		}elseif($setting === "op"){
			if(!$player->isOp()){
				$event->setCancelled();
			}
		}	
	}

	function Damage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($entity instanceof Player){
			$cause = $event->getCause();
			if($cause === 4){
				$setting = $this->setting->get("落下ダメージ無効化");
				if($setting === "true"){
					$event->setCancelled();
				}
			}
		}
	}

	/*function Death(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if($this->setting->get("死亡時にアイテム保持") === "true"){
			$a0 = $player->getArmorInventory()->getHelmet();
			$a1 = $player->getArmorInventory()->getChestplate();
			$a2 = $player->getArmorInventory()->getLeggings();
			$a3 = $player->getArmorInventory()->getBoots();
		    $this->drops[$name][0] = $a0;
		    $this->drops[$name][1] = $a1;
		    $this->drops[$name][2] = $a2;
		    $this->drops[$name][3] = $a3;
		    $this->drops[$name][4] = $player->getInventory()->getContents();
		    $event->setDrops(array());
		}
	}

	function PlayerRespawn(PlayerRespawnEvent $event){
	    $player = $event->getPlayer();
	    if(isset($this->drops[$player->getName()])){
      	    $player->getArmorInventory()->setHelmet($this->drops[$player->getName()][0]);
            $player->getArmorInventory()->setChestplate($this->drops[$player->getName()][1]);
            $player->getArmorInventory()->setLeggings($this->drops[$player->getName()][2]);
          	$player->getArmorInventory()->setBoots($this->drops[$player->getName()][3]);
	    	$player->getInventory()->setContents($this->drops[$player->getName()][4]);
	    	unset($this->drops[$player->getName()]);
	    }
	}*/

	function Quit(PlayerQuitEvent $event){
		$name = $event->getPlayer()->getName();
		if(isset($this->drops[$name])){
			unset($this->drops[$name]);
		}
	}
}
