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
        $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        switch ($cmd->getName()){
            case "rename":
                $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                if(!$sender instanceof Player){
                    $sender->sendMessage($cfg->get("noplayer"));
                    return false;
                }
                if(!$sender->hasPermission("rename.use")){
                    $sender->sendMessage($cfg->get("no-permission"));
                    return false;
                }
                if(!isset($args[0])){
                    $sender->sendMessage($cfg->get("rename-usage"));
                    return false;
                }
                if(isset($args[0])){
                    $name = $args[0];
                    $item = $sender->getInventory()->getItemInHand();
                    $item->setCustomName($name);
                    $sender->getInventory()->setItemInHand($item);;
                    $msg = str_replace("{name}", $name, $cfg->get("rename-succes"));
                    $sender->sendMessage($msg);
                }
                break;
        }
        return false;
    }


}
