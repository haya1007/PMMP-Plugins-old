<?php

namespace TimeKill;

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
use pocketmine\event\entity\EntityMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerToggleSprintEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\SetEntityMotionPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\ContainerSetSlotPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\PlayerEquipmentPacket;
//use pocketmine\network\mcpe\protocol\RemovePlayerPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\mcpe\protocol\UseItemPacket;
use pocketmine\network\mcpe\protocol\FullChunkDataPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\SetHealthPacket;
use pocketmine\network\mcpe\protocol\MobEffectPacket;
use pocketmine\network\mcpe\protocol\RemoveBlockPacket;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\SetEntityDataPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\RespawnPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;
use pocketmine\network\mcpe\protocol\TileEntityDataPacket;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\Info as ProtocolInfo;
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
use pocketmine\plugin\Plugin;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\block\Block;
use pocketmine\tile\Tile;
use pocketmine\utils\UUID;
use pocketmine\utils\BinaryStream;
use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\entity\Zombie;
use pocketmine\entity\Creeper;
use pocketmine\entity\Skeleton;
use pocketmine\entity\Human;
use pocketmine\entity\Squid;
use pocketmine\entity\Enderman;
use pocketmine\entity\Stray;
use pocketmine\entity\Husk;
use pocketmine\entity\CaveSpider;
use pocketmine\entity\IronGolem;
use pocketmine\entity\SnowGolem;
use pocketmine\entity\WitherSkeleton;
use pocketmine\entity\PrimedTNT;
use pocketmine\entity\Arrow;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\player\PlayerTransferEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\network\protocol\ResourcePacksInfoPacket;
use pocketmine\math\AxisAlignedBB;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\entity\Attribute;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\AnvilUseSound;


class main extends PluginBase implements Listener{
	public function onEnable(){
		$server = $this->getServer();
		$server->getPluginManager()->registerEvents($this, $this);
		foreach($server->getLevels() as $level) {
		$task = new kill($this,$level,$server);
        $server->getScheduler()->scheduleRepeatingTask($task, 20*180);
    	}
	}
}

class kill extends PluginTask{

	public function __construct($owner,$level,$server){
	  parent::__construct($owner);
	  $this->level = $level;
	  $this->server = $server;
    }

 	public function onRun($kill){
 		$c = 0;
 		foreach($this->level->getEntities() as $e) {
 			if(!$e instanceof Player && !$e instanceof Creature){
 				$e->close();
 				$e->kill();
 				$c++;
 			}
 		}
 		$this->server->broadcastMessage("§amobを葬り去りました");
 	}

}