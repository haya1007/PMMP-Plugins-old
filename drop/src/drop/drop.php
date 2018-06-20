<?php

namespace drop;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\math\Vector3;

class drop extends PluginBase implements Listener {
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function Break(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$id = $block->getID();
		$level = $player->getLevel();
		$x = $block->getX();
		$y = $block->getY();
		$z = $block->getZ();
		if($id === 1){
			$rand1 = mt_rand(1,800);
			$rand2 = mt_rand(1,1200);
			$rand3 = mt_rand(1,2000);
			$rand4 = mt_rand(1,3000);
			$pos = new Vector3($x,$y,$z);
			if($rand1 == 1){
				$level->setBlock($pos, Block::get(16,0)); //ブロック設置
				$player->sendMessage('§l§b石を掘ってたら石炭が!');	
			}elseif($rand2 == 1){											
				$level->setBlock($pos, Block::get(15,0)); //ブロック設置
				$player->sendMessage('§l§b石を掘ってたら鉄が!');				
			}elseif($rand3 == 1){
				$level->setBlock($pos, Block::get(14,0)); //ブロック設置
				$player->sendMessage('§l§b石を掘ってたら金が!');
			}elseif($rand4 == 1){
				$level->setBlock($pos, Block::get(56,0)); //ブロック設置
				$player->sendMessage('§l§b石を掘ってたらダイヤが!');
			}
		}
	}
}