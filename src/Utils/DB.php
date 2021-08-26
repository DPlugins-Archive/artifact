<?php

namespace AncientWorks\Artifact\Utils;

use Exception;
use Medoo\Medoo;
use wpdb;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class DB
{
	private static $instances = [];

	private static $medoo;

	private static $wpdb;

	protected function __construct()
	{
		self::$wpdb = self::wpdb();

		$db_host = self::$wpdb->parse_db_host(self::$wpdb->dbhost);

		self::$medoo = new Medoo([
			'type'     => 'mysql',
			'host'     => $db_host[0],
			'port'     => $db_host[1],
			'socket'   => $db_host[2],
			'database' => self::$wpdb->dbname,
			'username' => self::$wpdb->dbuser,
			'password' => self::$wpdb->dbpassword,
			'prefix'   => self::$wpdb->prefix,
		]);
	}

	public static function getInstance(): DB
	{
		$cls = static::class;
		if (!isset(self::$instances[$cls])) {
			self::$instances[$cls] = new static();
		}

		return self::$instances[$cls];
	}

	public static function __callStatic(string $method, array $args)
	{
		return self::getInstance()::$medoo->{$method}(...$args);
	}

	public function __get(string $name)
	{
		return self::getInstance()::$medoo->{$name};
	}

	public function __wakeup()
	{
		throw new Exception("Cannot unserialize a singleton.");
	}

	protected function __clone()
	{
	}

	public static function wpdb(): wpdb
	{
		/** @var wpdb $wpdb */
		global $wpdb;

		return $wpdb;
	}

	public static function db(): Medoo
	{
		return self::getInstance()::$medoo;
	}
}
