<?php
/**
 * Setup
 *
 * @package gwelser-qgiv
 */

namespace GW;

/**
 * Setup class
 */
class Setup {

	/**
	 * Initialize
	 */
	public static function init() {

		self::maybe_create_users_table();
		self::maybe_create_transactions_table();

		if ( self::maybe_add_data() ) {
			self::add_data();
		}

	}

	/**
	 * Create Users table
	 */
	private static function maybe_create_users_table() {

		$db = Database::connect();
		$db->query( 'CREATE TABLE IF NOT EXISTS Users (id INT NOT NULL AUTO_INCREMENT, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthdate DATETIME NOT NULL, PRIMARY KEY (id));' );
		$db->close();

	}

	/**
	 * Create Transactions table
	 */
	private static function maybe_create_transactions_table() {

		$db = Database::connect();
		$db->query( 'CREATE TABLE IF NOT EXISTS Transactions (id INT NOT NULL AUTO_INCREMENT, user_id INT NULL, amount FLOAT NULL, status VARCHAR(255) NULL, method VARCHAR(255) NULL, timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id));' );
		$db->close();

	}

	/**
	 * Check if user data has been imported. Returns true if there are no users.
	 *
	 * @return bool
	 */
	private static function maybe_add_data() {

		$db     = Database::connect();
		$result = $db->query( 'SELECT COUNT(id) AS user_count FROM Users' );
		$row    = $result->fetch_assoc();
		$count  = (int) $row['user_count'];

		$db->close();

		return 0 === $count;
	}

	/**
	 * Add user and transaction data to database
	 */
	private static function add_data() {

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_URL, 'https://randomuser.me/api/?results=500&nat=us&exc=login,id,nat' );
		$result = curl_exec( $ch );
		curl_close( $ch );
		$randomusers = json_decode( $result );
		foreach ( $randomusers->results as $randomuser ) {

			$user = new User( null, $randomuser->name->first, $randomuser->name->last, $randomuser->email, $randomuser->dob->date );
			$user->save();

			$number_of_transactions = rand( 1, 5 );
			for ( $i = 1;$i <= $number_of_transactions; $i++ ) {
				Transaction::add_transaction( $user->get_id(), Utility::get_random_amount(), Utility::get_random_status(), Utility::get_random_method() );
			}
		}

	}

}
