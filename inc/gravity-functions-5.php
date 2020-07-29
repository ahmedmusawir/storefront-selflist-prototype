<?php



/**
 * Below is a fictional example of adding a successful order to a third party order fulfillment system.
 */

function membership_payment_processing($entry, $feed, $transaction_id, $amount) {

   // =============================== START OF CRON SETUP FOR MOOSE =========================================

  /**
   * WP CRON SCHEDULE AN EMAIL IN A MINUTE
   */
  $post_id = 23;

      // Updating The Post Status to Draft
      wp_update_post(array(
        'ID' => $post_id,
        'post_status' => 'draft'
      ));

  function do_this_in_a_minute( $post_id ) {

    $my_post_title = get_the_title($post_id);

    // Updating The Post Status to Draft
    wp_update_post(array(
      'ID' => $post_id,
      'post_status' => 'draft'
    ));
      
      $message = "<h3>The Post with Title:</h3>
      <h3>$my_post_title</h3>
      <h3>... Has been Drafted!</h3> 
      <h1>PAYPAL SANDBOX PAYMENT IS A SUCCESS!</h1>
      <h4>Payment Amount: $amount</h4> 
      <h4>Payment Amount: $transaction_id</h4>";
      
        //add filter to enable HTML messages
        add_filter('wp_mail_content_type','set_email_to_html'); 
          
        //send yourself an email (replace the email address below with yours)
        wp_mail('moose@cyberizegroup.com','Cron Post Draft Status in a minute ...', $message);
    
        //remove filter (to set back to plain text email)
        remove_filter( 'wp_mail_content_type', 'set_email_to_html' );
  }
  add_action( 'my_new_event', 'do_this_in_a_minute', 10, 1 );
 
// put this line inside a function, 
// presumably in response to something the user does
// otherwise it will schedule a new event on every page visit
 
wp_schedule_single_event( time() + 60, 'my_new_event', array( $post_id ) );
 
// time() + 3600 = one hour from now.

// =============================== END OF CRON SETUP FOR MOOSE =========================================

$message2 = "<h1>PAYPAL ONLY PAYMENT IS A SUCCESS!</h1>
<h4>Payment Amount: $amount</h4> 
<h4>Payment Amount: $transaction_id</h4>";

// JUST MAKE SURE THIS GRAVITY HOOK IS WORKNG
        //add filter to enable HTML messages
        add_filter('wp_mail_content_type','set_email_to_html'); 
          
        //send yourself an email (replace the email address below with yours)
        wp_mail('moose@cyberizegroup.com','JUST PAYPAL HOOK5 with DRAFT TARGET ID 23 UPDATING OUTSIDE CRON ...', $message2 );
    
        //remove filter (to set back to plain text email)
        remove_filter( 'wp_mail_content_type', 'set_email_to_html' );
    
  }
  
  add_action('gform_paypal_fulfillment', 'membership_payment_processing', 10, 4);

//sets the email content type to HTML
function set_email_to_html(){
	return true;
}

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