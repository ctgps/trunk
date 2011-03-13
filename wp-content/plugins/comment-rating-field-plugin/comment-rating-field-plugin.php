<?php
/**
* Plugin Name: Comment Rating Field Plugin
* Plugin URI: http://www.n7studios.co.uk/2010/06/04/wordpress-comment-rating-field-plugin/
* Version: 1
* Author: <a href="http://www.n7studios.co.uk/">Tim Carr</a>
* Description: Adds a 5 star rating field to the comments form in Wordpress.  Requires Wordpress 3.0+
*/

/**
* Comment Rating Field Plugin Class
* 
* @package Wordpress
* @subpackage Comment Rating Field Plugin
* @author Tim Carr
* @version 1
* @copyright n7 Studios
*/
class CommentRatingFieldPlugin {
    /**
    * Constructor.  Adds custom write panels and save custom field hooks
    */
    function CommentRatingFieldPlugin() {
        // Action and Filter Hooks
        add_action('comment_post', array(&$this, 'SaveRating')); // Save Rating Field
        add_action('comment_text', array(&$this, 'DisplayRating')); // Displays Rating on Comments              
        
        // Register and load CSS
        wp_register_style('crfp-rating-css', '/wp-content/plugins/comment-rating-field-plugin/css/rating.css');
        wp_enqueue_style('crfp-rating-css');
        
        // Register and Enqueue jQuery and Rating Scripts
        wp_register_script('crfp-jquery-rating-pack', '/wp-content/plugins/comment-rating-field-plugin/js/jquery.rating.pack.js');
        wp_register_script('crfp-jquery-rating-settings', '/wp-content/plugins/comment-rating-field-plugin/js/jquery.rating.settings.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('crfp-jquery-rating-pack');
        wp_enqueue_script('crfp-jquery-rating-settings');
    }
       
    /**
    * Saves the POSTed rating for the given comment ID to the comment meta table
    * 
    * @param int $commentID
    */
    function SaveRating($commentID) {
        add_comment_meta($commentID, 'crfp-rating', $_POST['crfp-rating'], true);
    }
    
    /**
    * Appends the rating to the end of the comment text for the given comment ID
    * 
    * @param text $comment
    */
    function DisplayRating($comment) {
        $rating = get_comment_meta(get_comment_ID(), 'crfp-rating', true);
        if ($rating == '') $rating = 0;
        return $comment.'<div class="crfp-rating crfp-rating-'.$rating.'"></div>';    
    }    
}
//$crfp = new CommentRatingFieldPlugin(); // Invoke class
?>
