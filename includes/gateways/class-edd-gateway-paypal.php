<?php
/**
 * PayPal Payment Gateway.
 *
 * @package     EDD
 * @subpackage  Gateways
 * @copyright   Copyright (c) 2017, Sunny Ratilal
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.7
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Gateway_PayPal extends EDD_Gateway {
	/**
	 * PayPal API Endpoint.
	 *
	 * @access private
	 * @since  2.7
	 * @var    string
	 */
	private $api_endpoint;

	/**
	 * Checkout URL.
	 *
	 * @access private
	 * @since  2.7
	 * @var    string
	 */
	private $checkout_url;

	/**
	 * PayPal API Username.
	 *
	 * @access protected
	 * @since  2.7
	 * @var    string
	 */
	protected $username;

	/**
	 * PayPal API Password.
	 *
	 * @access protected
	 * @since  2.7
	 * @var    string
	 */
	protected $password;

	/**
	 * PayPal API Signature.
	 *
	 * @access protected
	 * @since  2.7
	 * @var    string
	 */
	protected $signature;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since  2.7
	 *
	 * @return void
	 */
	public function __construct() {
		$this->ID = 'paypal';

		parent::__construct();
	}

	/**
	 * Initialise the gateway.
	 *
	 * @access public
	 * @since  2.7
	 *
	 * @return void
	 */
	public function init() {
		if ( $this->test_mode ) {
			$this->api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$this->username     = edd_get_option( 'paypal_test_api_username' );
			$this->password     = edd_get_option( 'paypal_test_api_password' );
			$this->signature    = edd_get_option( 'paypal_test_api_signature' );
		} else {
			$this->api_endpoint = 'https://api-3t.paypal.com/nvp';
			$this->username     = edd_get_option( 'paypal_live_api_username' );
			$this->password     = edd_get_option( 'paypal_live_api_password' );
			$this->signature    = edd_get_option( 'paypal_live_api_signature' );
		}
	}
}