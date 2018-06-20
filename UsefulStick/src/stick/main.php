<?php

namespace stick;

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

use RuinPray\ui\UI;
use RuinPray\ui\elements\Dropdown;
use RuinPray\ui\elements\Input;
use RuinPray\ui\elements\Label;
use RuinPray\ui\elements\Slider;
use RuinPray\ui\elements\StepSlider;
use RuinPray\ui\elements\Toggle;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		if($label === "stick"){
			$item = Item::get(280, 0, 1)->setCustomName("§o§l§aUseful§bS§et§di§dc§ck");
			$this->Enchant($item, 17, 1000);
			$inv = $sender->getInventory();
			if($inv->canAddItem($item)){
				$inv->addItem($item);
				$sender->sendMessage("§l§aProtectStickを配布しました");
			}else{
				$sender->sendMessage("§l§cインベントリに空きがありません");
			}
		}
		return true;
	}

	public function onDamage(EntityDamageEvent $event){
		if($event->getCause() === 1){
			$damager = $event->getDamager();
			$entity = $event->getEntity();
			if($damager->getInventory()->getItemInHand()->getCustomName() === "§o§l§aUseful§bS§et§di§dc§ck"){
				$event->setCancelled();
				if($damager->isOp()){
					$this->stick[$damager->getName()] = $entity->getName();
	 						$form = UI::createCustomForm(64836);
	 						$form->setTitle("§o§l§aUseful§bS§et§di§dc§ck");
	 						$form->addContent((new Label)->text("そのプレイヤーに行いたいことを選択してください"));
							$form->addContent((new Dropdown)->text(
								"オプション\n".
								"* kick : プレイヤーをキックする\n".
								"* ban : プレイヤーをBanする\n".
								"* ban-ip : プレイヤーをBan-Ipする\n".
								"* Whitelist add : プレイヤーをWhitelistに追加する\n".
								"* Whitelist remove :プレイヤーをWhitelistから消去する\n".
								"* tell : 個人メッセージを送る\n".
								"* info : プレイヤー情報を表示する\n"
							)->options(["kick", "ban", "ban-ip", "Whitelist add", "Whitelist remove", "tell", "info"]));
	 						$form->addContent((new Input)->text('表示メッセージ(kickの時のみ)')->placeholder('警告文的な'));
	 						$form->addContent((new Input)->text('送るメッセージ(tellの時のみ)')->placeholder('個人チャット'));
							UI::sendForm($damager, $form);
				}else{
					$damager->sendMessage("§l§c権限がありません");
				}
			}
		}
	}

	public function onReceive(DataPacketReceiveEvent $event){
		$pk = $event->getPacket();
		$p = $event->getPlayer();
		$name = $p->getName();
		if($pk instanceof ModalFormResponsePacket) {
			$id = $pk->formId;
			$data = $pk->formData;
			$result = json_decode($data);
			if($data == "null\n"){
			}else{
				if($id === 64836){
					if(isset($this->stick[$p->getName()])){
						$player2 = $this->getServer()->getPlayer($this->stick[$p->getName()]);
						$dropdown = $result[1];
						$message = $result[2];
						if($dropdown === 0){
							if($player2->isOnline()){
								if($message === ""){
									$message = "なし";
								}
								$player2->kick($message);
								$p->sendMessage("§l§aプレイヤーをキックしました(理由: ".$message.")");
							}else{
								$p->sendMessage("§l§cそのプレイヤーはオフラインです");
							}
						}elseif($dropdown === 1){
							$player2->setBanned(true);
							$p->sendMessage("§l§aプレイヤーBanしました");
						}elseif($dropdown === 2){
							Server::getInstance()->dispatchCommand($p, "ban-ip ".$player2->getName());							
						}elseif($dropdown === 3){
							$player2->setWhitelisted(true);
							$p->sendMessage("§l§aプレイヤーをホワリスに追加しました");
						}elseif($dropdown === 4){
							$player2->setWhitelisted(false);
							$p->sendMessage("§l§aプレイヤーをホワリスから消去しました");
						}elseif($dropdown === 5){
							Server::getInstance()->dispatchCommand($p, "tell ".$player2->getName()." ".$result[3]);
						}elseif($dropdown === 6){
	 						$form = UI::createCustomForm($player2->getId());
	 						$form->setTitle("§l§b".$player2->getName()." の情報一覧");
	 						$name = $player2->getName();
	 						$ip = $player2->getAddress();
	 						$item = $player2->getInventory()->getItemInHand();
	 						$id = $item->getId();
	 						$meta = $item->getDamage();
	 						$item_name = $item->getCustomName();
	 						$uuid = $player2->getUniqueId();
	 						$xuid = $player2->getXuid();
	 						$cid = $player2->getClientId();
	 						$local = $player2->getLocale();
	 						$gamemode = $player2->getGamemode();
	 						$world = $player2->getLevel()->getFolderName();
	 						$x = round($player2->x, 1); $y = round($player2->y, 1); $z = round($player2->z, 1);
	 						$max_health = $player2->getMaxHealth();
	 						$health = $player2->getHealth();
	 						$food = $player2->getFood();
	 						$form->addContent((new Label)->text("個人情報が含まれます。 \n相手の了承無く第三者に教えるような行為は控えて下さい\n"));
	 						$form->addContent((new Label)->text("プレイヤーネーム : ".$name));
	 						$form->addContent((new Label)->text("IPアドレス : ".$ip));
	 						$form->addContent((new Label)->text("UUID : ".$uuid));
	 						$form->addContent((new Label)->text("Xuid : ".$xuid));
	 						$form->addContent((new Label)->text("CID : ".$cid));
	 						$form->addContent((new Label)->text("言語 : ".$local));
	 						$form->addContent((new Label)->text("持っているアイテムのId:Meta : ".$id.":".$meta));
	 						$form->addContent((new Label)->text("持っているアイテムの名前 : ".$item_name));
	 						$form->addContent((new Label)->text("ゲームモード : ".$gamemode));
	 						$form->addContent((new Label)->text("World名 : ".$world));
	 						$form->addContent((new Label)->text("座標 : x: ".$x." y: ".$y." z: ".$z));
	 						$form->addContent((new Label)->text("体力 : ".$health."/".$max_health));
	 						$form->addContent((new Label)->text("空腹度 : ".$food."/20"));
							UI::sendForm($p, $form);
						}
						unset($this->stick[$p->getName()]);
					}else{
						$p->sendMessage("§l§cもう一度やり直してください");
					}
				}
			}
		}
	}

	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}

	public function Enchant($item, $id, $level){
		$encha = Enchantment::getEnchantment($id);
		$item->addEnchantment(new EnchantmentInstance($encha, $level));
	}
}