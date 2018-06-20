<?php

namespace SpawnMob;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\tile\Sign;
use pocketmine\scheduler\Task;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\BossEventPacket;
use pocketmine\network\protocol\SetEntityMotionPacket;
use pocketmine\network\protocol\MoveEntityPacket;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\ContainerSetContentPacket;
use pocketmine\network\protocol\ContainerSetSlotPacket;
use pocketmine\network\protocol\MobArmorEquipmentPacket;
use pocketmine\network\protocol\PlayerEquipmentPacket;
//use pocketmine\network\protocol\RemovePlayerPacket;
use pocketmine\network\protocol\RemoveEntityPacket;
use pocketmine\network\protocol\SetEntityLinkPacket;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\network\protocol\UpdateAttributesPacket;
use pocketmine\network\protocol\FullChunkDataPacket;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\network\protocol\SetHealthPacket;
use pocketmine\network\protocol\MobEffectPacket;
use pocketmine\network\protocol\RemoveBlockPacket;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\network\protocol\SetEntityDataPacket;
use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\network\protocol\AdventureSettingsPacket;
use pocketmine\network\protocol\AnimatePacket;
use pocketmine\network\protocol\BatchPacket;
use pocketmine\network\protocol\DisconnectPacket;
use pocketmine\network\protocol\RespawnPacket;
use pocketmine\network\protocol\SetDifficultyPacket;
use pocketmine\network\protocol\SetSpawnPositionPacket;
use pocketmine\network\protocol\SetTimePacket;
use pocketmine\network\protocol\StartGamePacket;
use pocketmine\network\protocol\TakeItemEntityPacket;
use pocketmine\network\protocol\TileEntityDataPacket;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info as ProtocolInfo;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\math\Vector2;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\level\Explosion;
use pocketmine\network\protocol\AddMobPacket;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\format\FullChunk;
use pocketmine\level\format\mcregion\Chunk;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\utils\TextFormat;
use pocketmine\utils\MainLogger;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\entity\Snowball;
use pocketmine\level\Position;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\Particle;
use pocketmine\level\sound\PopSound;  
use pocketmine\level\sound\BatSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\level\sound\DoorSound;
use pocketmine\level\sound\FizzSound;
use pocketmine\level\sound\GenericSound;
use pocketmine\level\sound\LaunchSound; 
use pocketmine\level\particle\FloatingTextParticle; 
use pocketmine\entity\Villager;
use pocketmine\event\TranslationContainer;
use pocketmine\network\Network;
use pocketmine\event\player\PlayerItemHeldEvent ;
use pocketmine\entity\Effect;
use pocketmine\entity\Item as ItemEntity;
use pocketmine\entity\FallingSand;
use pocketmine\inventory\Inventory;
use pocketmine\event\entity\EntityShootBowEvent; 
use pocketmine\event\entity\EntityMoveEvent; 
use pocketmine\plugin\Plugin;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\block\Block;
use pocketmine\tile\Tile;
use pocketmine\utils\UUID;
use pocketmine\utils\BinaryStream;
use pocketmine\event\level\ChunkLoadEvent;

use pocketmine\entity\Creeper;
use pocketmine\entity\Skeleton;
use pocketmine\entity\Human;
use pocketmine\entity\Squid;
use pocketmine\entity\Husk;
use pocketmine\entity\PrimedTNT;
use pocketmine\entity\Arrow;

class main extends PluginBase implements Listener{

	public function onEnable(){
		$server = $this->getServer();
		$server->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->notice("SpawnMob　製作者:hayao");
		$this->level['PVE'] = $server->getLevelByName('PVE');

//--------------------------Zombie & Husk-------------------------------------------

				$nbt1 = new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", 218),
						new DoubleTag("", 5),
						new DoubleTag("", 336)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", lcg_value() * 360),
						new FloatTag("", 0)
					]),
				]);

				$nbt2 = new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", 231),
						new DoubleTag("", 5),
						new DoubleTag("", 333)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", lcg_value() * 360),
						new FloatTag("", 0)
					]),
				]);

				$task1 = new spawn($this,$nbt1,'Zombie');
				$task2 = new spawn($this,$nbt2,'Skeleton');
        		$server->getScheduler()->scheduleRepeatingTask($task1, 20*8);
        		$server->getScheduler()->scheduleRepeatingTask($task2, 20*10);

//--------------------------Silverfish---------------------------------------

				$nbt3 = new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", -60),
						new DoubleTag("", 6),
						new DoubleTag("", 7)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", lcg_value() * 360),
						new FloatTag("", 0)
					]),
				]);
				#$task3 = new spawn($this,$nbt3,$level,'Silverfish');
        		#$server->getScheduler()->scheduleRepeatingTask($task3, 20*80);

//--------------------------Skeleton---------------------------------------

				$nbt4 = new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", 1),
						new DoubleTag("", 6),
						new DoubleTag("", -36)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", lcg_value() * 360),
						new FloatTag("", 0)
					]),
				]);
				#$task4 = new spawn($this,$nbt4,$level,'Skeleton');
        		#$server->getScheduler()->scheduleRepeatingTask($task4, 20*10);
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if($label === 'xyz'){
			$x = $sender->getX();
			$y = $sender->getY();
			$z = $sender->getZ();
			$X = intval($x);
			$Y = intval($y);
			$Z = intval($z); 
			$sender->sendMessage('§l§eあなたの座標は  '.$X.'§f : §e'.$Y.'§f : §e'.$Z);
		}elseif($label === 'zombie'){
			$x = $sender->getX();
			$y = $sender->getY();
			$z = $sender->getZ();
			$skin = $sender->getSkinData();
			$skinId = $sender->getSkinId();
				$nbt = new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", $x),
						new DoubleTag("", $y),
						new DoubleTag("", $z)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", lcg_value() * 360),
						new FloatTag("", 0)
					]),
				]);
			$level = $sender->getLevel();
			$entity = Entity::createEntity('Zombie', $level, $nbt);
 			$entity->SpawnToAll();
		}
	}

	/*public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$skin = $player->getSkinData();
		$skinId = $player->getSkinId();
				$nbt1 = new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", -8),
						new DoubleTag("", 6),
						new DoubleTag("", -17)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", lcg_value() * 360),
						new FloatTag("", 0)
					]),
				]);
			$level = $this->getServer()->getDefaultLevel();
			$entity = Entity::createEntity('Human', $level, $nbt1);
			$entity->setSkin($skin,$skinId);
 			$entity->setDataProperty(39,3,10);
 			$entity->SpawnToAll();


	}*/

}

class spawn extends PluginTask{

	public function __construct($owner,$nbt,$branch){
	  parent::__construct($owner);
	  $this->nbt = $nbt;
	  $this->branch = $branch;
    }

 	public function onRun($mob){
 		$owner = $this->owner;
 		if($this->branch === 'Zombie'){
 			$entity = Entity::createEntity('Zombie', $owner->level['PVE'], $this->nbt);
			$entity->SpawnToAll();
 		}elseif($this->branch === 'Silverfish'){
 			$entity = Entity::createEntity('Silverfish', $owner->level['PVE'], $this->nbt);
 			$entity->setDataProperty(39,3,10);
 			$entity->SpawnToAll();
 		}elseif($this->branch === 'Husk'){
 			$entity = Entity::createEntity('Husk', $owner->level['PVE'], $this->nbt);
 			$entity->setDataProperty(39,3,2.5);
 			$entity->setMaxHealth(150);
 			$entity->SpawnToAll();
 		}elseif($this->branch === 'Husk'){
 			$entity = Entity::createEntity('Husk', $owner->level['PVE'], $this->nbt);
 			$entity->setDataProperty(39,3,2.5);
 			$entity->setMaxHealth(150);
 			$entity->SpawnToAll();
 		}elseif($this->branch === 'Skeleton'){
 			$entity = Entity::createEntity('Skeleton', $owner->level['PVE'], $this->nbt);
 			$entity->SpawnToAll();
 		}
 	}

}