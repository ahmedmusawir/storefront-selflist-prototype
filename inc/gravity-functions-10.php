<?php

/**
 * 
 * GRAVITY AFTER SUBMISSION 
 * 
 */
add_action( 'gform_after_submission_3', 'custom_action_after_apc', 10, 2 );
function custom_action_after_apc( $entry, $form ) {
 
    //if the Advanced Post Creation add-on is used, more than one post may be created for a form submission
    //the post ids are stored as an array in the entry meta
    $created_posts = gform_get_meta( $entry['id'], 'gravityformsadvancedpostcreation_post_id' );
    foreach ( $created_posts as $post )
    {
        $post_id = $post['post_id'];
        // Do your stuff here.
        // echo '<h1> THE post_id </h1>';
        // echo $post_id;

        $cookie_name = "POST_ID";
        $cookie_value = $post_id;
        setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/"); // 86400 = 1 day

    }

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
    'post_type' => 'listings',
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

   // =============================== START COLLECTING VARS FROM $ENTRY =========================================

   $transaction_type = rgar($entry, 'transaction_type');
   $transaction_success = rar($entry, 'is_fulfilled');
   $post_id = rgar($entry, '7');
   $duration = rgar($entry, '4');

       // Updating The Post Status to PUBLISH
       wp_update_post(array(
        'ID' => $post_id,
        'post_type' => 'listings',
        'post_status' => 'publish'
      ));


   // =============================== END OF COLLECTING VARS FROM $ENTRY ========================================

   // =============================== START OF SINGLE TRANSACTION ========================================
   
   // tran type 1 = SINGLE PAYMENT WITH A DEADLINE

   if ( $transaction_type == 1  && $transaction_success == 1 ) {

    $minutes = 0;

    if ( $duration == 1 ) {
      $minutes = 5;
    } elseif ( $duration == 2 ) {
      $minutes = 10;
    } elseif ( $duration == 3 ) {
      $minutes = 20;
    } elseif ( $duration == 4 ) {
      $minutes = 30;
    } elseif ( $duration == 5 ) {
      $minutes = 60;
    } else {
      $minutes = 5;
    }

    // Updating The Post Status to DRAFT after certain time

    wp_schedule_single_event( time() + $minutes + 60, 'my_new_event', array( $post_id ) );

   }

   // =============================== END OF SINGLE TRANSACTION ========================================
   
   // =============================== STARTING OF RECURRING TRANSACTION ========================================

  //  if ( $transaction_type == 2  && $transaction_success == 1 ) {

  //   // Updating The Post Status to PUBLISH
  //   wp_update_post(array(
  //     'ID' => $post_id,
  //     'post_type' => 'listings',
  //     'post_status' => 'publish'
  //   ));

  //  }

   // =============================== END OF RECURRING TRANSACTION ========================================
  
$message = '';

    $message .= '<h1> THE NEW: $entry DETAILS </h1>';
    
    foreach ( $entry as $key => $value ) {

      $message .= '<tr>';
      $message .= '<th>Key: </th>' . '<td>' . $key . '</td> <th>Value: </th>' . '<td>' . $value . '</td>';
      $message .= '</tr>';

    }

    // JUST MAKE SURE THIS GRAVITY HOOK IS WORKNG
    //add filter to enable HTML messages
    add_filter('wp_mail_content_type','set_email_to_html'); 
      
    //send yourself an email (replace the email address below with yours)
    // wp_mail('moose@cyberizegroup.com','JUST PAYPAL HOOK6 with DRAFT TARGET ID 25 UPDATING OUTSIDE CRON ...', $message2 );
    wp_mail('moose@cyberizegroup.com','VAR DUMP OF GRAVITY FULLFILLMENT 28 JULY 2020 ...', $message );

    //remove filter (to set back to plain text email)
    remove_filter( 'wp_mail_content_type', 'set_email_to_html' );

  }
  
add_action('gform_paypal_fulfillment', 'membership_payment_processing', 10, 4);

//sets the email content type to HTML
function set_email_to_html(){
	return true;
}


/** ============== END FUNCTION & ADD ACTION FOR WP-CRON TO SET UP PRIOR TO CALLING THE WP_SCHEDULE_SINGLE_EVENT ================================ */