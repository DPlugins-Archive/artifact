<?php

namespace AncientWorks\Artifact\Utils;

use AncientWorks\Artifact\Artifact;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class Notice
{
    public const ERROR = 'error';
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const INFO = 'info';

    public static function lists()
    {
        $notices = Utils::get_option('_notices', []);

        Utils::update_option('_notices', []);

        return $notices;
    }

    public static function add($status, $message, $key = false, $learn_more = false)
    {
        $notices = Utils::get_option('_notices', []);

        $payload = [
            'status' => $status,
            'message' => $message,
        ];

        if ($learn_more) {
            $payload['learn_more'] = $learn_more;
        }

        if ($key) {
            $notices[$key] = $payload;
        } else {
            $notices[] = $payload;
        }

        Utils::update_option('_notices', $notices);
    }

    public static function adds( $status, $messages ) {
		if ( ! is_array( $messages ) ) {
			$messages = [ $messages ];
		}

		foreach ( $messages as $message ) {
            if (!is_array($message)) {
                self::add($status, $message);
            } else {
                self::add($status, $message[0], $message[1], $message[2]);
            }
		}
	}

	public static function success( $message, $key = false, $learn_more = false ) {
		self::add( self::SUCCESS, $message, $key, $learn_more );
	}

	public static function warning( $message, $key = false, $learn_more = false ) {
		self::add( self::WARNING, $message, $key, $learn_more );
	}

	public static function info( $message, $key = false, $learn_more = false ) {
		self::add( self::INFO, $message, $key, $learn_more );
	}

	public static function error( $message, $key = false, $learn_more = false ) {
		self::add( self::ERROR, $message, $key, $learn_more );
	}
}
