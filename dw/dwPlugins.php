<?php

namespace dw;

use dw\classes\dwPlugin;

define('E_PLUG_LOAD', 100);
define('E_PLUG_UNKNOW', 101);

/**
 * Classe pour gérer les plugins
 * @author Quentin Supernant
 * @version 1.0
 * @package dotWoueb
 */
class dwPlugins {
	
	public static $pathPlugins = './';
	public static $tablePrefix = 'plug_';
	public static $pluginSuffix = '';
	private static $_aPluginLoaded = array();
	 
	public static function getPlugins()
	{
		return self::$_aPluginLoaded;	
	}
	
	public static function getPlugin($splugin)
	{
		if(!self::isLoaded($splugin))
		{
			throw new exception(E_PLUG_UNKNOW);
		}
		return self::$_aPluginLoaded[$splugin];
	}
	
	public static function isLoaded($splugin)
	{
		return isset(self::$_aPluginLoaded[$splugin]);
	}
	
	public static function &loadPlugin($splugin, $pathPlugins, $useCache = true)
	{
		//if(!isset(self::$_aPluginLoaded[$splugin]) || !$useCache)
		{
			include_once($pathPlugins.'/'.$splugin.'/class.php');
			$class = $splugin.self::$pluginSuffix;
			if(class_exists($class))
			{
				$plugin = new $class($splugin);
				$plugin -> prepare($pathPlugins.'/'.$splugin);
				self::$_aPluginLoaded[$splugin] = $plugin;
			} else {
				throw new exception(E_PLUG_LOAD);	
			}
		}
		return self::$_aPluginLoaded[$splugin];	
	}
	 
	public static function load($mplugin, $pathPlugins = null, $useCache = true)
	{
		if(is_null($pathPlugins))
		{
			$pathPlugins = self::$pathPlugins;
		}
		if(is_array($mplugin))
		{
			foreach($mplugin as $splugin)
			{
				self::loadPlugin($splugin, $pathPlugins, $useCache);	
			}
		} else {
			return self::loadPlugin($mplugin, $pathPlugins, $useCache);		
		}
	}
	
	public static function forAllPluginsDo($sfunction, $aparams = null)
	{
		foreach(self::$_aPluginLoaded as $plugin)
		{
			$plugin -> $sfunction($aparams);
		}
	}
	
	public static function setPath($spath)
	{
		self::$pathPlugins = $spath;
	}
		
}
 
?>
