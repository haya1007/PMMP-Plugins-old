<?php

namespace haya1007;

use pocketmine\event\Listener;

use pocketmine\inventory\Inventory;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class EventListener implements Listener{

	public function __construct(Main $main){
		$this->main = $main;
	}

	public function onTap(PlayerInteractEvent $event){
		$action = $event->getAction();
		if($action === 2 or $action === 3){
			$player = $event->getPlayer();
			$world_name = $player->getLevel()->getFolderName();
			if(!isset($this->world[$world_name])){
				$hand_item = $player->getInventory()->getItemInHand();
				if($hand_item == $this->main->paper){
					$data = [
						'type'    => 'custom_form',
						'title'   => '§aHomePaper',
						'content' => [
							[
								"type" => "label",
								"text" => "§eテレポート先の名前を書いてください\n"
							],
							[
								"type"        => "input",
								"text"        => "上限: 10文字",
								"placeholder" => "おうち",
								"default"     => ""
							],
						],
	                ];
					$this->main->createWindow($player, $data, 808080);
					$this->main->item[$player->getName()] = $hand_item;
				} else {
					$this->main->teleport($player);
				}
			} else {
				$player->sendMessage("§l§cこのワールドでは使えないアイテムです");
			}
		}
	}

	public function onReceive(DataPacketReceiveEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$pk = $event->getPacket();
		if($pk instanceof ModalFormResponsePacket){
			$id = $pk->formId;
			$data = $pk->formData;
			$result = json_decode($data);
			if($data == "null\n"){
			}else{
				if($id === 808080){
					if(!isset($this->main->item[$name])){
						$player->sendMessage("§l§c不具合が起きたので処理を終了します");

					} else if ($result[1] === ""){
						$player->sendMessage("§l§c入力されていません");

					} else if (mb_strlen($result[1]) > 10){
						$player->sendMessage("§l§c10文字以内にしてください");

					} else {
						$hand_item = $this->main->item[$name];
						unset($this->main->item[$name]);
						$x = round($player->x, 1);
						$y = round($player->y, 1);
						$z = round($player->z, 1);

						$world_name = $player->getLevel()->getFolderName();
						$inv = $player->getInventory();
						$hand_item->setNamedTagEntry(new FloatTag("x", round($player->x, 1)));
						$hand_item->setNamedTagEntry(new FloatTag("y", round($player->y, 1)));
						$hand_item->setNamedTagEntry(new FloatTag("z", round($player->z, 1)));
						$hand_item->setNamedTagEntry(new StringTag("world", round($world_name, 1)));
						$hand_item->setCustomName("§l§a".$result[1]);
						$hand_item->setLore(["座標: ".$x.":".$y.":".$z."", "ワールド: ".$world_name.""]);

						if($inv->canAddItem($hand_item)){
							$inv->removeItem($this->main->paper);
							$inv->addItem($hand_item);
							$player->sendMessage("§l§aテレポート先を登録しました");
						} else {
							$player->sendMessage("§l§cインベントリがいっぱいです");
						}
					}
				}
			}
		}
	}
}