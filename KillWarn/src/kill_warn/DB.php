<?php

namespace kill_warn;

use pocketmine\Player;

use kill_warn\EventListener;

class DB{

	public function __construct(main $main){
		$this->main = $main;
		$this->db = new \SQLite3($this->main->getDataFolder() ."data.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS userdata (
				name TEXT NOT NULL PRIMARY KEY,
				warn INTEGER NOT NULL,
				xuid TEXT NOT NULL
		)");
	}

	public function register(Player $player){
		$value = "INSERT INTO userdata (name, warn, xuid) VALUES (:name, :warn, :xuid)";
		$db = $this->db->prepare($value);
		$name = $player->getName();
		$xuid = $player->getXuid();
		$db->bindValue(":name", $name);
		$db->bindValue(":warn", 0);
		$db->bindValue(":xuid", $xuid);
		$db->execute();
		#main::getLogger()->notice($name ." Register Account！");
		#$player->setImmobile(false);
	}

	public function isRegister(Player $player){
		$name = $player->getName();
		$value = "SELECT xuid FROM userdata WHERE name = :name";
		$db = $this->db->prepare($value);
		$db->bindValue(":name", $name, SQLITE3_TEXT);
		$result = $db->execute()->fetchArray(SQLITE3_ASSOC);
		return empty($result) ? false : true;
	}

	public function unRegister(string $name){
		$data = $this->getUserData(null, $name);
		if (is_null($data)) return null;
		
		$value = "DELETE FROM userdata WHERE name = :name";
		$db = $this->db->prepare($value);
		$db->bindValue(":name", $name, SQLITE3_TEXT);
		$db->execute();
		#main::getLogger()->info("§a". $name ." Deleted Account！");
		return true;
	}

	/*public function login(Player $player){
		$name = $player->getName();
		$xuid = $player->getXuid();
		$result = $this->isRegister($player);
		if ($result) {
			$data = $this->getUserData($player, $name);
			if (is_null($data)) return;
			if ($data["name"] === $name && $data["xuid"] === $xuid) {
				return $data["warn"];
			} else {
				return null;
			}
		}
	}*/

	public function updateWarn(Player $player, int $warn){
		$name = $player->getName();
		$newIp = $player->getAddress();
		$value = "SELECT warn FROM userdata WHERE name = :name";
		$db = $this->db->prepare($value);
		$db->bindValue(":name", $name, SQLITE3_TEXT);
		$old_warn = $db->execute()->fetchArray(SQLITE3_ASSOC);

		if (empty($old_warn)) return null;

		$value = "UPDATE userdata SET warn = :warn WHERE name = :name";
		$db = $this->db->prepare($value);
		$db->bindValue(":name", $name, SQLITE3_TEXT);
		$db->bindValue(":warn", $warn, SQLITE3_INTEGER);
		$db->execute();
		$this->main->NewNameTag($player, $warn);
	}


	public function getUserData($player = null, $namae)
	{
		if (!is_null($player)) {
			$name = $player->getName();
		} else {
			$name = $namae;
		}
		$data = [];
		$value = "SELECT * FROM userdata WHERE name = :name";
		$db = $this->db->prepare($value);
		$db->bindValue(":name", $name, SQLITE3_TEXT);
		$result = $db->execute()->fetchArray(SQLITE3_ASSOC);
		if (empty($result)) return null;
		foreach ($result as $key => $value) {
			$data[$key] = $value;
		}
		return $data;
	}

	public function getWarn(Player $player){
		$name = $player->getName();
		$value = "SELECT warn FROM userdata WHERE name = :name";
		$db = $this->db->prepare($value);
		$db->bindValue(":name", $name, SQLITE3_TEXT);
		$result = $db->execute()->fetchArray(SQLITE3_ASSOC);
		if (empty($result)) return null;
		return $result["warn"];
	}
}