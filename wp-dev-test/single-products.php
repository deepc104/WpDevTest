<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="deep content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			// Include the single post content template.
			//get_template_part( 'template-parts/content', 'single' );
			/** Content from content-single.php **/ ?>
			  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
				<div class="product-mainimg">
					<?php twentysixteen_post_thumbnail(); ?>
					<?php $onsale = get_post_meta( get_the_ID() , 'onsale_field');
					   if($onsale[0] == "YES"){ ?>
						   <div class="onsalewrap1"></div>
					 <?php } ?>
				</div>	
				
				
				<?php $prod_dtls = get_post_meta( get_the_ID());?>
				

				<div class="entry-content">
				    <?php if($prod_dtls['desc_field'][0]<>""){?>
						<div class="fieldlabel">Description: </div><div class="fieldvalue"><?=$prod_dtls['desc_field'][0];?></div>
					<?php } ?>
					<?php if($prod_dtls['youtube_field'][0]<>""){?>
						<div class="fieldlabel">Youtube video: </div><div class="fieldvalue">
						<?php
							$yvideo = $prod_dtls['youtube_field'][0];
							$yvideo_new = "";
							if(strpos($yvideo, "rel=0") == false){
								$yvideo_new = (strpos($yvideo, "?") !== false) ? "&amp;rel=0" : "?rel=0";
							}
							$yvideo = $yvideo . $yvideo_new;
						?>
						 <iframe width="560" height="315" src="<?=$yvideo;?>" frameborder="0" allowfullscreen></iframe>
						</div>
					<?php } ?>
					<?php 
						$timages = get_children( array(
						'post_parent'    => get_the_ID(),
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'numberposts'    => 6
						) );
					if(count($timages) > 0){?>
						<div class="fieldlabel">Product image gallery: </div><div class="fieldvalue">
						<?php 
							foreach ( (array) $timages as $timage ) {
								//echo "<div class='product-img imagewrap'>".wp_get_attachment_image( $timage->ID, array(75, 75))."</div>";
								//echo "<div class='hiddenimagewrap'>".wp_get_attachment_image( $timage->ID, 'full')."</div>";
								$image_attributes = wp_get_attachment_image_src( $attachment_id = $timage->ID, 'full' );
								if ( $image_attributes ) { 
								    echo "<div class='product-img imagewrap'>";
									echo '<img src="'.$image_attributes[0].'" width="100" height="100%" alt="'.$image_attributes[0].'" />';
									echo "</div>";
								 }
						    }
						?>
						</div>
					<?php } ?>
					<?php if($prod_dtls['price_field'][0]<>""){?>
						<div class="fieldlabel">Price: </div><div class="fieldvalue <?=($prod_dtls['onsale_field'][0] == "YES")? "strikethrough-text": NULL;?>">Rs. <?=$prod_dtls['price_field'][0];?></div>
					<?php } ?>
					<?php if($prod_dtls['onsale_field'][0]<>"" && $prod_dtls['onsale_field'][0] == "YES"){?>
						<div class="fieldlabel">Sale price: </div><div class="fieldvalue">Rs. <?=$prod_dtls['saleprice_field'][0];?></div>
					<?php } ?>
					<?php 
					    $catlist = get_the_term_list(get_the_ID(), 'products_cat', '', ', ','');
						if($catlist<>""){?>
							<div class="fieldlabel">Category: </div><div class="fieldvalue"><?=$catlist;?></div>	
					<?php } ?>
					<?php
						wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
							'separator'   => '<span class="screen-reader-text">, </span>',
						) );

						if ( '' !== get_the_author_meta( 'description' ) ) {
							get_template_part( 'template-parts/biography' );
						}
					?>
					
				</div><!-- .entry-content -->
				
				<footer class="entry-footer">
					<?php twentysixteen_entry_meta(); ?>
					<?php
						edit_post_link(
							sprintf(
								/* translators: %s: Name of current post */
								__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
								get_the_title()
							),
							'<span class="edit-link">',
							'</span>'
						);
					?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## --> 
            <?php 
			/** Content from content-single.php ends here **/ 
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			if ( is_singular( 'attachment' ) ) {
				// Parent post navigation.
				the_post_navigation( array(
					'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteen' ),
				) );
			} elseif ( is_singular( 'post' ) ) {
				// Previous/next post navigation.
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				) );
			}

			// End of the loop.
		endwhile;
		?>
		
		<div class="related-products-wrap">
		     <h4>Related Products: </h4>
			  <?=showrelatedproducts($post);?>
	    </div><!-- .related-products-wrap -->

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->


<?php get_sidebar(); ?>
<?php get_footer(); ?>
