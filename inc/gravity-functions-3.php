<?php

// =============================== START OF CRON SETUP FOR MOOSE =========================================

/**
 * WP CRON SCHEDULE AN EMAIL IN A MINUTE
 */
$arg1 = 'The Post';
$arg2 = 'Post ID';
$arg3 = 'Post Status';

function do_this_in_a_minute( $arg1, $arg2, $arg3 ) {
    
    $message = "<h3>$arg1</h3><h3>$arg2</h3><h3>$arg3</h3>";
    
       //add filter to enable HTML messages
       add_filter('wp_mail_content_type','set_email_to_html'); 
        
       //send yourself an email (replace the email address below with yours)
       wp_mail('moose@cyberizegroup.com','Cron Email for Moose in a minute ...', $message);
   
       //remove filter (to set back to plain text email)
       remove_filter( 'wp_mail_content_type', 'set_email_to_html' );
}
add_action( 'my_new_event', 'do_this_in_a_minute', 10, 3 );
 
// put this line inside a function, 
// presumably in response to something the user does
// otherwise it will schedule a new event on every page visit
 
wp_schedule_single_event( time() + 60, 'my_new_event', array( $arg1, $arg2, $arg3 ) );
 
// time() + 3600 = one hour from now.

// =============================== END OF CRON SETUP FOR MOOSE =========================================

/**
 * Below is a fictional example of adding a successful order to a third party order fulfillment system.
 */

function membership_payment_processing($entry, $feed, $transaction_id, $amount) {

    $message = '';

    $message .= '<h1> THE $entry DETAILS </h1>';
    
    $message .= '<table>';
    
    foreach ( $entry as $key => $value ) {

      $message .= '<tr>';
      $message .= '<th>Key: </th>' . '<td>' . $key . '</td> <th>Value: </th>' . '<td>' . $value . '</td>';
      $message .= '</tr>';

    }

    $message .= '</table>';

    $message .= '<h1> THE $feed DETAILS </h1>';

    $message .= '<table>';
    
    foreach ( $feed as $key => $value ) {

      $message .= '<tr>';
      $message .= '<th>Key: </th>' . '<td>' . $key . '</td> <th>Value: </th>' . '<td>' . $value . '</td>';
      $message .= '</tr>';

    }
    
    $message .= '</table>';

    
    //add filter to enable HTML messages
    add_filter('wp_mail_content_type','set_email_to_html'); 
        
    //send yourself an email (replace the email address below with yours)
    wp_mail('moose@cyberizegroup.com','Gravity PayPal Information', $message);

    //remove filter (to set back to plain text email)
	remove_filter( 'wp_mail_content_type', 'set_email_to_html' );
    
  }
  
  add_action('gform_paypal_fulfillment', 'membership_payment_processing', 10, 4);

//sets the email content type to HTML
function set_email_to_html(){
	return true;
}

// THE OLD ONE THAT DIDN'T WORK  
// add_action( 'gform_paypal_fulfillment', 'process_order', 10, 4 );
// function process_order( $entry, $feed, $transaction_id, $amount ) {
 
    // get first and last name from $entry
    // $order_id = rgar( $entry, 'id' );
    // $first_name = rgar( $entry, '2.3' );
    // $last_name = rgar( $entry, '2.6' );
 
    // use fictional function to add order to fictional My Third Party application
    // mtp_add_order( $transaction_id, $amount, $order_id, $first_name, $last_name );

    // echo "=====================================";
    // echo 'THIS IS $entry';
    // echo "=====================================";
    // echo "<pre>";
    // $entry_result = print_r($entry, true);
    // echo "</pre>";

    // echo "=====================================";
    // echo 'THIS IS $feed';
    // echo "=====================================";
    // echo "<pre>";
    // $feed_result = print_r($feed);
    // echo "</pre>";

    // echo "=====================================";
    // echo 'THIS IS $transaction_id';
    // echo "=====================================";
    // echo "<pre>";
    // $tran_id = print_r($transaction_id);
    // echo "</pre>";

    // $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    // // $txt = "John Doe\n";
    // fwrite($myfile, $txt);
    // // $txt = "Jane Doe\n";
    // fwrite($myfile, $txt);
    // fclose($myfile);

    // die('End of gform_paypal_fulfillment');
 
// }


/**
 * Proper ob_end_flush() for all levels
 *
 * This replaces the WordPress `wp_ob_end_flush_all()` function
 * with a replacement that doesn't cause PHP notices.
 */
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action( 'shutdown', function() {
   while ( @ob_end_flush() );
} );