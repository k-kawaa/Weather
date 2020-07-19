<?php

namespace apart\weather;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{

	private $config2;

	public function onEnable()
	{

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$this->config2 = new Config($this->getDataFolder() . "weather.yml", Config::YAML, array(
			"weather" => "clear",));


	}

	public function onjoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		$level = $player->getLevel()->getFolderName();
		$data = $this->config2->get("weather");
		if ($data === "clear") {

		} elseif ($data === "rain") {
			$pk = new LevelEventPacket();
			$pk->position = $player;
			$pk->data = 110000;
			$pk->evid = LevelEventPacket::EVENT_START_RAIN;
			$player->dataPacket($pk);
		} elseif ($data === "thunder") {
			$pk = new LevelEventPacket();
			$pk->position = $player;
			$pk->data = 40000;
			$pk->evid = LevelEventPacket::EVENT_START_RAIN;
			$player->dataPacket($pk);
		}
	}


	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
	{

		$data = $this->config2->get("weather");
		if (!$sender instanceof Player) {
			$sender->sendMessage("§cゲーム内で実行してください");
			return true;
		}

		$name = $sender->getName();

		switch ($label) {

			case 'weather':
				if (!isset($args[0])) {
					$sender->sendMessage("§a[weatherSystem]use：/weather clear|rain|thunder");
				} elseif ($args[0] === "rain") {
					$this->config2->set("weather", "rain");
					$pk = new LevelEventPacket();
					$pk->position = $sender;
					$pk->data = 110000;
					$pk->evid = LevelEventPacket::EVENT_START_RAIN;
					$sender->dataPacket($pk);
				} elseif ($args[0] === "thunder") {
					$this->config2->set("weather", "thunder");
					$pk = new LevelEventPacket();
					$pk->position = $sender;
					$pk->data = 40000;
					$pk->evid = LevelEventPacket::EVENT_START_RAIN;
				} elseif ($args[0] === "clear") {
					if ($data === "clear") {
						# code...
					}
					if ($data === "rain") {
						$pk = new LevelEventPacket();
						$pk->position = $sender;
						$pk->data = 110000;
						$pk->evid = LevelEventPacket::EVENT_STOP_RAIN;
						$sender->dataPacket($pk);
						$this->config2->set("weather", "clear");
						return true;
					}
					if ($data === "thunder") {
						$pk = new LevelEventPacket();
						$pk->position = $sender;
						$pk->data = 40000;
						$pk->evid = LevelEventPacket::EVENT_STOP_RAIN;
						$sender->dataPacket($pk);
						$this->config2->set("weather", "clear");
						return true;
					}
				}
				break;

		}
		return true;

	}


}
