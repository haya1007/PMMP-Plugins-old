<?php

namespace system;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\scheduler\PluginTask;
//use pocketmine\scheduler\CallbackTask;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\DataPropertyManager;
use pocketmine\entity\Attribute;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Zombie;
use pocketmine\entity\Skeleton;
use pocketmine\entity\Enderman;
use pocketmine\entity\Villager;
use pocketmine\entity\PigZombie;
use pocketmine\entity\Creeper;
use pocketmine\entity\Spider;
use pocketmine\entity\Witch;
use pocketmine\entity\IronGolem;
use pocketmine\entity\Blaze;
use pocketmine\entity\Slime;
use pocketmine\entity\WitherSkeleton;
use pocketmine\entity\Horse;
use pocketmine\entity\Donkey;
use pocketmine\entity\Mule;
use pocketmine\entity\SkeletonHorse;
use pocketmine\entity\ZombieHorse;
use pocketmine\entity\Stray;
use pocketmine\entity\Husk;
use pocketmine\entity\Human;
use pocketmine\entity\Mooshroom;
use pocketmine\entity\FallingSand;
use pocketmine\entity\Item as DroppedItem;
use pocketmine\entity\Skin;
use pocketmine\entity\projectile\Snowball;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\ItemFrameDropItemEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryPickupArrowEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerTextPreSendEvent;
use pocketmine\event\player\PlayerAchievementAwardedEvent;
use pocketmine\event\player\PlayerAnimationEvent;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerBedLeaveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerHungerChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerToggleSprintEvent;
use pocketmine\event\player\PlayerUseFishingRodEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\TextContainer;
use pocketmine\event\Timings;
use pocketmine\event\TranslationContainer;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\AnvilInventory;
use pocketmine\inventory\BaseTransaction;
use pocketmine\inventory\BigShapedRecipe;
use pocketmine\inventory\BigShapelessRecipe;
use pocketmine\inventory\CraftingManager;
use pocketmine\inventory\DropItemTransaction;
use pocketmine\inventory\EnchantInventory;
use pocketmine\inventory\FurnaceInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\enchantment\ProtectionEnchantment;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Armor;
use pocketmine\item\FoodSource;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\item\Durable;
use pocketmine\level\ChunkLoader;
use pocketmine\level\Explosion;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\WeakPosition;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\metadata\MetadataValue;
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
use pocketmine\network\Network;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\AddHangingEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemPacket;
use pocketmine\network\mcpe\protocol\AddPaintingPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\BlockPickRequestPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\ChunkRadiusUpdatedPacket;
use pocketmine\network\mcpe\protocol\ClientboundMapItemDataPacket;
use pocketmine\network\mcpe\protocol\ClientToServerHandshakePacket;
use pocketmine\network\mcpe\protocol\CommandBlockUpdatePacket;
use pocketmine\network\mcpe\protocol\CommandStepPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\ContainerSetDataPacket;
use pocketmine\network\mcpe\protocol\ContainerSetSlotPacket;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\CraftingEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\DropItemPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\ExplodePacket;
use pocketmine\network\mcpe\protocol\FullChunkDataPacket;
use pocketmine\network\mcpe\protocol\HurtArmorPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryActionPacket;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\MapInfoRequestPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\EntityFallPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\RemoveBlockPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\ReplaceItemInSlotPacket;
use pocketmine\network\mcpe\protocol\RequestChunkRadiusPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkDataPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkRequestPacket;
use pocketmine\network\mcpe\protocol\ResourcePackClientResponsePacket;
use pocketmine\network\mcpe\protocol\ResourcePackDataInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePacksInfoPacket;
use pocketmine\network\mcpe\protocol\RespawnPacket;
use pocketmine\network\mcpe\protocol\RiderJumpPacket;
use pocketmine\network\mcpe\protocol\ServerToClientHandshakePacket;
use pocketmine\network\mcpe\protocol\SetCommandsEnabledPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\network\mcpe\protocol\SetEntityDataPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\SetEntityMotionPacket;
use pocketmine\network\mcpe\protocol\SetHealthPacket;
use pocketmine\network\mcpe\protocol\SetPlayerGameTypePacket;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\network\mcpe\protocol\SetTitlePacket;
use pocketmine\network\mcpe\protocol\ShowCreditsPacket;
use pocketmine\network\mcpe\protocol\SpawnExperienceOrbPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\network\mcpe\protocol\UnknownPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\UpdateTradePacket;
use pocketmine\network\mcpe\protocol\UseItemPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\SourceInterface;
use pocketmine\permission\PermissibleBase;
use pocketmine\permission\PermissionAttachment;
use pocketmine\plugin\Plugin;
use pocketmine\tile\ItemFrame;
use pocketmine\tile\Sign;
use pocketmine\tile\Spawnable;
use pocketmine\tile\Tile;
use pocketmine\utils\Binary;
use pocketmine\utils\Config;
use pocketmine\utils\Color;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use pocketmine\Player;
use pocketmine\Server;

class main extends PluginBase implements Listener{
	function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){mkdir($this->getDataFolder(), 0744, true);}
		if(!file_exists($this->getDataFolder()."relog/")){mkdir($this->getDataFolder()."relog/", 0744, true);}
		if(!file_exists($this->getDataFolder()."PlayerData/")){mkdir($this->getDataFolder()."PlayerData/", 0744, true);}
		if(!file_exists($this->getDataFolder()."money/")){mkdir($this->getDataFolder()."money/", 0744, true);}
		if(!file_exists($this->getDataFolder()."level/")){mkdir($this->getDataFolder()."level/", 0744, true);}
		$this->server = new Config($this->getDataFolder().'setting.yml', Config::YAML, array(
			"ブロック破壊無効化" => 'op',
			"ブロック設置無効化" => 'op',
			"アイテムクラフト無効化" => 'op',
			"アイテムを投げる無効化" => 'op',
			"落下ダメージ無効化" => 'true',
			"死亡時にアイテム保持" => 'true',
			"プレイヤーチャット" => 'op',
			"OP以外のゲームモードを設定にする(鯖に入った時)" => "2",
			"防具の耐久値が無限" => "true",
			"死んだときのメッセージを消去" => "true",
			"ステータスバーを表示" => "true",
			"プレイヤーがサーバーに入った時初期リス地でスポーン" => "true",
			"攻撃できないワールドを設定" => "world:life",
			"killコマンド無効化" => "op"
			#"編集できないワールドを設定" => "world:life"
		));

		$this->command = new Config($this->getDataFolder().'command.yml', Config::YAML, array(
			"ItemID確認コマンド" => 'op',
			"Inventoryアイテム全消去コマンド" => 'op',
			"座標確認コマンド" => 'op',
			"リログコマンド" => 'op'
		));

		$this->relog = new Config($this->getDataFolder().'relog\relog.yml', Config::YAML, array(
			"ServerIP" => "0.0.0.0",
			"ServerPort" => "19132"
		));

		$this->money = new Config($this->getDataFolder().'money/money.yml', Config::YAML, array(
			"経済システム" => "true",
			"お金の単位" => "M",
			"Payコマンド" => "true",
			"アイテムショップ" => "true",
			"敵を倒したときにお金を入手" => "true",
			"Job機能" => "true",
			"土地保護" => "true",
			"土地保護できない土地を設定" => "world:life",
			"保護しないとワールドを編集できないかどうか" => "true",
			"一つの場所を保護する際にかかる値段" => 20
		));

		foreach (explode(":", $this->money->get("土地保護できない土地を設定")) as $data) {
			$this->world[$data] = $data;
		}

		foreach (explode(":", $this->money->get("編集できないワールドを設定")) as $data) {
			$this->world2[$data] = $data;
		}

		foreach (explode(":", $this->server->get("攻撃できないワールドを設定")) as $data) {
			$this->world3[$data] = $data;
		}

		$this->job = new Config($this->getDataFolder().'money/job.yml', Config::YAML, array(
			"tree_cutter" => "10",
			"miner" => "5"
		));

		$this->level = new Config($this->getDataFolder().'level/level.yml', Config::YAML, array(
			"レベルシステム" => "true",
			"名前にレベルを表示" => "true"
		));

		$this->b = new Config($this->getDataFolder()."money/shop.yml",Config::YAML);
		$this->config['land'] = new Config($this->getDataFolder().'money/land.json', Config::JSON);

		if($this->server->get("防具の耐久値が無限") === "true"){
			$this->getServer()->getScheduler()->scheduleRepeatingTask(new ArmorUnbreaking($this), 10);
		}
	}

	public function onDisable(){
		foreach($this->config as $data) $data->save();
	}

	function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$c = new SystemCommand($this);
		$c->onCommand($sender, $command, $label, $args, $this);
		return true;
	}

	function Break(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$setting = $this->server->get("ブロック破壊無効化");
		$land = $this->money->get("土地保護");
		$level_name = $player->getLevel()->getFolderName();
		if($setting === "true"){
			if(!$land === "true"){
				$event->setCancelled();
			}else{
				/*if (isset($this->world2[$level_name])) {
					$event->setCancelled();
					$player->sendPopup("§c§lこのワールドは編集できません");
				}else{
				}*/
				$this->isSetting($event, $player);
			}
			#$event->setCancelled();
		}elseif($setting === "op"){
			if(!$player->isOp()){
				if(!$land === "true"){
					$event->setCancelled();
				}else{
					/*if (isset($this->world2[$level_name])) {
						$event->setCancelled();
						$player->sendPopup("§c§lこのワールドは編集できません");
					}else{
						$this->isSetting($event, $player);
					}*/
					$this->isSetting($event, $player);
				}
				#$event->setCancelled();
			}
		}
		if($this->money->get("アイテムショップ") === "true"){
			$block = $event->getBlock();
			$var = (Int)$event->getBlock()->getX().":".(Int)$event->getBlock()->getY().":".(Int)$event->getBlock()->getZ().":".$block->getLevel()->getFolderName();
	        if($this->b->exists($var)){
	            if($player->isOp()){
	                $this->b->remove($var);
	                $this->b->save();
		            $player->sendPopup("§aSHOPを解体しました");
	            }else{
	            	$player->sendMessage("§cショップは破壊できません");
	                $event->setCancelled();
	            }
	        }
	    }
	    if($this->money->get("Job機能") === "true"){
			$block = $event->getBlock();
			$id = $block->getId();
	    	$job = $this->config[$player->getName()]->get("job");
	    	if($job === "tree_cutter"){
	    		$plus = $this->job->get("tree_cutter");
	    		if($id === Block::WOOD or $id === Block::WOODEN_PLANKS or $id === Block::WOOD2){
	    			$this->addMoney($player->getName(), $plus);
	    		}
	    	}elseif($job === "miner"){
	    		$plus = $this->job->get("miner");
	    		if($id === Block::STONE){
	    			$this->addMoney($player->getName(), $plus);
	    		}
	    	}
	    }
	}

	function Place(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		$setting = $this->server->get("ブロック設置無効化");
		$land = $this->money->get("土地保護");
		$level_name = $player->getLevel()->getFolderName();
		if($setting === "true"){
			if($land === "true"){
				/*if (isset($this->world2[$level_name])) {
					$event->setCancelled();
					$player->sendPopup("§c§lこのワールドは編集できません");
				}else{
					$this->isSetting($event, $player);
				}*/
				$this->isSetting($event, $player);
			}else{
				$event->setCancelled();
			}
			#$event->setCancelled();
		}elseif($setting === "op"){
			if(!$player->isOp()){
				if($land === "true"){
					/*if (isset($this->world2[$level_name])) {
						$event->setCancelled();
						$player->sendPopup("§c§lこのワールドは編集できません");
					}else{
						$this->isSetting($event, $player);
					}*/
					$this->isSetting($event, $player);
				}else{
					$event->setCancelled();
				}
				#$event->setCancelled();
			}
		}
	}

	function Carft(CraftItemEvent $event){
		$player = $event->getPlayer();
		$setting = $this->server->get("アイテムクラフト");
		if($setting === "true"){
			$event->setCancelled();
		}elseif($setting === "op"){
			if(!$player->isOp()){
				$event->setCancelled();
			}
		}	
	}

	function Chat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		$setting = $this->server->get("プレイヤーチャット");
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
		$setting = $this->server->get("アイテムを投げる");
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
			$level = $entity->getLevel()->getFolderName();
			if(!isset($this->world3[$level])){
				$cause = $event->getCause();
				if($cause === 4){
					$setting = $this->server->get("落下ダメージ無効化");
					if($setting === "true"){
						$event->setCancelled();
					}
				}
			}else{
				$event->setCancelled();
			}
		}
	}

	function Death(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if($this->server->get("死亡時にアイテム保持") === "true"){
			$event->setKeepInventory(true);
			/*$a0 = $player->getArmorInventory()->getHelmet();
			$a1 = $player->getArmorInventory()->getChestplate();
			$a2 = $player->getArmorInventory()->getLeggings();
			$a3 = $player->getArmorInventory()->getBoots();
		    $this->drops[$name][0] = $a0;
		    $this->drops[$name][1] = $a1;
		    $this->drops[$name][2] = $a2;
		    $this->drops[$name][3] = $a3;
		    $this->drops[$name][4] = $player->getInventory()->getContents();
		    $event->setDrops(array());*/
		}
		if($this->server->get("死んだときのメッセージを消去") === "true"){
			$event->setDeathMessage(null);
		}

		$entity = $event->getEntity();
		$cause = $entity->getLastDamageCause();
		if($cause instanceof EntityDamageByEntityEvent){
			$damage = $cause->getDamager();
			if($damage instanceof Player){
				$entity_name = $entity->getName();
				$damage_name = $damage->getName();
				if($this->level->get("レベルシステム") === "true"){
					$entity_level = $this->getLevel($entity_name);
					$damage->sendMessage("§7LevelSystem >> ".$entity_level."Exp を入手しました");
					$this->addExpLevel($damage, intval($entity_level*1.5));
				}

				if($this->money->get("敵を倒したときにお金を入手") === "true"){
					$entity_money = $this->getMoney($entity_name);
					$get = intval($entity_money / 100);
					$damage->sendMessage("§7MoneySystem >> ".$get."".$this->money->get("お金の単位")." を入手しました");
					$this->addMoney($damage_name, $get);
				}

				$this->config[$entity_name]->set("death", $this->config[$entity_name]->get("death") + 1);
				$this->config[$damage_name]->set("kill", $this->config[$damage_name]->get("kill") + 1);
			}
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
	}

	function Join(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		if($this->server->get("OP以外のゲームモードを設定にする") === 0){
			if(!$player->isOp()){
				$player->setGamemode(0);
			}
		}elseif($this->server->get("OP以外のゲームモードを設定にする") === 1){
			if(!$player->isOp()){
				$player->setGamemode(1);
			}
		}elseif($this->server->get("OP以外のゲームモードを設定にする") === 2){
			if(!$player->isOp()){
				$player->setGamemode(2);
			}
		}elseif($this->server->get("OP以外のゲームモードを設定にする") === 3){
			if(!$player->isOp()){
				$player->setGamemode(3);
			}
		}

		//制限関係なしに作成
		$name = $player->getName();
		$folder = $this->getFolder($name);
		$this->config[$name] = new Config($folder, Config::JSON, [
			"name" => $name,
			"money" => 500,
			"level" => 1,
			"exp" => 0,
			"land" => [],
			"job" => "",
			"kill" => 0,
			"death" => 0
		]);
		$this->config[$name]->save();

		if($this->server->get("プレイヤーがサーバーに入った時初期リス地でスポーン") === "true"){
			$x = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getX();
			$y = $this->getServer()->getDefaultLevel()->getSafeSpawn()-> getY();
			$z = $this->getServer()->getDefaultLevel()->getSafeSpawn()->getZ();
			$level = $this->getServer()->getDefaultLevel();
			$player->setLevel($level);
			$player->teleport(new Vector3($x, $y, $z, $level));
		}

		if($this->level->get("レベルシステム") === "true" and $this->level->get("名前にレベルを表示") === "true"){
			$level = $this->getLevel($name);
			$player->setNameTag('§eLv.'.$level.' §l§f'.$name.'§r');	
			$player->setDisplayName('§eLv.'.$level.' §l§f'.$name.'§r');
		}

		if($this->server->get("ステータスバーを表示") === "true"){
			$this->status[$name] = $this->getServer()->getScheduler()->scheduleRepeatingTask(new StatusBar($this, $player), 10);
		}
	}

	function Quit(PlayerQuitEvent $event){
		$name = $event->getPlayer()->getName();
		if(isset($this->drops[$name])){
			unset($this->drops[$name]);
		}
		if(isset($this->shop[$name])){
			unset($this->shop[$name]);
		}
		if(isset($this->buy[$name])){
			unset($this->buy[$name]);
		}
		if(isset($this->status[$name])){
			unset($this->status[$name]);
		}
		$this->config[$name]->save();
	}

	function PlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$message = $event->getMessage();
		$sub = substr($message, 0, 1);
		$level = $this->getLevel($name);
		$display = $player->getDisplayName();
		if($sub === '/'){
			if($this->server->get("killコマンド無効化") === "true"){
				if(strpos($message, '/kill') !== false){
					$event->setCancelled();
					$player->sendMessage("§l§cこのコマンドは使用できません");
				}
			}elseif($this->server->get("killコマンド無効化") === "op"){
				if(!$player->isOp()){
					if(strpos($message, '/kill') !== false){
						$event->setCancelled();
						$player->sendMessage("§l§cこのコマンドは使用できません");
					}					
				}
			}
		}
	}

	function Tap(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$block = $event->getBlock();
		$id = $block->getId();
		$inv = $player->getInventory();
		$var = $block->getX().":".$block->getY().":".$block->getZ().":".$block->getLevel()->getFolderName();
		if($this->b->exists($var)){
			if($event->getAction() === 1){
				$b = $this->b->getAll();
				$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
				$id = $b[$var]["id"];
				$money = $b[$var]["money"];
				$number = $b[$var]["number"];
				if(!isset($this->shop[$name])){
					$player->sendMessage("§a購入する場合は、再度タップしてください");
					$this->shop[$name] = "true";
					$this->getServer()->getScheduler()->scheduleDelayedTask(new AgainTap($this, $name), 2 * 20);
				}else{
					$player_money = $this->getMoney($name);
					$item = Item::fromString($id);
					if($player_money >= $money){
						$add_item = Item::get($item->getId(), $item->getDamage(), $number);
						if($player->getInventory()->canAddItem($add_item)){
							if(!isset($this->buy[$name])){
								$this->buy[$name] = "true";
								$this->removeMoney($name, $money);
								$inv->addItem($add_item);
								$player->sendMessage("§a購入しました");
								$this->getServer()->getScheduler()->scheduleDelayedTask(new buy($this, $name), 2 * 20);
							}
						}else{
							$player->sendMessage("§cインベントリに空きがありません");
						}
					}else{
						$player->sendMessage("§cお金が足りません");
					}
				}
			}
		}
	}

//---------------------MoneySystem関連----------------------------------

    function onSignChange(SignChangeEvent $event){
    	if($this->money->get("アイテムショップ") === "true"){
			$result = $event->getLine(0);
			$block = $event->getBlock();
			if($result == "shop"){
				$player = $event->getPlayer();
				if(!$player->isOp()){
					$player->sendMessage("§c権限がありません");
					return;
	            }
	            if(is_numeric($event->getLine(2)) and is_numeric($event->getLine(3))){
					$var = (Int)$event->getBlock()->getX().":".(Int)$event->getBlock()->getY().":".(Int)$event->getBlock()->getZ().":".$block->getLevel()->getFolderName();
					$block = $event->getBlock();
	          	 	$id = $event->getLine(1);
	          	  	$money = $event->getLine(2);
	            	$number = $event->getLine(3);
	          	    $this->b->set($var, [
						"x" => $block->getX(),
						"y" => $block->getY(),
						"z" => $block->getZ(),
						"level" => $block->getLevel()->getFolderName(),
						"money" => $money,
						"id" => $id,
						"number" => $number
					]);
	                $this->b->save();
					$player->sendMessage("§aSHOPを作成しました");
	                $itemName = Item::fromString($id)->getName();
					$event->setLine(0, "§b[SHOP]"); // TAG
					$event->setLine(1, "§e商品:".$itemName);
					$event->setLine(2, "§e値段: ".$money."".$this->money->get("お金の単位")); 
					$event->setLine(3, "§e個数: ".$number."個"); 
				}else{}
			}
		}
	}
//---------------------CommandFunction----------------------------------

	function resetInventory($player){
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->setHelmet(Item::get(0));
		$player->getArmorInventory()->setChestPlate(Item::get(0));
		$player->getArmorInventory()->setLeggings(Item::get(0));
		$player->getArmorInventory()->setBoots(Item::get(0));
	}

	function checkXYZ($player){
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$X = round($x, 1);
		$Y = round($y, 1);
		$Z = round($z, 1);
		$player->sendMessage('§eあなたの座標  §lX:'.$X.' Y:'.$Y.' Z:'.$Z);
	}

	function relog($player){
		$pk = new TransferPacket();
		$pk->address = $this->relog->get("ServerIP");
		$pk->port = $this->relog->get("ServerPort");
		$player->dataPacket($pk);
	}

//-------------------OtherFunction--------------------------

	public function getFolder($name){
		$sub = substr($name, 0, 1);
		$upper = strtoupper($sub);
		$folder = $this->getDataFolder().'PlayerData/'.$upper.'/';
		if(!file_exists($folder)) mkdir($folder);
		$lower = strtolower($name);
		return $folder .= $lower.'.json';
	}

	public function addExpLevel($player, $exp){
		$name = $player->getName();
		$player1 = $this->getServer()->getPlayer($name);
		$this->addExp($name, $exp);
		$exp = $this->getExp($name);
		$old = $this->getLevel($name);
		$new = $old;
		while($exp >= $this->getExpectedExperience($new)) ++$new;
		while($exp < $this->getExpectedExperience($new - 1)) --$new;
		$this->setLevel($name, $new);
		if($old < $new){
			$pk = new SetTitlePacket();
			$pk->type = SetTitlePacket::TYPE_SET_TITLE;
			$pk->text = '§eレベルｱｯﾌﾟ Lv.'.$old.' -> Lv.'.$new.'';
			$player1->dataPacket($pk);
			$new_level = $new - $old;
			if($this->level->get("名前にレベルを表示") == "true"){
				$player1->setNameTag('§eLv.'.$new.' §l§f'.$name.'§r');	
				$player1->setDisplayName('§eLv.'.$new.' §l§f'.$name.'§r');
			}					
			$this->config[$player1->getName()]->save();
		}
	}

	public function getLevelUpExpectedExperience($level, $exp){
		$expected = $this->getExpectedExperience($level);
		return $expected - $exp;
	}

	public function getExpectedExperience($level){
		return $level ** 3 * 3;
	}

	public function getLevel($name){
		return $this->config[$name]->get("level");
	}

	public function getExp($name){
		return $this->config[$name]->get("exp");
	}

	public function addExp($name, $exp){
		$exp = $this->getExp($name) + $exp;
		$this->config[$name]->set("exp", $exp);
	}

	public function addLevel($name,$level){
		$plus = $this->getLevel($name) + $level;
		$this->setLevel($name,$plus);
	}

	public function setLevel($name,$level){
		$this->config[$name]->set("level", $level);
	}

	public function getMoney($name){
		return $this->config[$name]->get("money");
	}

	public function setMoney($name,$money){
		$this->config[$name]->set("money", $money);
	}

	public function addMoney($name,$money){
		$plus = $this->getMoney($name) + $money;
		$this->setMoney($name,$plus);
	}

	public function removeMoney($name,$money){
		$remove = $this->getMoney($name) - $money;
		$this->setMoney($name,$remove);
	}

	public function sendremoveMoney($sendname, $money){
		$money = $this->getMoney($sendname) - $money;
		$this->setMoney($sendname, $money);
	}

	public function addLand($name, $number){
		$land  = $this->getLand($name);
		$land[$number] = $number;
		$this->config[$name]->set('land', $land);
	}

	public function getLand($name){
		return $this->config[$name]->get('land');
	}

	public function isSetting($event, $player, $max = null, $min = null, $check = null){
		$name = $player->getName();
		$land = $this->getLand($name);
		if(is_null($check)){
			$block = $event->getBlock();
			$x = $block->x;
			$y = $block->y;
			$z = $block->z;
			$block = $player->getLevel()->getBlock(new Vector3($x, $y, $z));
		}else{
			$block = $event;
		}
		$x = $block->x;
		$z = $block->z;
		$number = $this->getNumber($x, $z);
		if(isset($number)){
			/*if(!isset($land[$number])){
				if(is_null($check)){
					$event->setCancelled();
				}
				$buyer = $this->config['land']->get($number)['buyer'];
				$player->sendTip('§cこの土地は'.$buyer.'が購入しています');
			}*/
			$buyer = $this->config['land']->get($number)['buyer'];
			if(!strtolower($name) === $buyer){
				if(is_null($check)){
					$event->setCancelled();
					$player->sendTip('§cこの土地は'.$buyer.'が購入しています');
				}	
			}
		}elseif(isset($max, $min) and !isset($this->setting[$name][$max])){
			$this->setting[$name][$max] = [$x, $z];
			if(isset($this->setting[$name][$min])){
				$change = $this->change($name);
				$max = $this->setting[$name]['max'];
				$min = $this->setting[$name]['min'];
				for($x = $min[0]; $x <= $max[0]; ++$x){
					for($z = $min[1]; $z <= $max[1]; ++$z){
						$number = $this->getNumber($x, $z);
						if(isset($number)){
							$player->sendMessage('§7範囲内に購入者の土地があります (範囲をリセットしました)');
							unset($this->setting[$name]);
							return;
						}
					}
				}
				$money = $change * $this->money->get("一つの場所を保護する際にかかる値段");
				$player->sendMessage('座標Bが設定されました '.$x.', '.$z.' (合計: '.$change.' 値段: '.$money.''.$this->money->get("お金の単位").')');
				$player->sendMessage('購入する場合は "/land buy" : 範囲をリセットする場合は "/land e"');
			}else{
				$player->sendMessage('座標Aが設定されました '.$x.', '.$z);
			}
		}elseif(!$player->isOp()){
			if($this->money->get("保護しないとワールドを編集できないかどうか") === "true"){
				if(is_null($check)){
					$event->setCancelled();
				}
				if(!isset($this->world[$player->getLevel()->getFolderName()])){
					$player->sendPopup('§c土地を購入してください');
				}
			}
		}
	}

	public function getNumber($x, $z){
		$all = $this->config['land']->getAll();
		foreach($all as $key => $value){
			$max = $value['max'];
			$min = $value['min'];
			if($max[0] >= $x and $min[0] <= $x and $max[1] >= $z and $min[1] <= $z) return $key;
		}
		return null;
	}

	public function change($name){
		$setting = $this->setting[$name];
		$max = $setting['max'];
		$min = $setting['min'];
		$x_max = max($max[0], $min[0]);
		$z_max = max($max[1], $min[1]);
		$x_min = min($max[0], $min[0]);
		$z_min = min($max[1], $min[1]);
		$this->setting[$name]['max'] = [$x_max, $z_max];
		$this->setting[$name]['min'] = [$x_min, $z_min];
		$x_range = ($x_max - $x_min) + 1;
		$z_range = ($z_max - $z_min) + 1;
		return $x_range * $z_range;
	}
}

class AgainTap extends PluginTask{//ショップ

	function __construct(PluginBase $owner, $name){
		parent::__construct($owner);
		$this->name = $name;
	}

	function onRun(int $currentTick){
		if(isset($this->getOwner()->shop[$this->name])){
			unset($this->getOwner()->shop[$this->name]);
		}
	}
}

class buy extends PluginTask{//ショップ

	function __construct(PluginBase $owner, $name){
		parent::__construct($owner);
		$this->name = $name;
	}

	function onRun(int $currentTick){
		if(isset($this->getOwner()->buy[$this->name])){
			unset($this->getOwner()->buy[$this->name]);
		}
	}
}

class ArmorUnbreaking extends PluginTask{//防具

	function __construct(PluginBase $owner){
		parent::__construct($owner);
	}

	function onRun(int $currentTick){
		foreach($this->getOwner()->getServer()->getOnlinePlayers() as $players){
			$a0 = $players->getArmorInventory()->getHelmet();
			$a1 = $players->getArmorInventory()->getChestplate();
			$a2 = $players->getArmorInventory()->getLeggings();
			$a3 = $players->getArmorInventory()->getBoots();
			$a0->setDamage(0);
			$a1->setDamage(0);
			$a2->setDamage(0);
			$a3->setDamage(0);
			$players->getArmorInventory()->setHelmet($a0);
			$players->getArmorInventory()->setChestplate($a1);
			$players->getArmorInventory()->setLeggings($a2);
			$players->getArmorInventory()->setBoots($a3);
		}
	}
}

class StatusBar extends PluginTask{//ステータスバー

	function __construct(PluginBase $owner, $player){
		parent::__construct($owner);
		$this->player = $player;
	}

	function onRun(int $currentTick){
		$player = $this->player;
		$name = $player->getName();
		$money = $this->getOwner()->getMoney($name);
		$level = $this->getOwner()->getLevel($name);
		$exp = $this->getOwner()->getExp($name);
		$up = $this->getOwner()->getLevelUpExpectedExperience($level, $exp);
		$kill = $this->getOwner()->config[$name]->get("kill");
		$death = $this->getOwner()->config[$name]->get("death");
		$job = $this->getOwner()->config[$name]->get("job");
		if($job === ""){
			$job = "就いていません";
		}
		$hhh = '                                                                                   ';
		$eol = '§r'."\n";
		$color = '§6';
		$space = $eol.'§l'.$color;
		if($this->getOwner()->money->get("経済システム") === "true" and $this->getOwner()->level->get("レベルシステム") === "true"){
			$player->sendTip(
				$space.$hhh.'§e  == status =='.
				$space.$hhh.'Level: Lv.'.$level.
				$space.$hhh.'経験値: '.$exp.'E'.
				$space.$hhh.'レベルアップまで: '.$up.'E'.
				$space.$hhh.'所持金: '.$money.''.$this->getOwner()->money->get("お金の単位").
				$space.$hhh.'職業: '.$job.
				$space.$hhh.'Kill数: '.$kill.'kill'.
				$space.$hhh.'Death数: '.$death.'death'.
				$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol
			);
		}elseif($this->getOwner()->money->get("経済システム") === "true" and $this->getOwner()->level->get("レベルシステム") !== "true"){
			$player->sendTip(
				$space.$hhh.'§e  == status =='.
				$space.$hhh.'所持金: '.$money.''.$this->getOwner()->money->get("お金の単位").
				$space.$hhh.'職業: '.$job.
				$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol
			);
		}elseif($this->getOwner()->money->get("経済システム") !== "true" and $this->getOwner()->level->get("レベルシステム") === "true"){
			$player->sendTip(
				$space.$hhh.'§e  == status =='.
				$space.$hhh.'Level: Lv.'.$level.
				$space.$hhh.'経験値: '.$exp.'E'.
				$space.$hhh.'レベルアップまで: '.$up.'E'.
				$space.$hhh.'Kill数: '.$kill.'kill'.
				$space.$hhh.'Death数: '.$death.'death'.
				$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol.$eol
			);
		}
	}
}
