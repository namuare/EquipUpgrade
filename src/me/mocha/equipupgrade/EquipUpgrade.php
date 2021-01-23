<?php

namespace me\mocha\equipupgrade;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\Item;
use pocketmine\item\Sword;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class EquipUpgrade extends PluginBase {

    private $message, $config;
    private $listener;

    public function onLoad() {
        $folder = $this->getDataFolder();
        @mkdir($folder);
        $this->saveResource("message.yml");
        $this->saveResource("config.yml");
        $this->message = (new Config($folder . "message.yml"))->getAll();
        $this->config = (new Config($folder . "config.yml"))->getAll();
    }

    public function onEnable() {
        $this->listener = new EventListener($this);
        $this->getServer()->getPluginManager()->registerEvents($this->listener, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->getMessage("only-player"));
            return true;
        }
        return false;
    }

    public function getMessage($key, $replacement = []): string {
        $msg = $this->message[$key];
        foreach ($replacement as $k => $v) {
            $msg = str_ireplace($k, $v, $msg);
        }
        return $msg;
    }

    public function isWeapon(Item $item): bool {
        return $item instanceof Sword || $item instanceof Axe;
    }

    public function isArmor(Item $item): bool {
        return $item instanceof Armor;
    }

}