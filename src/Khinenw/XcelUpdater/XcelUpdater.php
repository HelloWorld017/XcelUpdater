<?php

namespace Khinenw\XcelUpdater;

class XcelUpdater extends UpdatePlugin{
	/**
	 * @var XcelUpdater
	 */
	public static $instance;
	public static $update;

	const RETURN_UPDATE_REQUIRED = -1;
	const RETURN_UPDATE_BYPASSED = 0;
	const RETURN_UPDATE_UP_TO_DATE = 1;

	public function onEnable(){
		self::$instance = $this;

		@mkdir($this->getDataFolder());
		if(!is_file($this->getDataFolder() . "update.dat")){
			file_put_contents($this->getDataFolder() . "update.dat", "true");
		}

		self::$update = (file_get_contents($this->getDataFolder()."update.dat") !== "false");
		self::chkUpdate($this);
	}

	public static function chkUpdate(UpdatePlugin $plugin){
		return self::$instance->checkUpdate($plugin);
	}

	public function checkUpdate(UpdatePlugin $plugin){
		if(!self::$update){
			$this->getLogger()->alert("=================XcelUpdater=================");
			$this->getLogger()->alert("Update bypassed! But plz don't do that.");
			$this->getLogger()->alert("=============================================");
			return 0;
		}

		$this->getLogger()->notice("Checking update for " . $plugin->getName());
		$this->getLogger()->notice("This may take a long time!");
		if($plugin->compVersion($plugin->getDescription()->getVersion(), self::getRepoVersion($plugin->getPluginYamlURL()))){
			$this->getLogger()->warning("=================XcelUpdater=================");
			$this->getLogger()->warning("Update required on plugin " . $plugin->getName());
			$this->getLogger()->warning("=============================================");
			return -1;
		}

		return 1;
	}

	public static function getRepoVersion($pluginYamlURL){
		$curl = curl_init($pluginYamlURL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		$data = curl_exec($curl);
		curl_close($curl);
		return yaml_parse($data)["version"];
	}

	public function compVersion($pluginVersion, $repoVersion){
		return $pluginVersion !== $repoVersion;
	}

	public function getPluginYamlURL(){
		return "https://raw.githubusercontent.com/HelloWorld017/XcelUpdater/master/plugin.yml";
	}

	public function getPluginInstance(){
		return $this;
	}
}
