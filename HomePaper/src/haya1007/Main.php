<?php

namespace haya1007;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\inventory\Inventory;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;

class main extends PluginBase{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getLogger()->info("§aHomePaperを読み込みました");

    	$this->saveDefaultConfig();
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

		$this->paper = Item::get(339, 0, 1);
		$this->paper->setCustomName("§l§aHomePaper");
		$this->paper->setLore(["右クリックすることでその位置を記憶し、転送を可能とする"]);

		Item::addCreativeItem($this->paper);

		foreach (explode(":", $this->config->get("使えないワールド")) as $data) {
			$this->world[$data] = $data;
		}
	}

	#プレイヤーに渡すコード	
	public function give(Player $player){
		$inv = $player->getInventory();
		if($inv->canAddItem($this->paper)){
			$inv->addItem($this->paper);
			$player->sendMessage("§l§aHomePaperをインベントリに追加しました");
		} else {
			$player->sendMessage("§l§cインベントリがいっぱいです");
		}
	}

	#ワープ
	public function teleport(Player $player){
		$item = $player->getInventory()->getItemInHand();
		if($item->getNamedTagEntry("world") !== null){
			$x = $item->getNamedTagEntry("x")->getValue();
			$y = $item->getNamedTagEntry("y")->getValue();
			$z = $item->getNamedTagEntry("z")->getValue();
			$world = $item->getNamedTagEntry("world")->getValue();

			$player->teleport(new Position($x, $y, $z, $this->getServer()->getLevelByName($world)));
			$player->sendMessage("§l§aワーーーーープ！！！");
		}
	}

	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}
}