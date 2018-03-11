<?php

namespace hayao;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\scheduler\PluginTask;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Attribute;
use pocketmine\entity\Effect;
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
use pocketmine\entity\Mooshroom;
use pocketmine\entity\FallingSand;
use pocketmine\entity\Item as DroppedItem;
use pocketmine\entity\Skin;
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
use pocketmine\item\enchantment\Enchantment;
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

	public function onEnable(){
		$this->server = $this->getServer();
		$this->server->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->notice("LevelMoneySystemシステム 起動");
		if(!file_exists($this->getDataFolder())){mkdir($this->getDataFolder(), 0744, true);}
		$this->setting = new Config($this->getDataFolder() . "setting.yml", Config::JSON, array(
			'Pay' => 'true'
		));
	}

	public function Join(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$folder = $this->getFolder($name);
		$this->config[$name] = new Config($folder, Config::JSON, [
			'money'     => 500
		]);
	}

	public function onDisable(){
		foreach($this->config as $value) $value->save();
	}

	public function Quit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$this->config[$name]->save();
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$name = $sender->getName();
		if($label === "money"){
			if($sender instanceof Player){
				if(isset($this->config[$name])){
					$money = $this->config[$name]->get('money');
					$sender->sendMessage("§b§l所持金: ".$money."M");
				}
			}else{
				$sender->sendMessage("§cこのコマンドはゲーム内で行ってください");
			}
		}elseif($label === "addmoney"){
			if(!isset($args[0]) or !isset($args[1])){
				$sender->sendMessage("§c/addmoney Player名 数値");
			}else{
				$player = $this->getServer()->getPlayer($args[0]);
				if(isset($player)){
					$player_name = $player->getName();
					if(isset($player_name)){
						$plus = $args[1];
						if(!is_numeric($plus)){
							$sender->sendMessage("§c数値には数字しか入れれません");
						}else{
							$this->addMoney($player_name, $plus);
							$sender->sendMessage("§a".$player_name."に".$plus."M渡しました");
							$player->sendMessage("§a".$name."から".$plus."M渡されました");
						}
					}else{
						$sender->sendMessage("§cそのプレイヤーのお金データは存在しません");
					}
				}else{
					$sender->sendMessage("§cそのプレイヤーは存在しません");
				}
			}
		}elseif($label === "removemoney"){
			if(!isset($args[0]) or !isset($args[1])){
				$sender->sendMessage("§c/removemoney Player名 数値");
			}else{
				$player = $this->getServer()->getPlayer($args[0]);
				if(isset($player)){
					$player_name = $player->getName();
					if(isset($player_name)){
						$plus = $args[1];
						if(!is_numeric($plus)){
							$sender->sendMessage("§c数値には数字しか入れれません");
						}else{
							$this->removeMoney($player_name, $plus);
							$sender->sendMessage("§a".$player_name."に".$plus."M取りました");
							$player->sendMessage("§a".$name."から".$plus."M没収されました");
						}
					}else{
						$sender->sendMessage("§cそのプレイヤーのお金データは存在しません");
					}
				}else{
					$sender->sendMessage("§cそのプレイヤーは存在しません");
				}
			}
		}elseif($label === "seemoney"){
			if(!isset($args[0])){
				$sender->sendMessage("§c/seemoney Player名");
			}else{
				$player = $this->getServer()->getPlayer($args[0]);
				if(isset($player)){
					$player_name = $player->getName();
					$money = $this->getMoney($player_name);
					$sender->sendMessage("§b§l".$player_name."の所持金: ".$money."M");
				}else{
					$sender->sendMessage("§cそのプレイヤーは存在しません");
				}
			}
		}elseif($label === 'pay'){
			if($this->setting->get('Pay') === "true"){
				if($sender instanceof Player){
					if(count($args) === 0){
						$sender->sendMessage('§c/pay Player名 金額');
					}elseif(count($args) === 1){
						$sender->sendMessage('§c/pay Player名 金額');
					}else{
						if(!isset($args[2])){
							$username = strtolower($args[0]);
							$money = $args[1];
							$money = intval($money);
							$sendname = $sender->getName();
							$have = $this->getMoney($sendname);
			      			$player = $this->getServer()->getPlayer($username);
			      			if(isset($player)){
			      				if($player instanceOf Player){
			      					$name = $player->getName();
									if(!is_numeric($money)){
										$sender->sendMessage("§c数値には数字しか入れれません");
									}else{
					      				if(!$money == 0){
					      					if($have > $money){
					      						$player->sendMessage('§a'.$sendname.'さんから'.$money.'M渡されました');
					      						$this->addMoney($name, $money);
					      						$sender->sendMessage('§a'.$name.'さんに'.$money.'M渡しました');
					      						$this->sendremoveMoney($sendname, $money);
					      					}else{
					      						$sender->sendMessage('§c所持金が足りません');
					      					}
					      				}else{
					      					$sender->sendMessage('§c渡すお金を設定してください');
					      				}
				      				}
			      				}
			      			}else{
			      				$sender->sendMessage('§c指定されたプレイヤーは存在しません');
			      			}
			      		}
			      	}
		      	}else{
		      		$sender->sendMessage("§cこのコマンドはゲーム内で行ってください");
		      	}
	      	}else{
	      		$sender->sendMessage("§cサーバー側で使えないように設定されています");
	      	}
		}
		return true;
	}

	public function getFolder($name){
		$sub = substr($name, 0, 1);
		$upper = strtoupper($sub);
		$folder = $this->getDataFolder().$upper.'/';
		if(!file_exists($folder)) mkdir($folder);
		$lower = strtolower($name);
		return $folder .= $lower.'.json';
	}

	public function addMoney($name, $money){
		$money = $this->getMoney($name) + $money;
		$this->setMoney($name, $money);
	}

	public function getMoney($name){
		return $this->config[$name]->get('money');
	}

	public function removeMoney($name, $money){
		$money = $this->getMoney($name) - $money;
		$this->setMoney($name, $money);
	}

	public function sendremoveMoney($sendname, $money){
		$money = $this->getMoney($sendname) - $money;
		$this->setMoney($sendname, $money);
	}

	public function setMoney($name, $money){
		$this->config[$name]->set('money', $money);
	}

}