<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Below is a fictional example of adding a successful order to a third party order fulfillment system.
 */
add_action( 'gform_paypal_fulfillment', 'process_order', 10, 4 );
function process_order( $entry, $feed, $transaction_id, $amount ) {
 
    // get first and last name from $entry
    // $order_id = rgar( $entry, 'id' );
    // $first_name = rgar( $entry, '2.3' );
    // $last_name = rgar( $entry, '2.6' );
 
    // use fictional function to add order to fictional My Third Party application
    // mtp_add_order( $transaction_id, $amount, $order_id, $first_name, $last_name );

    echo "=====================================";
    echo 'THIS IS $entry';
    echo "=====================================";
    echo "<pre>";
    $entry_result = print_r($entry, true);
    echo "</pre>";

    echo "=====================================";
    echo 'THIS IS $feed';
    echo "=====================================";
    echo "<pre>";
    $feed_result = print_r($feed, true);
    echo "</pre>";

    echo "=====================================";
    echo 'THIS IS $transaction_id';
    echo "=====================================";
    echo "<pre>";
    $tran_id = print_r($transaction_id);
    echo "</pre>";

    $myfile = fopen("PAYPAL_RESULTS.txt", "w") or die("Unable to open file!");
    // $txt = "John Doe\n";
    fwrite($myfile, "============================================");
    fwrite($myfile, "========= THIS IS THE ENTRY =================");
    fwrite($myfile, "============================================");
    fwrite($myfile, $entry_result);
		fwrite($myfile, "============================================");
		fwrite($myfile, "========= THIS IS THE FEED =================");
    fwrite($myfile, "============================================");
    // $txt = "Jane Doe\n";
    fwrite($myfile, $feed_result);
    fclose($myfile);

    die('End of gform_paypal_fulfillment');
 
}




/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */