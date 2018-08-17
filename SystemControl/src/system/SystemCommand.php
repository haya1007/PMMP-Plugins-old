<?php

namespace system;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\PlayerInventory;

use pocketmine\math\Vector3;

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

class SystemCommand{
	private $command;

	public function _construct(string $pg){
		$this->command = $pg;
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args, $server) : bool{
		if($label === "id"){
			if($server->command->get("ItemID確認コマンド") === "op"){
				if(!$sender->isOp()){
			 		$sender->sendMessage("§cあなたには権限がありません");
			 	}else{
					$hand = $sender->getInventory()->getItemInHand();
					$sender->sendMessage((string) $hand);
			 	}
			}elseif($server->command->get("ItemID確認コマンド") === "true"){
				$hand = $sender->getInventory()->getItemInHand();
				$sender->sendMessage((string) $hand);
			}else{}
		}elseif($label === "reset"){
			if($server->command->get("Inventoryアイテム全消去コマンド") === "op"){
				if(!$sender->isOp()){
			 		$sender->sendMessage("§cあなたには権限がありません");
			 	}else{
			 		$server->resetInventory($sender);
					$sender->sendMessage("§7[System] >> アイテムを全て消去しました");
			 	}
			}elseif($server->command->get("Inventoryアイテム全消去コマンド") === "true"){
				$server->resetInventory($sender);
				$sender->sendMessage("§7[System] >> アイテムを全て消去しました");
			}else{}
		}elseif($label === "xyz"){
			if($server->command->get("座標確認コマンド") === "op"){
				if(!$sender->isOp()){
			 		$sender->sendMessage("§cあなたには権限がありません");
			 	}else{
			 		$server->checkXYZ($sender);
			 	}
			}elseif($server->command->get("座標確認コマンド") === "true"){
				$server->checkXYZ($sender);
			}else{}
		}elseif($label === "relog"){
			if($server->command->get("リログコマンド") === "op"){
				if(!$sender->isOp()){
			 		$sender->sendMessage("§cあなたには権限がありません");
			 	}else{
			 		$server->relog($sender);
			 	}
			}elseif($server->command->get("リログコマンド") === "true"){
				$server->relog($sender);
			}else{}
		}

		if($server->money->get("経済システム") === "true"){
			$name = $sender->getName();
			$tani = $server->money->get("お金の単位");
			if($label === "money"){
				$money = $server->getMoney($name);
				$sender->sendMessage("§bあなたの所持金: ".$money."".$tani);
			}elseif($label == 'addmoney'){
				if(count($args) === 0){
					$sender->sendMessage('/addmoney Player名 金額');
					return true;
				}elseif(count($args) === 1){
					$sender->sendMessage('/addmoney Player名 金額');
					return true;
				}
				if(!isset($args[2])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$plus = intval($args[1]);
	      				if($plus == true){
		      				$server->addMoney($pName, $plus);
		      				$sender->sendMessage('§a'.$pName.'に'.$plus.''.$tani.'を渡しました');
		      				$player->sendMessage('§a権限者の'.$name.'から'.$plus.''.$tani.'送られました');
		      			}else{
		      				$sender->sendMessage('§c数字にしてください');
		      			}	      				
	      			}else{
	      				$sender->sendMessage('そのプレイヤーはログインしていません');
	      			}
	      		}
			}elseif($label == 'removemoney'){
				if(count($args) === 0){
					$sender->sendMessage('/removemoney Player名 金額');
					return true;
				}elseif(count($args) === 1){
					$sender->sendMessage('/removemoney Player名 金額');
					return true;
				}
				if(!isset($args[2])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$remove = intval($args[1]);
	      				if($remove == true){
		      				$server->removeMoney($pName, $remove);
		      				$sender->sendMessage('§a'.$pName.'から'.$remove.''.$tani.'をとりました');
		      				$player->sendMessage('§a権限者の'.$name.'から'.$remove.''.$tani.'引かれました');
		      			}else{
		      				$sender->sendMessage('§c数字にしてください');
		      			}
	      			}else{
	      				$sender->sendMessage('そのプレイヤーはログインしていません');
	      			}
	      		}
	      	}elseif($label == 'setmoney'){
				if(count($args) === 0){
					$sender->sendMessage('/setmoney Player名 金額');
					return true;
				}elseif(count($args) === 1){
					$sender->sendMessage('/setmoney Player名 金額');
					return true;
				}
				if(!isset($args[2])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$set = intval($args[1]);
	      				if($set == true){
		      				$server->setMoney($pName, $set);
		      				$sender->sendMessage('§a'.$pName.'を'.$set.''.$tani.'に変更しました');
		      				$player->sendMessage('§a権限者の'.$name.'から'.$set.''.$tani.'に変更されました');
		      			}else{
		      				$sender->sendMessage('§c数字にしてください');
		      			}
	      			}else{
	      				$sender->sendMessage('そのプレイヤーはログインしていません');
	      			}
	      		}
	      	}elseif($label == 'seemoney'){
				if(count($args) === 0){
					$sender->sendMessage('/seemoney Player名');
					return true;
				}
				if(!isset($args[1])){
					$username = strtolower($args[0]);
	      			$player = $sender->getServer()->getPlayer($username);
	      			if(!$player == null){
	      				$pName = $player->getName();
	      				$money = $server->getMoney($pName);
						$sender->sendMessage('§l§b'.$pName.' : '.$money.''.$tani);
					}else{
						$sender->sendMessage('§cそのプレイヤーはログインしてません');
					}
				}
	      	}elseif($label == 'pay'){
	      		if($server->money->get("Payコマンド") === "true"){
					if(count($args) === 0){
						$sender->sendMessage('/pay Player名 金額');
						return true;
					}elseif(count($args) === 1){
						$sender->sendMessage('/pay Player名 金額');
						return true;
					}
					if(!isset($args[2])){
						$username = strtolower($args[0]);
						$have = $server->getMoney($name);
		      			$player = $sender->getServer()->getPlayer($username);
		      			$money = intval($args[1]);
		      			if(!$player == null){
		      				$pName = $player->getName();
		      				if(!$money == 0 and $money == true){
		      					if($have > $money){
		      						$player->sendMessage($name.'さんから'.$money.''.$tani.'渡されました');
		      						$server->addMoney($pName, $money);
		      						$sender->sendMessage($pName.'さんに'.$money.''.$tani.'渡しました');
		      						$server->sendremoveMoney($name, $money);
		      					}else{
		      						$sender->sendMessage('所持金が足りません');
		      					}
		      				}else{
		      					$sender->sendMessage('渡すお金を設定してください');
		      				}
		      			}else{
		      				$sender->sendMessage('そのプレイヤーはログインしていません');
		      			}
		      		}
		      	}elseif($server->money->get("Payコマンド") === "op"){
					if(count($args) === 0){
						$sender->sendMessage('/pay Player名 金額');
						return true;
					}elseif(count($args) === 1){
						$sender->sendMessage('/pay Player名 金額');
						return true;
					}elseif(!$sender->isOp()){
						$sender->sendMessage("§cあなたには権限がありません");
						return true;
					}
					if(!isset($args[2])){
						$username = strtolower($args[0]);
						$have = $server->getMoney($name);
		      			$player = $sender->getServer()->getPlayer($username);
		      			$money = intval($args[1]);
		      			if(!$player == null){
		      				$pName = $player->getName();
		      				if(!$money == 0 and $money == true){
		      					if($have > $money){
		      						$player->sendMessage($name.'さんから'.$money.''.$tani.'渡されました');
		      						$server->addMoney($pName, $money);
		      						$sender->sendMessage($pName.'さんに'.$money.''.$tani.'渡しました');
		      						$server->sendremoveMoney($name, $money);
		      					}else{
		      						$sender->sendMessage('所持金が足りません');
		      					}
		      				}else{
		      					$sender->sendMessage('渡すお金を設定してください');
		      				}
		      			}else{
		      				$sender->sendMessage('そのプレイヤーはログインしていません');
		      			}
		      		}
		      	}
		    }
		      	if($server->money->get("土地保護") === "true"){
		      		if($label === 'land'){
						if(!isset($args[0])){
							$sender->sendMessage('/land e : 範囲データを消去します');
							$sender->sendMessage('/land buy : 土地を購入します');
							$sender->sendMessage('/land set : 買う土地を指定します');
							$sender->sendMessage('/land remove : 自分の土地を売却します');
						}elseif($args[0] === 'e'){
							if(isset($server->setting[$name])){
								unset($server->setting[$name]);
								$sender->sendMessage('設定した座標のデータを削除しました');
							}else{
								$sender->sendMessage('座標のデータは設定されていません');
							}
						}elseif($args[0] === 'buy'){
							if(isset($server->setting[$name]['max'], $server->setting[$name]['min'])){
								$money = $server->getMoney($name);
								$total = $server->change($name) * 20;
								if($money >= $total){
									$setting = $server->setting[$name];
										$all = $server->config['land']->getAll();
										$all[] = [
											'max'    => $max = $setting['max'],
											'min'    => $setting['min'],
											'buyer'  => strtolower($name),
											'money'  => $total,
											'friend' => []
										];
										$server->config['land']->setAll($all);
										$server->config['land']->save();
										$number = $server->getNumber($max[0], $max[1]);
										$server->addLand($name, $number);
										$server->removeMoney($name, $total);
										$sender->sendMessage('土地を'.$total.''.$server->money->get("お金の単位").'で購入しました');
										$server->config[$sender->getName()]->save();
										unset($server->setting[$name]);
								}else{
									$sender->sendMessage('§cお金が足りないため購入できません');
								}
							}else{
								$sender->sendMessage('購入する範囲を囲ってください');
							}
						}elseif($args[0] === 'remove'){
							$x = $sender->x;
							$z = $sender->z;
							$number = $server->getNumber($x, $z);
							$land = $server->config['land']->get($number);
							$lower = strtolower($name);
							if($land['buyer'] === $lower or $sender->isOp()){
								$money = $land['money'] / 2;
								$server->addMoney($name, $money);
								$sender->sendMessage('土地を売却したので、'.$money.''.$server->money->get("お金の単位").'が返金されました');
								$server->config['land']->remove($number);
							}else{
								$sender->sendMessage('§c自分の土地以外売却できません');
							}
						}elseif($args[0] === 'set'){
							$level = $sender->getLevel();
							$level_name = $level->getFolderName();
							if(!isset($server->world[$level_name])) {
								$event = $level->getBlock(new Vector3($sender->x, $sender->y - 1, $sender->z));
								if(isset($server->setting[$name])){
									$server->isSetting($event, $sender, 'max', 'min', "a");
								}else{
									$server->isSetting($event, $sender, 'min', 'max', "a");
								}
							}else{
								$sender->sendMessage("§c§lこのワールドは土地保護が禁止です");
							}
						}elseif($args[0] === 'here'){
							$x = round($sender->getX(), 1);
							$z = round($sender->getZ(), 1);
							$number = $server->getNumber($x, $z);
							if(isset($number)){
								$buyer = $server->config['land']->get($number)['buyer'];
								$sender->sendMessage('§cこの土地は'.$buyer.'が購入しています');
							}else{
								$sender->sendMessage("§l§cここは誰の土地ではありません");
							}
						}else{
							$sender->sendMessage('/land e : 範囲データを消去します');
							$sender->sendMessage('/land buy : 土地を購入します');
							$sender->sendMessage('/land set : 買う土地を指定します');
							$sender->sendMessage('/land remove : 自分の土地を売却します');							
						}
					}
				}
				if($server->money->get("Job機能") === "true"){
					if($label === "job"){
						if(!isset($args[0])){
							$sender->sendMessage("§a/job join <職業名> : 職業を決める");
							$sender->sendMessage("§a/job list : 職業一覧");
							$sender->sendMessage("§a/job me : 自分の職業確認");
							$sender->sendMessage("§a/job retire : 職業を辞める");
						}else{
							switch($args[0]){
								case "join":
									if(!isset($args[1])){
										$sender->sendMessage("§a/job join <職業名> : 職業を決める");										
									}else{
										if($args[1] === "tree_cutter" or $args[1] === "miner"){
											$server->config[$name]->set("job", $args[1]);
											$server->config[$name]->save();
											$sender->sendMessage("§aあなたは".$args[1]."になりました");
										}else{
											$sender->sendMessage("§cそのような職業は御座いません");
										}
									}
								break;

								case "list":
									$sender->sendMessage("§b職業名 : もらえるお金 :　職業説明");
									$sender->sendMessage("§btree_cutter : 10 : 木を切る職業");
									$sender->sendMessage("§bminer: 5 : 石を掘る職業");
								break;

								case "me":
									$job = $server->config[$name]->get("job");
									if($job === ""){
										$sender->sendMessage("§aあなたは職業に就いていません");
									}else{
										$sender->sendMessage("§aあなたは".$job."に就いています");
									}
								break;

								case "retire":
									$job = $server->config[$name]->get("job");
									$server->config[$name]->set("job", "");
									$server->config[$name]->save();
									if($job === ""){
										$sender->sendMessage("§aあなたは職業に就いていません");
									}else{
										$sender->sendMessage("§aあなたは".$job."を辞めました");									
									}
								break;
							}
						}
					}
				}
		}
		return true;
	}
}