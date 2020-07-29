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
function perform_UNLIST_now( $post_id, $transaction_id, $transaction_amount, $minutes ) {

  $my_post_title = get_the_title($post_id);

  // ====== START PUBLISH LIST AFTER PAYPAL SUCCESS ========
    // Updating The Post Status to PUBLISH
    wp_update_post(array(
      'ID' => $post_id,
      'post_type' => 'listings',
      'post_status' => 'draft'
    ));
  // ====== END PUBLISH LIST AFTER PAYPAL SUCCESS ==========
    
    
  // ====== START SEND EMAIL AFTER CRON SUCCESS ============
  
    $message = "<h3>The Post with Title:</h3>
    <h3>$my_post_title</h3>
    <h3>... Has been Drafted!</h3> 
    <h1>SCHEDULED DRAFING SUCCESS!</h1>
    <h4>Payment Amount: $amount</h4> 
    <h4>Payment Amount: $transaction_id</h4>;
    <h3>Drafted After: $minutes MINUTES</h3>";
    
      //add filter to enable HTML messages
      add_filter('wp_mail_content_type','set_email_to_html'); 
        
      //send yourself an email (replace the email address below with yours)
      wp_mail('moose@cyberizegroup.com','Cron Post Draft Status in a minute ...', $message);
  
      //remove filter (to set back to plain text email)
      remove_filter( 'wp_mail_content_type', 'set_email_to_html' );

  // ====== END SEND EMAIL AFTER CRON SUCCESS ============

}
add_action( 'UNLIST_event', 'perform_UNLIST_now', 10, 4 );

/**
 * Below is a fictional example of adding a successful order to a third party order fulfillment system.
 */

function membership_payment_processing($entry, $feed, $transaction_id, $amount) {

  // =============================== START COLLECTING VARS FROM $ENTRY =========================================

  //$transaction_success = rgar($entry, 'payment_status'); // DID NOT WORK
   $transaction_id = $entry['transaction_id'];
   $transaction_success = $entry['is_fulfilled'];
   $transaction_status = $entry['payment_status']; 
   $transaction_amount = $entry['payment_amount']; 
   $transaction_type = $entry['6'];

   $post_id = $entry['7'];
   $duration = $entry['4'];

  //  $post_id = rgar($entry, '7');
  //  $transaction_type = rgar($entry, '6');
  //  $duration = rgar($entry, '4');


  // =============================== END OF COLLECTING VARS FROM $ENTRY ========================================

  // =============================== START FULFILMENT TASKS AFTER PAYPAL SUCCESS =========================================

      if ( $transaction_success == 1 && $transaction_status == 'Paid' || $transaction_status == 'Active') {


         // ====== START PUBLISH LIST AFTER PAYPAL SUCCESS ========
            // Updating The Post Status to PUBLISH
            wp_update_post(array(
              'ID' => $post_id,
              'post_type' => 'listings',
              'post_status' => 'publish'
            ));
         // ====== END PUBLISH LIST AFTER PAYPAL SUCCESS ==========

         // ====== START PROCESS SINGLE PAYMENT AFTER PAYPAL SUCCESS ======

             if ( $transaction_type == 'SINGLE' ) {

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
          
              wp_schedule_single_event( time() + $minutes + 60, 'UNLIST_event', array( $post_id, $transaction_id, $transaction_amount, $minutes ) );

             }

         // ====== END PROCESS SINGLE PAYMENT AFTER PAYPAL SUCCESS ========

         // ====== START PROCESS SINGLE PAYMENT AFTER PAYPAL SUCCESS ======

         if ( $transaction_type == 'RECURRING' ) {

            $SUBSCRIPTION_message = '';
            $SUBSCRIPTION_message = '<h1>Post ID: </h1>' . $post_id;
            $SUBSCRIPTION_message .= '<h1>Tran Type: </h1>' . $transaction_type;
            $SUBSCRIPTION_message .= '<h1>Tran Fulfilment: </h1>' . $transaction_success;
            $SUBSCRIPTION_message .= '<h1>Payment Status: </h1>' . $transaction_status;

            // JUST MAKE SURE THIS GRAVITY HOOK IS WORKNG
            //add filter to enable HTML messages
            add_filter('wp_mail_content_type','set_email_to_html'); 
              
            //send yourself an email (replace the email address below with yours)
            // wp_mail('moose@cyberizegroup.com','JUST PAYPAL HOOK6 with DRAFT TARGET ID 25 UPDATING OUTSIDE CRON ...', $SUBSCRIPTION_message2 );
            wp_mail('moose@cyberizegroup.com','A SUBSCRIPTION PAYMENT SUCCESS ...', $SUBSCRIPTION_message );

            //remove filter (to set back to plain text email)
            remove_filter( 'wp_mail_content_type', 'set_email_to_html' );

         }

        // ====== END PROCESS SINGLE PAYMENT AFTER PAYPAL SUCCESS ========




      }


  // =============================== END FULFILMENT TASKS AFTER PAYPAL SUCCESS ===========================================


  // =============================== START SENDING EMAIL MESSAGE ABOUT THE TRANSACTION =========================================


    $message = '';
    $message = '<h1>Post ID: </h1>' . $post_id;
    $message .= '<h1>Tran Type: </h1>' . $transaction_type;
    $message .= '<h1>Tran Fulfilment: </h1>' . $transaction_success;
    $message .= '<h1>Payment Status: </h1>' . $transaction_status;
    $message .= '<h1>Duration: </h1>' . $duration;

    $message .= '<h1> THE NEW: $entry DETAILS PROTOTYPE </h1>';
    
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
    wp_mail('moose@cyberizegroup.com','VAR DUMP OF GRAVITY FULLFILLMENT 29 JULY 2020 ...', $message );

    //remove filter (to set back to plain text email)
    remove_filter( 'wp_mail_content_type', 'set_email_to_html' );

  }

   // =============================== END SENDING EMAIL MESSAGE ABOUT THE TRANSACTION =========================================

  
add_action('gform_paypal_fulfillment', 'membership_payment_processing', 10, 4);

//sets the email content type to HTML
function set_email_to_html(){
	return true;
}


/** ============== END FUNCTION & ADD ACTION FOR WP-CRON TO SET UP PRIOR TO CALLING THE WP_SCHEDULE_SINGLE_EVENT ================================ */