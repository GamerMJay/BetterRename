<?php

namespace GamerMJay\BetterRename;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{

    private $confversion = "1.0.0";

    public $version = "2.0.0";

    private Config $config;

    public function onEnable(): void
    {
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        if(!$this->config->exists("version") || $this->config->get("version") !== $this->confversion){
            $this->getLogger()->error("Your config has an old version, updating your config to a new one. You might set values");
        }
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        if ($cmd->getName() === "rename") {
            $config = $this->config;
            if (!$sender instanceof Player) {
                $sender->sendMessage($config->get("run-ingame"));
                return false;
            }
            if(!$sender->hasPermission("rename.use")){
                $sender->sendMessage($config->get("no-permission"));
                return false;
            }
            $item = $sender->getInventory()->getItemInHand();
            if ($item->isNull()) {
                $sender->sendMessage($config->get("rename.noitem"));
                return false;
            }
            if(!isset($args[0])){
                $sender->sendMessage($config->get("rename-usage"));
                return false;
            }
            $name = $args[0];
            $item = $sender->getInventory()->getItemInHand();
            $item->setCustomName($name);
            $sender->getInventory()->setItemInHand($item);
            $sender->sendMessage(str_replace("{name}", $name, $config->get("rename-succes")));
        }

        return true;
    }
}
