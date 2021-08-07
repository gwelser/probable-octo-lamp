<?php
/**
 * Utility
 *
 * @package gwelser-qgiv
 */

namespace GW;

/**
 * Utility class
 */
class Utility {

	/**
	 * Get a random transaction amount
	 *
	 * @param int $min Optional. Minimum amount.
	 * @param int $max Optional. Maximum amount.
	 *
	 * @return int
	 */
	public static function get_random_amount( $min = 1, $max = 1000 ) {

		return rand( $min, $max );
	}

	/**
	 * Get a random method of payment
	 *
	 * @return string
	 */
	public static function get_random_method() {

		$method = array( 'Visa', 'Mastercard', 'Discover', 'American Express', 'eCheck' );
		shuffle( $method );

		return $method[0];
	}

	/**
	 * Get a random status
	 *
	 * @return string
	 */
	public static function get_random_status() {

		$status = array( 'OPEN', 'PROCESSING', 'ACCEPTED', 'DECLINED' );
		shuffle( $status );

		return $status[0];
	}
}
