<?php
namespace ServerLoveMCPE;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->nolove = new Config($this->getDataFolder()."nolove.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[♥] Yayyy, ServerLoveMCPE is ready for love on Version ".$this->getDescription()->getVersion());
    }
    
    public function onDisable(){
        $this->nolove->save();
        $this->getLogger()->info(TextFormat::LIGHT_PURPLE . "[♥] You've broken up with the server.");
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
/**
*
*                                         LOVE
*
**/
            case "love":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                
                $loved = array_shift($args);
                if($this->nolove->exists(strtolower($loved))){
                    $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥]Sorry, " . $loved . " is not looking to love anyone right now.");
                    return true;
                }else{
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        if($lovedPlayer == $sender){
                            //This is where the loop for the #ForeverAlone goes to - by ratchetgame98
                            //You can personlise the messages to your liking also
                            $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥]You can't love yourself :P");
                            $this->getServer()->broadcastMessage($sender->getName() . TextFormat::DARK_PURPLE . "[♥] §etried to love themselves :P." . TextFormat::GOLD . "#ForeverAlone");
                        }else{
                            $lovedPlayer->sendMessage($sender->getName() . TextFormat::DARK_PURPLE . " is in love with you!");
                            /** $sender->setNameTag($sender->getName() . "- ♥");
                            * WON'T WORK ATM
                            $loved->setNameTag($loved->getName() . "- ♥"); **/
                            if(isset($args[0])){
                                $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                            }
                            $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] So you love " . TextFormat::GREEN . $loved . "?" . TextFormat::DARK_PURPLE . " Awww, thats nice");
                            $this->getServer()->broadcastMessage(TextFormat::GREEN . $sender->getName() . TextFormat::LIGHT_PURPLE . " is in love with " . TextFormat::GREEN . $loved . TextFormat::LIGHT_PURPLE . "§d.");
                            $this->getServer()->broadcastMessage(TextFormat::LIGHT_PURPLE . "♥" . $loved . "♥" . $sender->getName() . "♥");
                            return true;
                        }
                    }else{
                        $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] " . TextFormat::GREEN . $loved . TextFormat::DARK_PURPLE . " is not avalible for love. #shameful. Basically, " . TextFormat::GREEN . $loved . TextFormat::DARK_PURPLE . " does not exist, or is not online.");
                        return true;
                    }
                }
/**
*
*                                 BREAKUP
*
**/
                break;
            case "breakup":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                $loved = array_shift($args);
                    $lovedPlayer = $this->getServer()->getPlayer($loved);
                    if($lovedPlayer !== null and $lovedPlayer->isOnline()){
                        $lovedPlayer->sendMessage(TextFormat::DARK_PURPLE . "[♥]" . TextFormat::GREEN . $sender->getName() . TextFormat::DARK_PURPLE . "has broken up with you!");
                        if(isset($args[0])){
                            $lovedPlayer->sendMessage("Reason: " . implode(" ", $args));
                        }
                        $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] You have broken up with " . TextFormat::GREEN . $loved . TextFormat::DARK_PURPLE .  ".");
                        $this->getServer()->broadcastMessage(TextFormat::GREEN . $sender->getName() . TextFormat::LIGHT_PURPLE . " has broken up with " . TextFormat::GREEN . $loved . TextFormat::LIGHT_PURPLE . ".");
                       /** $sender->setNameTag($sender->getName() . "");
                        * WON'T WORK ATM
                        $loved->setNameTag($loved->getName() . ""); **/
                        return true;
                    }else{
                        $sender->sendMessage($loved . TextFormat::DARK_PURPLE . "[♥] is not avalible for a breakup. Basically, " . TextFormat::GREEN . $loved . TextFormat::DARK_PURPLE . " does not exist, or is not online.");
                        return true;
                    }
/**
*
*                                      NOLOVE
*
**/
            break;
            case "nolove":
                if(!(isset($args[0]))){
                    return false;
                }
                if (!($sender instanceof Player)){ 
                $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] YOU MUST USE THIS COMMAND IN GAME. SORRY.");
                    return true;
                }
                if($args[0] == "nolove"){
                    $this->nolove->set(strtolower($sender->getName()));
                    $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] You will no longer be loved." . TextFormat::YELLOW . "#ForEverAlone");
                    return true;
                }elseif($args[0] == "love"){
                    $this->nolove->remove(strtolower($sender->getName()));
                    $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥] You will now be loved again!"  . TextFormat::YELLOW . "#GetInThere");
                    return true;
                }else{
                    return false;
                }
/**
*
*                                   SERVERLOVE
*
**/
            break;
            case "serverlove":
                $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥][ServerLoveMCPE] Original ServerLove (For MCPC )  Made By ratchetgame98 ");
                $sender->sendMessage(TextFormat::LIGHT_PURPLE . "[♥][ServerLoveMCPE] Usage: /love <playerName>");
                $sender->sendMessage(TextFormat::LIGHT_PURPLE . "[♥][ServerLoveMCPE] Usage: /breakup <playerName>");
                $sender->sendMessage(TextFormat::LIGHT_PURPLE . "[♥][ServerLoveMCPE] Usage: /nolove <nolove / love> ");
                $sender->sendMessage(TextFormat::DARK_PURPLE . "[♥][ServerLoveMCPE] Happy Loving!");
                return true;
            break;
        default:
            return false;
        }
    return false;
    }
}
