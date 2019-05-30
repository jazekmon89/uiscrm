<?php

namespace App\Helpers;

use App\Providers\Facades\Entity;

class ThemeHelper 
{
	static $default = [
		'Theme' => 'cmi',
		'AppName' => 'CMI Data'
	];

	static $settings = [];

	static $host = null;

	public static function host()
	{
		return static::$host = static::$host ?: array_get(app('request')->server(), "HTTP_HOST", "localhost");
	}
	
	public static function getDomain()
	{
		return static::host();
	}

	public static function getSettings($domain)
	{
		if (isset(static::$settings[$domain]))
		{
			return static::$settings[$domain];
		}

		if ($template = Entity::model()->DomainTemplate_GetByDomain_first($domain))
		{
			$settings = [
				'Theme' => $template->Template ?: 'cmi',
				'AppName' => $template->OrganisationName ?: 'CMI Data',
				'Organisation' => $template->OrganisationID
			];

			return static::$settings[$domain] = $settings;
		}

		return static::$default;
	}

	public static function getTheme()
	{
		return array_get(static::getSettings(static::getDomain()), 'Theme', '');
	}

	public static function appName()
	{
		return array_get(static::getSettings(static::getDomain()), 'AppName', '');
	}
}