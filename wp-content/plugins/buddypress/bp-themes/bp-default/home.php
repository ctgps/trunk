<?php get_header() ?>

	<div id="content">
		<div class="padder">
<?php


?>
		<?php do_action( 'bp_before_blog_home' ) ?>

		<div class="page" id="blog-latest">
		<div class="slideshow" >
<?php
if(is_front_page())
{
if(function_exists('wp_content_slider')) { wp_content_slider(); }
}

$numPosts = 0;
?>
</div>
<h2>最新文章</h2>
			<?php if ( have_posts() ) : ?>

				<?php while (have_posts()) : the_post(); ?>
					<?php ++$numPosts; if ( $numPosts==5 ) break;?>
					<?php do_action( 'bp_before_blog_post' ) ?>

					<div class="post" id="post-<?php the_ID(); ?>">

						<div class="author-box">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
							<p><?php printf( __( 'by %s', 'buddypress' ), bp_core_get_userlink( $post->post_author ) ) ?></p>
						</div>

						<div class="post-content">
							<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

							<p class="date"><?php the_time() ?> <em><?php _e( 'in', 'buddypress' ) ?> <?php the_category(', ') ?> <?php printf( __( 'by %s', 'buddypress' ), bp_core_get_userlink( $post->post_author ) ) ?></em></p>

							<div class="entry">
								<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
							</div>

							<p class="postmetadata"><span class="tags"><?php the_tags( __( 'Tags: ', 'buddypress' ), ', ', '<br />'); ?></span> <span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?></span></p>
						</div>

					</div>

					<?php do_action( 'bp_after_blog_post' ) ?>

				<?php endwhile; ?>
<!--  
				<div class="navigation">

					<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
					<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>

				</div>
-->
			<?php else : ?>

				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ) ?></h2>
				<p class="center"><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'buddypress' ) ?></p>

				<?php locate_template( array( 'searchform.php' ), true ) ?>

			<?php endif; ?>
		</div>
<?php 
function ctgps_list_latest5_of_a_category( $arg="cat=1" )
{
$second_query = new WP_Query( $arg.'&order=DESC&orderby=date&limit=1,5');

// The Loop
$i = 0;
while ( $i<5 ){
    ++$i;
    if( $second_query->have_posts() ) {
        $second_query->the_post();
        echo '<li><a class="title" href="';
        the_permalink();
        echo '">';
        the_title();
        echo '</a><span class="author"';   
        the_author_link();
        echo "</span></li>";
    }
    else{
        for( ; $i<=5; ++$i ) {
         echo "<li>&nbsp;</li>";
        }
    }
}


}

?>
<style type="text/css">
.category-latest{
width:350px;
float:left;
background:#eee;
margin:5px 20px;
}
.category-latest td{
padding:0;
}
</style>
		<div class="category-latest">
			<h2>新闻中心<a href="<?php echo get_category_link(10); ?>">更多...</a></h2>

			<?php ctgps_list_latest5_of_a_category("cat=10");?>
		
		</div>
	    
	    <div class="category-latest">
			<h2>我的大学<a href="<?php echo get_category_link(6); ?>">更多...</a></h2>			
			<?php ctgps_list_latest5_of_a_category("cat=6");?>
		</div>
		
	    <div class="category-latest">
			<h2>校园生活<a href="<?php echo get_category_link(7); ?>">更多...</a></h2>
			<?php ctgps_list_latest5_of_a_category("cat=7");?>
		</div>
		
		<div class="category-latest">
			<h2>新生指南<a href="<?php echo get_category_link(8); ?>">更多...</a></h2>
			<?php ctgps_list_latest5_of_a_category("cat=8");?>
		</div>
	    <div style="clear:both" ></div>
	    <?php wp_reset_postdata(); ?>
	    
	    
		<?php do_action( 'bp_after_blog_home' ) ?>
        <?php $comments=get_comments( array( "post_id" => 1, "orderby" => "comment_date"));   ?>
        
        <div id="comments">

			<?php
				// Only include comments
				$numTrackBacks = 0; $numComments = 0;
				foreach ( (array)$comments as $comment )
					if ( 'comment' != get_comment_type() )
						$numTrackBacks++; 
					else
						$numComments++;
			?>
			<h2>留言板</h2>
			<h3 id="comments">最新留言<span>(共有留言<?php echo $numComments;?>条)</span>
			</h3>

			<?php do_action( 'bp_before_blog_comment_list' ) ?>

			<ol class="commentlist">
				<?php  foreach ( $comments as $comment) {
				         bp_dtheme_blog_comments($comment, NULL, 1);				         
				        }
				//wp_list_comments( array( 'callback' => 'bp_dtheme_blog_comments' ) ); 
				?>
			</ol><!-- .comment-list -->

			<?php do_action( 'bp_after_blog_comment_list' ) ?>

			<?php if ( get_option( 'page_comments' ) ) : ?>

				<div class="comment-navigation paged-navigation">

					<?php paginate_comments_links(); ?>

				</div>

			<?php endif; ?>

		</div><!-- #comments -->
		
		<div id="respond">

			<div class="comment-avatar-box">
				<div class="avb">
					<?php if ( bp_loggedin_user_id() ) : ?>
						<a href="<?php echo bp_loggedin_user_domain() ?>">
							<?php echo get_avatar( bp_loggedin_user_id(), 50 ); ?>
						</a>
					<?php else : ?>
						<?php echo get_avatar( 0, 50 ); ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="comment-content">

				<h3 id="reply" class="comments-header">
					<?php comment_form_title( __( 'Leave a Reply', 'buddypress' ), __( 'Leave a Reply to %s', 'buddypress' ), true ); ?>
				</h3>

				<p id="cancel-comment-reply">
					<?php cancel_comment_reply_link( __( 'Click here to cancel reply.', 'buddypress' ) ); ?>
				</p>

				<?php if ( get_option( 'comment_registration' ) && !$user_ID ) : ?>

					<p class="alert">
						<?php printf( __('You must be <a href="%1$s" title="Log in">logged in</a> to post a comment.', 'buddypress'), wp_login_url( get_permalink() ) ); ?>
					</p>

				<?php else : ?>

					<?php do_action( 'bp_before_blog_comment_form' ) ?>

					<form action="<?php echo site_url( 'wp-comments-post.php' ) ?>" method="post" id="commentform" class="standard-form">
					<input type="hidden" name='redirect_to'  id='redirect_to'  value="<?php echo home_url();?>" />
					
					
						<?php if ( $user_ID ) : ?>

							<p class="log-in-out">
								<?php printf( __('Logged in as <a href="%1$s" title="%2$s">%2$s</a>.', 'buddypress'), bp_loggedin_user_domain(), $user_identity ); ?> <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e('Log out of this account', 'buddypress'); ?>"><?php _e('Log out &rarr;', 'buddypress'); ?></a>
							</p>

						<?php else : ?>

							<?php $req = get_option( 'require_name_email' ); ?>

							<p class="form-author">
								<label for="author"><?php _e('Name', 'buddypress'); ?> <?php if ( $req ) : ?><span class="required"><?php _e('*', 'buddypress'); ?></span><?php endif; ?></label>
								<input type="text" class="text-input" name="author" id="author" value="<?php echo $comment_author; ?>" size="40" tabindex="1" />
							</p>

							<p class="form-email">
								<label for="email"><?php _e('Email', 'buddypress'); ?> (选填) <?php if ( $req ) : ?><span class="required"><?php _e('*', 'buddypress'); ?></span><?php endif; ?></label>
								<input type="text" class="text-input" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="40" tabindex="2" />
							</p>
<!--  
							<p class="form-url">
								<label for="url"><?php _e('Website', 'buddypress'); ?></label>
								<input type="text" class="text-input" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="40" tabindex="3" />
							</p>
-->
						<?php endif; ?>
						<div class="comment-action">
							<?php do_action( 'comment_form', $post->ID ); ?>
						</div>
						
						<p class="form-textarea">
							<label for="comment"><?php _e('Comment', 'buddypress'); ?></label>
							<textarea name="comment" id="comment" cols="60" rows="10" tabindex="4"></textarea>
						</p>

						<?php do_action( 'bp_blog_comment_form' ) ?>

						<p class="form-submit">
							<input class="submit-comment button" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit', 'buddypress'); ?>" />
							<?php //comment_id_fields(); ?>
							<input type="hidden" value="0" id="comment_parent" name="comment_parent">
							<input type="hidden" name='comment_post_ID' id='comment_post_ID' value="1" />
						</p>



					</form>

					<?php do_action( 'bp_after_blog_comment_form' ) ?>

				<?php endif; ?>

			</div><!-- .comment-content -->
		    </div><!-- respond -->
		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer() ?>
