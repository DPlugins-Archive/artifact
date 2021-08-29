<?php

namespace AncientWorks\Artifact\Utils;

use League\Plates\Engine;
use Medoo\Medoo;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class Utils
{
	public static function db(): Medoo
	{
		return DB::db();
	}

    public static function template(): Engine
    {
        return Template::template();
    }

    public static function redirect($location)
    {
        if (headers_sent() === false) {
            wp_redirect($location);
        } else {
            echo '<meta http-equiv="refresh" content="0;url='.$location.'">';
        }
        exit;
    }
}
