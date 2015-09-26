<?php

namespace Khinenw\XcelUpdater;

use pocketmine\plugin\PluginBase;

abstract class UpdatePlugin extends PluginBase{
	public abstract function compVersion($pluginVersion, $repoVersion);
	public abstract function getPluginYamlURL();
}
