<?php

/**
 * 
 * GRAVITY AFTER SUBMISSION 
 * 
 */

add_action( 'gform_post_submission_3', 'set_post_content', 10, 2 );
function set_post_content( $entry, $form ) {


    //getting post
    $post = get_post( $entry['post_id'] );
 
     $message = '';

    $message .= '<h1> THE $entry DETAILS </h1>';

    $message .= '<table>';
    
    foreach ( $entry as $key => $value ) {

      $message .= '<tr>';
      $message .= '<th>Key: </th>' . '<td>' . $key . '</td> <th>Value: </th>' . '<td>' . $value . '</td>';
      $message .= '</tr>';

    }

    $message .= '</table>';

    // echo $entry('post_id');
    echo "<pre>";
    echo "POST ID:";
    print_r($entry['post_id']);
    echo "</pre>";
    echo $message;
 


    // die();

} 




// REMOVING ADMIN BAR FOR SUBSCRIBERS
if ( ! current_user_can( 'manage_options' ) ) {
  add_filter('show_admin_bar', '__return_false');
}

/** ================= END LOGIN REDIRECT AFTER SUBSCRIBER LOGIN ====================== */



/**
 * 
 * FUNCTION & ADD ACTION FOR WP-CRON TO SET UP PRIOR TO CALLING THE WP_SCHEDULE_SINGLE_EVENT
 * 
 */
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

/**
 * Below is a fictional example of adding a successful order to a third party order fulfillment system.
 */

function membership_payment_processing($entry, $feed, $transaction_id, $amount) {

   // =============================== START OF CRON SETUP FOR MOOSE =========================================

  /**
   * WP CRON SCHEDULE AN EMAIL IN A MINUTE
   */
  $post_id = 25;

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
    wp_mail('moose@cyberizegroup.com','JUST PAYPAL HOOK6 with DRAFT TARGET ID 25 UPDATING OUTSIDE CRON ...', $message2 );

    //remove filter (to set back to plain text email)
    remove_filter( 'wp_mail_content_type', 'set_email_to_html' );

  }
  
  add_action('gform_paypal_fulfillment', 'membership_payment_processing', 10, 4);

//sets the email content type to HTML
function set_email_to_html(){
	return true;
}


/** ============== END FUNCTION & ADD ACTION FOR WP-CRON TO SET UP PRIOR TO CALLING THE WP_SCHEDULE_SINGLE_EVENT ================================ */