<?php 
//only used in course guideline, must placed before get_header() 
//because needed to insert some css
$crfp = new CommentRatingFieldPlugin(); // Invoke class

get_header(); 
?>
<script type="text/javascript" language="JavaScript">


jQuery(document).ready(function($) {

	function reset_row_color()
	{
	$(".parent_row:even").css("background-color","#CCC");
	$(".parent_row:odd").css("background-color","#999");
	}


	$('#spread').click(
	                   function()
	                    {
	                        $('.children_row').show();
	                    } );

	$('#roll_up').click(
	                   function()
	                    {
	                        $('.children_row').hide();
	                    } );

	$('#txt_search').keyup(
	                   function()
	                    {
	                        if ( $(this).val()!="" )
	                        {
	                            $('#main_table tbody tr').hide().filter(".parent_row:contains('"+$(this).val()+"')").show();
	                        }
	                        else
	                        {
	                          $('#main_table tbody tr').hide()
	                          .filter(".parent_row").show();
	                        }

	                    } );




	$("#roll_up").triggerHandler("click");

	$('#spread').triggerHandler("click");
	
	$(".parent_row").hover(
	function()
	{
	    $(this).css("background-color","#cc4a0a");
	},
	function()
	{
	    reset_row_color();
	});

	$(".parent_row").toggle(
	function()
	{
	    $(".children_row[name="+$(this).attr('id')+"]").show(); 
	},
	function()
	{
	    $(".children_row[name="+$(this).attr('id')+"]").hide();
	}
	);


	$(".open_comment_form").toggle(
	function(){
		$(".children_comment_row[name="+$(this).attr('name')+"]").show();
		$(this).html("收起评价");
	},
	function(){
		$(".children_comment_row[name="+$(this).attr('name')+"]").hide();
		$(this).html("我要评价");
	});

	$(".children_comment_row").hide();

	
	reset_row_color();

	
 

});

</script>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ) ?>

		<div class="page" id="blog-archives">

			<h3 class="pagetitle"><?php printf( wp_title( false, false ) ); ?></h3>

<div id="top_menu" style="margin:10px 5px">
<input type="button" value="展开所有评价" id="spread">
<input type="button" value="收起所有评价" id="roll_up">
<form id="search" style="display:inline; float:right;">
<label for="txt_search">查找：</label>
<input type="text" maxlength="40" size="20" id="txt_search">
</form>
</div>

			<?php if ( have_posts() ) : _?>
<!--  
				<div class="navigation">

					<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
					<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>

				</div>
-->

                <table width="80%" id="main_table">
                <thead><tr>
                    <th >课程名称</th>
                    <th width="200">任课老师</th>
                    <th width="200" colspan="2">已有评价</th> 
                    <th width="100">我要评价</th>
                </tr></thead>
                
                <tbody>







				<?php while (have_posts()) : the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ) ?>
                    <tr id="<?php the_ID(); ?>" class="parent_row" style="background-color: rgb(204, 204, 204);">
                        <td><?php the_title(); ?></td>
                        <td><?php $teacher = get_post_custom_values("任课老师"); echo $teacher[0] ;?></td>
                        <?php $comments=get_comments( array( "post_id" => get_the_ID(), "orderby" => "comment_date"));
                        $numComments = 0;
                        $numScore    = 0;
        				foreach ( (array)$comments as $comment ){
        					if ( 'comment' == get_comment_type() ) {
        					    ++$numComments;
        					    $numScore += get_comment_meta($comment->comment_ID, "crfp-rating", true);
        					}
        				}
                        $aveScore=$numComments ?  $numScore/$numComments : 0;						
                        ?>
                        <td><div class="crfp-rating crfp-rating-<?php echo  (int) ($aveScore+0.5);?>" "></div> </td>
                        <td>[平均:<?php echo number_format( $aveScore, 2);?>分|<?php echo $numComments?>人次]</td>
                        <td><button type="button" name="<?php the_ID(); ?>" class="open_comment_form">我要评价</button></td>
                    </tr>
				 

                    <?php
                   //$comments=get_comments( array( "post_id" => get_the_ID(), "orderby" => "comment_date")); 
                    foreach ( $comments as $comment):	
                     
                    $GLOBALS['comment'] = $comment; 			        
                     ?>
                    <tr name="<?php echo $comment->comment_post_ID; ?>" class="children_row" >
                    <td colspan="4">
                    <fieldset>
                     
                    <legend>[<a href="<?php echo get_comment_author_url() ?>" rel="nofollow">
                    <?php  echo get_comment_author_link(); ?></a>]说：</legend>
                    <div class="cmt"><?php comment_text();?></div>
                    <div class="comment-options">
				   	   <?php echo comment_reply_link( array('depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ?>
				       <?php edit_comment_link( __( 'Edit' ),'','' ); ?>
					</div>
                    </fieldset></td>
                    </tr>
                    <?php endforeach;?>		

					<tr name="<?php the_ID(); ?>" class="children_comment_row" >
                    <td colspan="4">
                    
        			<div class="comment-content">
        
        				<h3 id="reply" class="comments-header">
        					我来吼两句
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
        					<input type="hidden" name="redirect_to" value="<?php echo get_category_link(isset($override_cat_id)? $override_cat_id : 11); ?>" />
        					
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
        							<?php comment_id_fields(); ?>
        						</p>
        
        
        
        					</form>
        
        					<?php do_action( 'bp_after_blog_comment_form' ) ?>
        
        				<?php endif; ?>
        
        			</div><!-- .comment-content -->
					</td>
					</tr>


					<?php do_action( 'bp_after_blog_post' ) ?>

				<?php endwhile; ?>
                </tbody>
                </table>
                
				<div class="navigation">

					<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
					<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>

				</div>

			<?php else : ?>

				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ) ?></h2>
				<?php locate_template( array( 'searchform.php' ), true ) ?>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer(); ?>
