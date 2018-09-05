<?php

namespace fish;

use pocketmine\{Player, Server};
use pocketmine\item\Item;
use pocketmine\event\player\PlayerFishingEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onFishing(PlayerFishingEvent $event){
		$fish = $event->getResultItem();
		$rand = mt_rand(1, 10);
		$rand1 = mt_rand(1, 10);
		if($rand === 1){
			$name = "なの";
		}elseif($rand === 2){
			$name = "カジキ";
		}elseif($rand === 3){
			$name = "アジ";
		}elseif($rand === 4){
			$name = "サンマ";
		}elseif($rand === 5){
			$name = "ジャッキーチェン";
		}elseif($rand === 6){
			$name = "サメ";
		}elseif($rand === 7){
			$name = "クジラ";
		}elseif($rand === 8){
			$name = "さかなクン";
		}elseif($rand === 9){
			$name = "ムツゴロウ天国";
		}elseif($rand === 10){
			$name = "将来とは何科";
		}
		if($rand1 === 1){
			$rank = "E";
		}elseif($rand1 === 2){
			$rank = "D";
		}elseif($rand1 === 3){
			$rank = "C";
		}elseif($rand1 === 4){
			$rank = "B";
		}elseif($rand1 === 5){
			$rank = "A";
		}elseif($rand1 === 6){
			$rank = "S";
		}elseif($rand1 === 7){
			$rank = "SS";
		}elseif($rand1 === 8){
			$rank = "SSS";
		}
		$size = mt_rand(1, 100);
		$fish->setCustomName($name."\n§eレア度§f: ".$rank."\n§a大きさ§f: ".$size."m");
		$event->setResultItem($fish);
	}
}