<?php

namespace GamerMJay\BetterRename;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    public function onEnable(): void
    {
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        switch ($cmd->getName()){
            case "rename":
                $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                if(!$sender instanceof Player){
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
                if(isset($args[0])){
                    $name = $args[0];
                    $item = $sender->getInventory()->getItemInHand();
                    $item->setCustomName($name);
                    $sender->getInventory()->setItemInHand($item);;
                    $msg = str_replace("{name}", $name, $config->get("rename-succes"));
                    $sender->sendMessage($msg);
                }
                break;
        }
        return false;
    }


}
