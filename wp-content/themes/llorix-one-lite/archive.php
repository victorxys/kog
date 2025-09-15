<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package llorix-one-lite
 */

get_header(); ?>
	</div>
	<!-- /END COLOR OVER IMAGE -->
</header>
<!-- /END HOME / HEADER  -->

<?php
	// echo "<pre>";
	// 	// $category = get_the_category();//默认获取当前所属分类
	// 	// // echo $category[0]->category_parent; //若不是子分类目录 则值为0;
	// 	// var_dump($category);
		
	// 	$category[0]->category_parent; //若不是子分类目录 则值为0;
	// 	wp_list_categories("child_of=".$category[0]->category_parent."&depth=0&hide_empty=0&title_li=");
	// 	//如果titleli=sssss,则默认返回的html有title是“sssss”
	// 	var_dump(get_categories());
	// 	exit;
	
	// 获取当前页面的所有子分类（如果有的话）
	$category = get_the_category();//默认获取当前所属分类
	if(!empty($category)){
			$child_cat =  get_child_categories($category[0]->category_parent);
	}
	// echo "<pre>";
	// var_dump ($child_cat);
	// exit;

	$llorix_one_lite_blog_header_image    = get_theme_mod( 'llorix_one_lite_blog_header_image', llorix_one_lite_get_file( '/images/background-images/background-blog.jpg' ) );
	$llorix_one_lite_blog_header_title    = get_theme_mod( 'llorix_one_lite_blog_header_title', apply_filters( 'llorix_one_lite_blog_header_title_default_filter', 'This Theme Supports a Custom FrontPage' ) );
	$llorix_one_lite_blog_header_subtitle = get_theme_mod( 'llorix_one_lite_blog_header_subtitle' );

	if ( ! empty( $llorix_one_lite_blog_header_image ) || ! empty( $llorix_one_lite_blog_header_title ) || ! empty( $llorix_one_lite_blog_header_subtitle ) ) :

	if ( ! empty( $llorix_one_lite_blog_header_image ) ) :
		echo '<div class="archive-top" style="background-image: url(' . wizhi_banner_image() . ');">';
		else :
			echo '<div class="archive-top">';
		endif;
		echo '<div class="section-overlay-layer">';
		echo '<div class="container">';

		// echo "<pre>";
		$a = get_the_archive_title();
		// // echo $a;
		// // echo strpos($a,"：");
		$header_title = substr($a, strpos($a, "：")+3);
		// exit;
		


		if ( ! empty( $llorix_one_lite_blog_header_title ) ) :
			echo '<p class="archive-top-big-title">' . $header_title . '</p>';
			echo '<p class="colored-line"></p>';
			endif;

		if ( ! empty( $llorix_one_lite_blog_header_subtitle ) ) :
			echo '<p class="archive-top-text">' . $header_title . '</p>';
			endif;

		echo '</div>';
		echo '</div>';
		echo '</div>';

	endif;

?>
<style type="text/css">
        .child-cat ul {
            display: flex;
            /*flex-direction: row;*/
            /*flex-wrap: nowrap;*/
            flex-flow: row nowrap;
            justify-content: center;
            margin: 0;
        }
        .child-cat ul li {
        	font-size: 20px;
            list-style: none;
            /*border: 2px solid #161EE8FF;*/
            text-align: center;
            line-height: 30px;
            padding: 10px;
            height: 30px;
            width: 100px;
            margin: 0 10px;
            /*display: inline;*/
        }
    </style>

<?php if(isset($child_cat)):?>
<div class="child-cat" style="float: left;width: 100%">
	<ul>
	<?php foreach($child_cat as $key=>$val):?>
	
		<!-- <li style="display:inline"><a href="/?cat=<?php _e($val['cat_id'])?>"><?php _e($val['cat_name'])?></a></li> -->
		<li><a href="/?cat=<?php _e($val['cat_id'])?>"><?php _e($val['cat_name'])?></a>1</li>
	
	<?php endforeach?>
	</ul>
</div>
<?php endif?>
<div role="main" id="content" class="content-wrap">
	<div class="container">

		<div id="primary" class="content-area col-md-8 post-list">
			<?php
			echo '<main ';
			if ( have_posts() ) {
				echo ' itemscope itemtype="http://schema.org/Blog" ';
			}
			echo ' id="main" class="site-main" role="main">';

			if ( have_posts() ) {

				// echo '<header class="page-header">';
				// 	// the_archive_title( '<h1 class="page-title">', '</h1>' );
				// 	// the_archive_description( '<div class="taxonomy-description">', '</div>' );
				// echo '</header>';
				while ( have_posts() ) {
					the_post();
					/**
					 *  Include the Post-Format-specific template for the content.
					 *  If you want to override this in a child theme, then include a file
					 *  called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				}
				the_posts_navigation();

			} else {
				get_template_part( 'content', 'none' );
			}
			?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php //get_sidebar(); ?>

	</div>
</div><!-- .content-wrap -->

<?php get_footer(); ?>
