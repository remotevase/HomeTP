<?php
namespace HomeTP;
use pocketmine\command\{Command, CommandSender};
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\level\{Position, Level};
use pocketmine\utils\TextFormat as C;
class HomeTP extends PluginBase{
    public $homeData;
    public function onEnable(){
        
        // Custom Config 
        
        $this->saveResource("homes.yml");
        @mkdir($this->getDataFolder());
        $this->homeData = new Config($this->getDataFolder()."homes.yml", Config::YAML, array());
        $this->getLogger()->info(C::GREEN."HomeTeleporter has successfully loaded!");
        $this->getLogger()->info(C::YELLOW."All homes have saved!");
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch(strtolower($command->getName())){
            case "home":
            if($sender instanceof Player){
                $home = $this->homeData->get($args[0]);
                if($home["world"] instanceof Level){
                    $s->setLevel($home["world"]);
                    $s->teleport(new Position($home["x"], $home["y"], $home["z"]));
                    $sender->sendMessage(C::BLUE."You teleported home.");
                }else{
                    $sender->sendMessage(C::RED."That world is not loaded or Doesn't Exist!");
                }
            }
            break;
            case "sethome":
            if ($sender instanceof Player){
                $x = $s->x;
                $y = $s->y;
                $z = $s->z;
                $level = $s->getLevel();
                // $args[0] is the Name of the house -> /sethome <name>
                $this->homeData->set($args[0], array(
                    "x" => $x,
                    "y" => $y,
                    "z" => $z,
                    "world" => $level,
                ));
                $sender->sendMessage(C::GREEN."Your home is set at coordinates\n" . "X:" . Color::YELLOW . $x . Color::GREEN . "\nY:" . Color::YELLOW . $y . Color::GREEN . "\nZ:" . Color::YELLOW . $z . Color::GREEN . "\nUse /home < ". $args[0] ." > to teleport to this home!");
                $this->getLogger()->info($sender->getName() . " has set their home in world " . $sender->getLevel()->getName());
            }else{
                    $sender->sendMessage(C::RED. "Please run command in game.");
                    return true;
                }
                break;
            default:
                return false;
        }
    }
    public function onDisable(){
        $this->getLogger()->info(C::DARK_RED."HomeTeleporter has successfully Disabled!");
        $this->getLogger()->info(C::YELLOW."All homes have saved!");
        $this->saveResource("homes.yml");
    }
}
