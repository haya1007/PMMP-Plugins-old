<?php

namespace kill_warn;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\level\Level;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\utils\Random;
use pocketmine\utils\UUID;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use kill_warn\task\checkWarn;

class main extends PluginBase implements Listener{
	public function onEnable(){
		if (!file_exists($this->getDataFolder())) {mkdir($this->getDataFolder());}

		$this->db = new DB($this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this, $this->db), $this);

		$this->getScheduler()->scheduleRepeatingTask(new checkWarn($this), 20);
	}

	public function Usage(Player $player){
		$player->sendMessage("§l§e====使い方====");
		$player->sendMessage("§l§a/warn check [PlayerName]");
		$player->sendMessage("§l§aプレイヤーのwarn数の確認");
		$player->sendMessage("§l§e===========");
		$player->sendMessage("§l§a/warn change [PlayerName] [Count]");
		$player->sendMessage("§l§aプレイヤーのwarn数を変更");
		$player->sendMessage("§l§e===========");
	}

	public function setDisplay(Player $player, string $tag){
		$remove = new RemoveEntityPacket();
		$remove->entityUniqueId = $player->getId();
		$pk = new AddPlayerPacket();
		$pk->uuid = $player->getUniqueId();
		$pk->username = $tag;
		$pk->entityRuntimeId = $player->getId();
		$pk->position = $player->asVector3();
		$pk->motion = $player->getMotion();
		$pk->yaw = $player->yaw;
		$pk->pitch = $player->pitch;
		$pk->item = $player->getInventory()->getItemInHand();
		$pk->metadata = $player->getDataPropertyManager()->getAll();
		foreach($this->getServer()->getOnlinePlayers() as $players){
			if($players->getId() !== $player->getId()){
				$players->dataPacket($remove);
				$players->dataPacket($pk);
			}
		}
	}

	public function NewNameTag(Player $player, int $warn){
		if($warn === 1){
			$color = "§e[△１]";
		}elseif($warn === 2){
			$color = "§5[△２]";
		}elseif($warn === 3){
			$color = "§c[△３]";
		}else{
			$color = "";
		}
		$display = $player->getDisplayName();
		$this->setDisplay($player, $color."§r ".$display);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($label === "warn"){
			if(!isset($args[0])){
				$this->Usage($sender);
			}else{
				switch($args[0]){
					case "help":
					case "h":
						$this->Usage($sender);
					break;
					case "check":
						if(isset($args[1])){
							$check_player = $this->getServer()->getPlayer($args[1]);
							if(isset($check_player)){
								$warn = $this->db->getWarn($check_player);
								$sender->sendMessage("§a[".$check_player->getName()."‘s Warn] §l".$warn);
								//DEBUG var_dump($warn);
							}else{
								$sender->sendMessage("§c§lその名前のプレイヤーは存在しません");
							}
						}else{
							$this->Usage($sender);
						}
					break;
					case "change":
						if(isset($args[1])){
							$check_player = $this->getServer()->getPlayer($args[1]);
							if(isset($check_player)){
								if(isset($args[2])){
									if(is_numeric($args[2])){
										$new_warn = (int) $args[2];
										$this->db->updateWarn($check_player, $new_warn);
										$sender->sendMessage("§a".$check_player->getName()."のWarnを§e".$new_warn."§aに変更しました");
									}else{
										$sender->sendMessage("§l§cCountは数字で入力してください");
									}
								}else{
									$this->Usage($player);
								}
							}else{
								$sender->sendMessage("§c§lその名前のプレイヤーは存在しません");
							}
						}else{
							$this->Usage($sender);
						}
					break;
					default:
						$this->Usage($sender);
					break;
				}
			}
		}
		return true;
	}

}