<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\Leaderboard\Task;

use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\world\particle\FloatingTextParticle;
use TheNote\core\utils\Manager as STM;
use TheNote\Leaderboard\Main;
use TheNote\core\Main as PCMain;

class LeaderboardTask extends Task
{

    private $plugin;
    private $floattext;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(): void
    {
        $all = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($all as $player) {
            if (!$player->isOnline()) return;

            $cfg = new Config($this->plugin->getDataFolder() . "Leaderboard.yml", Config::YAML);
            $level = $this->plugin->getServer()->getWorldManager()->getWorldByName($cfg->get("World"));

            $text = $this->getText($player);
            $x = $cfg->get("X");
            $y = $cfg->get("Y");
            $z = $cfg->get("Z");

            if ($this->plugin->anni === 1) {
                $this->plugin->anni = 2;
            } elseif ($this->plugin->anni === 2) {
                $this->plugin->anni = 1;
            }
            if (!isset($this->floattext[$player->getName()])) {
                $this->floattext[$player->getName()] = new FloatingTextParticle($text);
                $particle = $this->floattext[$player->getName()];
                $particle->setInvisible(true);
                $level->addParticle(new Vector3($x, $y, $z), $particle, [$player]);
            } else {
                $particle = $this->floattext[$player->getName()];
                $particle->setInvisible(true);
                $level->addParticle(new Vector3($x, $y, $z), $particle, $all);
                $this->floattext[$player->getName()] = new FloatingTextParticle($text);
                $newparticle = $this->floattext[$player->getName()];
                $newparticle->setInvisible(false);
                $level->addParticle(new Vector3($x, $y, $z), $newparticle, [$player]);
            }
        }
    }

    public function getText(Player $player)
    {
        $cfg = new Config($this->plugin->getDataFolder() . "Leaderboard.yml", Config::YAML);
        if ($this->plugin->anni === 1) {
            $text = STM::formateString(PCMain::getInstance(), $player, $cfg->get("Leaderboard"));
        } else {
            $text = STM::formateString(PCMain::getInstance(), $player, $cfg->get("Leaderboard"));
        }
        return $text;
    }
}