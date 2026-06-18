<?php
/**
 * Plugin Name: Portfolio Demo Safety
 * Description: Prevents real emails, checkout payments, public registration, XML-RPC, and the old GTM container from running on the portfolio demo.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'SKIP_GTM' ) ) {
    define( 'SKIP_GTM', true );
}

// Pretend emails were sent successfully without contacting a mail server.
add_filter(
    'pre_wp_mail',
    static function ( $return, $atts ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'Portfolio demo blocked an outgoing email.' );
        }
        return true;
    },
    10,
    2
);

// Allow payment gateways locally for testing.
// Keep them disabled on a public portfolio demo.
add_filter(
    'woocommerce_available_payment_gateways',
    static function ( $gateways ) {
        if ( 'local' === wp_get_environment_type() ) {
            return $gateways;
        }

        if ( is_admin() && ! wp_doing_ajax() ) {
            return $gateways;
        }

        return array();
    },
    999
);

add_action(
    'woocommerce_before_checkout_form',
    static function () {
        if ( 'local' !== wp_get_environment_type() ) {
            echo '<div class="woocommerce-info">Portfolio demonstration only. Payments and order completion are disabled.</div>';
        }
    },
    5
);

// Do not let unknown visitors create accounts on a portfolio demo.
add_filter( 'pre_option_users_can_register', '__return_zero' );
add_filter( 'xmlrpc_enabled', '__return_false' );
