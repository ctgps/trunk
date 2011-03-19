<?php get_header(); ?>


	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ) ?>

		<div class="page" id="blog-archives">

			<h3 class="pagetitle"><?php printf( wp_title( false, false ) ); ?></h3>
			<p>请选择类别</p>
			<p>
			<a href="<?php echo get_category_link(9)?>" >公选课</a>
			<a href="<?php echo get_category_link(10)?>" >其他课程</a>
			</p>



		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer(); ?>
