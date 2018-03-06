<?php

namespace item;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\item\Item;
use pocketmine\inventory\Inventory;

class main extends PluginBase implements Listener{
	public function onEnale(){//Pluginが読み込まれた時
		$this->getServer()->getPluginManager()->registerEvents($this, $this);//event追加
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{//Command
		if($sender instanceof Player){//プレイヤーか判断
			if($label === "id"){//コマンドがidだったら
				$item = $sender->getInventory()->getItemInHand();//Playerの持ってるアイテムを取得
				$sender->sendMessage((string) $item);//Itemの数とか名前とかIDを表示
			}
		}else{
			$sender->sendMessage("§cゲーム内で行ってください");//Consoleに注意
		}
		return true;
	}
}