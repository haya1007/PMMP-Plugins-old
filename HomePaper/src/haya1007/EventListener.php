<?php

namespace haya1007;

use pocketmine\event\Listener;

use pocketmine\inventory\Inventory;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class EventListener implements Listener{

	public function __construct(Main $main){
		$this->main = $main;
	}

	public function onTap(PlayerInteractEvent $event){
		if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
			$player = $event->getPlayer();
			$world_name = $player->getLevel()->getFolderName();
			if(!isset($this->world[$world_name])){
				$inv = $player->getInventory();
				$hand_item = $inv->getItemInHand();
				if($hand_item == $this->main->paper){
					$x = round($player->x, 1);
					$y = round($player->y, 1);
					$z = round($player->z, 1);

					$hand_item->setNamedTagEntry(new FloatTag("x", round($player->x, 1)));
					$hand_item->setNamedTagEntry(new FloatTag("y", round($player->y, 1)));
					$hand_item->setNamedTagEntry(new FloatTag("z", round($player->z, 1)));
					$hand_item->setNamedTagEntry(new StringTag("world", round($world_name, 1)));
					$hand_item->setCustomName("§l§aHomePaper");
					$hand_item->setLore(["座標: ".$x.":".$y.":".$z."", "ワールド: ".$world_name.""]);

					if($inv->canAddItem($hand_item)){
						$inv->removeItem($this->main->paper);
						$inv->addItem($hand_item);
						$player->sendMessage("§l§aテレポート先を登録しました");
					} else {
						$player->sendMessage("§l§cインベントリがいっぱいです");
					}
				} else {
					$this->main->teleport($player);
				}
			} else {
				$player->sendMessage("§l§cこのワールドでは使えないアイテムです");
			}
		}
	}
}