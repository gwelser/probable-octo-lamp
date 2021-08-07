<?php
/**
 * Pagination
 *
 * @package gwelser-qgiv
 */

namespace GW;

/**
 * Pagination class
 */
class Pagination {

	/**
	 * The current page
	 *
	 * @var int
	 */
	private $page;

	/**
	 * The number of items per page
	 *
	 * @var int
	 */
	private $records_per_page;

	/**
	 * The total number of items
	 *
	 * @var int
	 */
	private $total_records;

	/**
	 * The current url query.
	 *
	 * @var array
	 */
	private $url_query;

	/**
	 * Class constructor
	 *
	 * @param int   $page The current page.
	 * @param int   $records_per_page The number of items per page.
	 * @param int   $total_records The total number of items.
	 * @param array $current_query The the current url query.
	 */
	public function __construct( $page, $records_per_page, $total_records, $current_query ) {

		$this->page             = $page;
		$this->records_per_page = $records_per_page;
		$this->total_records    = $total_records;
		$this->url_query        = $current_query;

	}

	/**
	 * Returns the current page
	 *
	 * @return int
	 */
	private function get_current_page() {

		return $this->page;
	}

	/**
	 * Returns the total number of pages
	 *
	 * @return int
	 */
	private function get_total_pages() {

		return ceil( $this->total_records / $this->records_per_page );
	}

	/**
	 * Return the previous page number or false if on the first page
	 *
	 * @return int|false
	 */
	private function get_previous_page() {

		return 1 < $this->page ? $this->page - 1 : false;
	}

	/**
	 * Return the next page number or false if on the last page
	 *
	 * @return int|false
	 */
	private function get_next_page() {

		return $this->page < $this->get_total_pages() ? $this->page + 1 : false;
	}

	/**
	 * Set url parameters
	 *
	 * @param int $page The next or previous page.
	 *
	 * @return string
	 */
	private function set_pagination_url( $page ) {

		$this->url_query['pg'] = $page;

		return http_build_query( $this->url_query );
	}

	/**
	 * Render pagination markup
	 *
	 * @return string
	 */
	public function render() {

		ob_start();
		?>
		<div class="pagination">
			<?php if ( false !== $this->get_previous_page() ) { ?>
			<a class="pagination__link" href="?<?php echo $this->set_pagination_url( $this->get_previous_page() ); ?>">Previous</a>
			<?php } ?>
			<span class="pagination__text">Page <?php echo $this->get_current_page(); ?> of <?php echo $this->get_total_pages(); ?></span>
			<?php if ( false !== $this->get_next_page() ) { ?>
			<a  class="pagination__link"href="?<?php echo $this->set_pagination_url( $this->get_next_page() ); ?>">Next</a>
			<?php } ?>
		</div>
		<?php

		return ob_get_clean();
	}

}
