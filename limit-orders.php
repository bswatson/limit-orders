<?php
/**
 * Plugin Name: Limit Orders for WooCommerce
 * Description: Automatically disable WooCommerce's checkout process after reaching a maximum number of orders.
 * Author:      Nexcess
 * Author URI:  https://nexcess.net
 * Text Domain: limit-orders
 * Domain Path: /languages
 * Version:     1.1.2
 *
 * WC requires at least: 3.9
 * WC tested up to:      4.0
 *
 * @package Nexcess\LimitOrders
 */

namespace Nexcess\LimitOrders;

/**
 * Register a PSR-4 autoloader.
 *
 * @param string $class The classname we're attempting to load.
 */
spl_autoload_register( function ( string $class ) {
	$filepath = str_replace( __NAMESPACE__ . '\\', '', $class );
	$filepath = __DIR__ . '/src/' . str_replace( '\\', '/', $filepath ) . '.php';

	if ( is_readable( $filepath ) ) {
		include_once $filepath;
	}
} );

// Initialize the plugin.
add_action( 'woocommerce_loaded', function () {
	$limiter = new OrderLimiter();
	$admin   = new Admin( $limiter );

	// Initialize hooks.
	$limiter->init();
	$admin->init();

	// Turn off ordering if we've reached the defined limits.
	if ( $limiter->has_reached_limit() ) {
		$limiter->disable_ordering();
	}
} );