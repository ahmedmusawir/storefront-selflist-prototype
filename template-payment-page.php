<?php
/**
 * The template for displaying all pages.
 * Template Name: Payment Page
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

get_header(); ?>

<?php 
/**
 * RETRIEVE COOKIE
 */
$cookie_name = "POST_ID";
        
if(!isset($_COOKIE[$cookie_name])) {
  echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
  // echo "Cookie '" . $cookie_name . "' is set!<br>";
  // echo "Value is: " . $_COOKIE[$cookie_name];
  $post_id = $_COOKIE[$cookie_name];
}
?>
<!-- <form action="self" method="post"> -->
<form action="/main-form" method="post">
  <ul class="list-group">
    <li class="list-group-item">Open Ended List<input type="radio" name="answer" value="recurring" /></li>
    <li class="list-group-item">A List with a deadline <input type="radio" name="answer" value="single" /></li>
    <li class="list-group-item">
      <span class="text-primary">
        If you've chosen A List with a deadline, pls choose a time frame from the dropdown:
        (Default is 5 min)
      </span>
    </li>
    <li class="list-group-item">
      <select class="form-control" name="duration">
        <option value=1>5 min (1)</option>
        <option value=2>10 Min (2)</option>
        <option value=3>20 Min (3)</option>
        <option value=4>30 Min (4)</option>
        <option value=5>1 Hr (5)</option>
      </select>
    </li>
    <li class="list-group-item"><input type="submit" value="submit" /></li>
  </ul>


</form>

<?php 
  $answer = false;
  $duration = false;
  
  if(isset($_POST['answer'])) {
  
    $answer = $_POST['answer'];  

  }    
  if(isset($_POST['duration'])) {
  
    $duration = $_POST['duration'];  

  }  
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

    <?php if ($answer == 'single') : ?>
    <?php $post_id ?>
    <h1>
      A LIST WITH A DEADLINE (SINGLE PAYMENT)
      <a class="btn btn-lg btn-info"
        href="/payment-form-page/?POST_ID=<?php echo $post_id; ?>&PAYMENT_TYPE=SINGLE&NUMBER_OF_LISTS=<?php echo $duration; ?>">
        PAY FOR THE LIST
      </a>
      Duration: <?php echo $duration; ?>
    </h1>

    <?php else : ?>

    <h1>
      OPEN ENDED LIST (SUBSCRIPTION)
      <a class="btn btn-lg btn-success"
        href="/payment-form-page/?POST_ID=<?php echo $post_id; ?>&PAYMENT_TYPE=RECURRING&NUMBER_OF_LISTS=1">
        PAY FOR THE SUBSCRIPTION
      </a>
    </h1>

    <?php endif; ?>

  </main><!-- #main -->
</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();