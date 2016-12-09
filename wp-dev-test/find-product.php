<?php
/*
Template Name: Find Product
*/

$prodname ="";
if(isset($_POST['submitted'])) {
	if(trim($_POST['prodname']) === '') {
		$nameError = '<span class="err">Please enter product name/id.</span>';
		$hasError = true;
	} else {
	   $prodname = trim($_POST['prodname']);
	}
}

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			//get_template_part( 'template-parts/content', 'page' );
?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
               
               <form action="<?php the_permalink(); ?>" id="searchForm" method="post">
					<label for="prodname">Product Name/Id:</label>
					<input type="text" name="prodname" id="prodname" value="<?=($prodname <> "") ? $prodname : NULL;?>" />
					<?=($hasError) ? $nameError : NULL;?>
					
					<br><br>
					<button type="submit">Search</button>
					<input type="hidden" name="submitted" id="submitted" value="true" />
				</form>
				
				<div id="resultwrap"></div>
				
				
				
				<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
						get_the_title()
					),
					'<footer class="entry-footer"><span class="edit-link">',
					'</span></footer><!-- .entry-footer -->'
				);
				?>

			</article><!-- #post-## -->		
			
			
			
<?php 
			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->
<script>
( function( $ ) {
	var prodtext = "<h3>Result:</h3>";
	var availableTags = <?php echo get_the_product_dtls($prodname);?>;
	if(availableTags != 0){
		$.each( availableTags, function( key, value ) {
			prodtext = prodtext + "<div class='prod-wrap-"+key+"'>";
			if(value.title != "") prodtext =  prodtext + "<br><b>Product Title:</b> "+value.title;
			if(value.desc_field != "") prodtext = prodtext +"<br><b>Product Description:</b> "+value.desc_field;
			if(value.price_field != "") prodtext = prodtext +"<br><b>Product price_field:</b> "+value.price_field;
			if(value.onsale_field != "") prodtext = prodtext +"<br><b>Product onsale_field:</b> "+value.onsale_field;
			if(value.onsale_field == "YES") {
				if(value.saleprice_field != "") prodtext = prodtext +"<br><b>Product saleprice_field:</b> "+value.saleprice_field;
			}
			if(value.imgpath != "") prodtext = prodtext +"<br><b>Product Image: </b> <img src='"+value.imgpath+"' width='100' />";
			if(value.perlink != "")  prodtext = prodtext +"<br><a href='"+value.perlink+"'>Read More...</a>";
			prodtext = prodtext +"</div><hr>";		
		});
	}
	else {
		if(availableTags !='default'){
			prodtext = "<div class='err'>No product found.</div>";
		}
	}
	$( "#resultwrap" ).html(prodtext);
	//console.log(availableTags);
} )( jQuery );

</script>


<?php get_sidebar(); ?>
<?php get_footer(); ?>
