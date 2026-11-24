<?php
/**
 * Plugin Name: Code & Spanner Service Notice
 * Description: Self-owned Gutenberg demonstration: an editable, server-rendered service notice.
 * Version: 1.0.0
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author: Code & Spanner
 * Text Domain: csp-service-notice
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
    register_block_type( __DIR__ );
} );
