<?php
/**
 * Database
 *
 * @package gwelser-qgiv
 */

namespace GW;

/**
 * Database class
 */
class Database {

	/**
	 * Connect to database
	 *
	 * @return \mysqli
	 */
	public static function connect() {

		$db = new \mysqli( DATABASE_SERVER, DATABASE_USER, DATABASE_PASS, DATABASE_NAME );
		if ( $db->connect_errno ) {
			echo sprintf( 'Connection failed: %s', $db->connect_error );
			die();
		}

		return $db;
	}
}
