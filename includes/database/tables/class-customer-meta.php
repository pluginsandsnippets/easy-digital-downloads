<?php
/**
 * Customer Meta Table.
 *
 * @package     EDD
 * @subpackage  Database\Tables
 * @copyright   Copyright (c) 2018, Easy Digital Downloads, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.0
 */
namespace EDD\Database\Tables;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( class_exists( '\\EDD\\Database\\Tables\\Base' ) ) :
/**
 * Setup the global "edd_customermeta" database table
 *
 * @since 3.0
 */
final class Customer_Meta extends Base {

	/**
	 * Table name
	 *
	 * @access protected
	 * @since 3.0
	 * @var string
	 */
	protected $name = 'edd_customermeta';

	/**
	 * Database version
	 *
	 * @access protected
	 * @since 3.0
	 * @var int
	 */
	protected $version = 201805290002;

	/**
	 * Setup the database schema
	 *
	 * @access protected
	 * @since 3.0
	 * @return void
	 */
	protected function set_schema() {
		$max_index_length = 191;
		$this->schema     = "meta_id bigint(20) unsigned NOT NULL auto_increment,
			edd_customer_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) DEFAULT NULL,
			meta_value longtext DEFAULT NULL,
			PRIMARY KEY (meta_id),
			KEY edd_customer_id (edd_customer_id),
			KEY meta_key (meta_key({$max_index_length}))";
	}

	/**
	 * Override the Base class `maybe_upgrade()` routine to do a very unique and
	 * special check against the old option.
	 *
	 * Maybe upgrades the database table from 2.x to 3.x standards. This method
	 * should be kept up-to-date with schema changes in `set_schema()` above.
	 *
	 * - Hooked to the "admin_init" action.
	 * - Calls the parent class `maybe_upgrade()` method
	 *
	 * @since 3.0
	 */
	public function maybe_upgrade() {
		if ( false !== get_option( $this->prefix . 'edd_customermeta_version', false ) ) {
			delete_option( $this->prefix . 'edd_customermeta_version' );
			$this->get_db()->query( "
				ALTER TABLE {$this->table_name} CHANGE `customer_id` `edd_customer_id` bigint(20) unsigned NOT NULL default '0';
			" );
		}

		parent::maybe_upgrade();
	}
}
endif;