<?php
//code to set style.css
function wp_dev_test_enqueue_styles() {
	global $post;
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
	
	if($post->post_type == "products"){
		wp_enqueue_script( 'script-name', get_stylesheet_directory_uri() . '/js/custom_js.js', array(), '1.0.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_dev_test_enqueue_styles' );


//Code to set favicon
function wp_dev_test_modify_head() {
    echo '<link rel="shortcut icon" type="image/x-icon" href="'.get_stylesheet_directory_uri().'/favicon.png" />' . "\n";
	
	//Code to change the address bar color for mobile
	echo '<meta name="theme-color" content="pink">'; //Chrome, Firefox OS, Opera
	echo '<meta name="msapplication-navbutton-color" content="pink">'; //Windows Phone **
	echo '<meta name="apple-mobile-web-app-capable" content="yes">'; //iOS Safari
	echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';
}
add_action( 'wp_head', 'wp_dev_test_modify_head' );

//code to hide admin bar
function wp_dev_test_remove_admin_bar() {
	if (current_user_can('editor') && !is_admin()) {
	 // show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'wp_dev_test_remove_admin_bar');

//code to revoke access to dashboard
function wp_dev_test_revoke_admin_access() {
	if (current_user_can('editor')) {
	  wp_redirect( home_url() ); exit;
	}
}
add_action('admin_init', 'wp_dev_test_revoke_admin_access');

//code to create new content type
function wp_dev_test_create_posttype() {

	register_post_type( 'products',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Products' ),
				'singular_name' => __( 'Product' )
			),
			'public'      => true,
			'has_archive' => true,
			'rewrite'     => array('slug' => 'products'),
			'supports'    => array('title', 'thumbnail', 'category'),
		)
	);
}
// Hooking up the function to theme setup
add_action( 'init', 'wp_dev_test_create_posttype' );


//Code to create custom taxanomy
add_action( 'init', 'wp_dev_test_create_product_taxonomies', 0 );

function wp_dev_test_create_product_taxonomies() {

	$labels = array(
		'name'              => _x( 'Product Categories', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Product Category', 'taxonomy singular name', 'textdomain' ),
		'edit_item'         => __( 'Edit Product', 'textdomain' ),
		'update_item'       => __( 'Update Product', 'textdomain' ),
		'add_new_item'      => __( 'Add New Product Category', 'textdomain' ),
		'new_item_name'     => __( 'New Product Name', 'textdomain' ),
		'menu_name'         => __( 'Product Categories', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		//'show_ui'           => true,
		//'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'products_cat' ),
	 //	'taxonomies'          => array( 'products_cat' ),

	);
	register_taxonomy( 'products_cat', 'products', $args );
}

//Code to add fields under products type 
function wp_dev_test_product_add_meta_box() {
   	add_meta_box(
			'wp_dev_custom_fields',
			'Product Info',
			'wp_dev_test_meta_box_callback',
			'products',
			'normal'
	);
}
add_action( 'add_meta_boxes', 'wp_dev_test_product_add_meta_box' );

function wp_dev_test_meta_box_callback($post){
	//wp_nonce_field( basename( _FILE_ ), 'products_nounce' );
	$outline = "";
	?>
	<!-- #Description -->
	<div class="meta-row"><div class="meta-th"><label for="desc_field" style="width:150px;">Description:</label></div>
    <div class="meta-td">
	<?php 
		$desc_field = get_post_meta( $post->ID, 'desc_field', true );
		$settings = array('textarea_rows'=> 8, 'media_buttons' => false);
		wp_editor($desc_field, 'desc_field', $settings);
	?>	
	</div></div>
	
	<!-- #Price -->
    <div class="meta-row"><div class="meta-th"><label for="price_field" style="width:150px; display:inline-block;">Price: </label></div>
	<?php 
	    $price_field = get_post_meta( $post->ID, 'price_field', true ); 
	?>
	<div class="meta-td"><input type="text" name="price_field" id="price_field" class="price_field" value="<?=$price_field;?>" /></div></div>
	
	
	<!-- #Is on sale? -->
	<div class="meta-row"><div class="meta-th"><label>Is On Sale?:</label></div>
	<?php 
	    $onsale_field = get_post_meta( $post->ID, 'onsale_field', true ); 
	?>
	<div class="meta-td">
	  <input type="radio" name="onsale_field" id="onsale1" value="YES" <?=($onsale_field == "YES")? " checked ": NULL;?> /><label for="onsale1" style="width:150px; display:inline-block;">Yes</label>
	  <input type="radio" name="onsale_field" id="onsale2" value="NO" <?=($onsale_field == "NO")? " checked ": NULL;?> /><label for="onsale2" style="width:150px; display:inline-block;">No</label>
	 </div></div>
	 
	 <!-- #Sale price -->
	 <div class="meta-row"><div class="meta-th"><label for="saleprice_field" style="width:150px; display:inline-block;">Sale Price: </label></div>
	<?php 
	    $saleprice_field = get_post_meta( $post->ID, 'saleprice_field', true ); 
	?>
	<div class="meta-td"><input type="text" name="saleprice_field" id="saleprice_field" class="saleprice_field" value="<?=$saleprice_field;?>" /></div></div>
	
	<!-- #Youtube video -->
	<div class="meta-row"><div class="meta-th"><label for="youtube_field" style="width:150px; display:inline-block;">Youtube Video: </label></div>
	<?php 
	    $youtube_field = get_post_meta( $post->ID, 'youtube_field', true ); 
	?>
	<div class="meta-td"><input type="text" name="youtube_field" id="youtube_field" class="youtube_field" value="<?=$youtube_field;?>" />
	 <i>Link format: https://www.youtube.com/embed/VIDEOCODE</i>
	</div></div>
	
	
	<!-- #Gallery Images -->
	<div class="meta-row"><div class="meta-th"><label for="youtube_field" style="width:150px; display:inline-block;">Gallery Images(Max 6 imgs): </label></div>
	<?php 
	      $images = get_children( array(
			'post_parent'    => get_the_ID(),
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'numberposts'    => 6
			) );
			foreach ( (array) $images as $image ) {
				echo wp_get_attachment_image( $image->ID,array(75, 75));
			}
	?>
	<div class="meta-td"><input type="file" name="my_file_upload[]"  multiple="multiple" /></div></div>
	
	
	<?php

}
 
function wpdocs_save_meta_box( $post_id ) {
    $post_type = get_post_type($post_id);

    // If this isn't a 'book' post, don't update it.
    if ( $post_type != 'products' ) return;

    // - Update the post's metadata.

    if ( isset( $_POST['desc_field'] ) ) {
        update_post_meta( $post_id, 'desc_field', sanitize_text_field( $_POST['desc_field'] ) );
    }
    if ( isset( $_POST['price_field'] ) ) {
        update_post_meta( $post_id, 'price_field', sanitize_text_field( $_POST['price_field'] ) );
    }
	if ( isset( $_POST['onsale_field'] ) ) {
        update_post_meta( $post_id, 'onsale_field', sanitize_text_field( $_POST['onsale_field'] ) );
    }
	if ( isset( $_POST['saleprice_field'] ) ) {
        update_post_meta( $post_id, 'saleprice_field', sanitize_text_field( $_POST['saleprice_field'] ) );
    }
	if ( isset( $_POST['youtube_field'] ) ) {
        update_post_meta( $post_id, 'youtube_field', sanitize_text_field( $_POST['youtube_field'] ) );
    }
	
	
 	if ( $_FILES ) {
     $files = $_FILES["my_file_upload"];
     //echo "<pre>";
     //print_r($files);	 exit;
	
    foreach ($files['name'] as $key => $value) {  
            if ($files['name'][$key]) { 
                $file = array( 
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key], 
                    'tmp_name' => $files['tmp_name'][$key], 
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                ); 
                $_FILES = array ("my_file_upload" => $file); 
                foreach ($_FILES as $file => $array) {              
                    $newupload[] = my_handle_attachment($file,$post_id); 
               }
            } 
        } 
    }
}
add_action( 'save_post', 'wpdocs_save_meta_box' );

function my_handle_attachment($file_handler,$post_id,$set_thu=false) {
// check to make sure its a successful upload
if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

require_once(ABSPATH . "wp-admin" . '/includes/image.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');
require_once(ABSPATH . "wp-admin" . '/includes/media.php');

$attach_id = media_handle_upload( $file_handler, $post_id );
	if ( is_numeric( $attach_id ) ) {
		update_post_meta( $post_id, 'my_file_upload', $attach_id );
	}
}



/*Code to style product admin page*/
function wp_dev_test_admin_scripts(){
	global $pagenow, $typenow;
	
	if(($pagenow == "post.php" || $pagenow == "post-new.php") && $typenow == "products"){
		wp_enqueue_style( 'custom_wp_admin_css', get_stylesheet_directory_uri() . '/css/product_admin.css' );
	}
}
 add_action( 'admin_enqueue_scripts', 'wp_dev_test_admin_scripts' );



function post_edit_form_tag( ) {
	global $typenow;
	if($typenow == "products") {
		echo ' enctype="multipart/form-data"';
	}
}
add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );


//Code to mange admin page of custom type
//used manage_edit-{$post_type}_columns 
function wp_dev_test_edit_products_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'products_cat' => __( 'Category' ),
		'main_image' => __( 'Main Image' ),
		'date' => __( 'Date' ),
	);

	return $columns;
}

add_filter( 'manage_edit-products_columns', 'wp_dev_test_edit_products_columns' ) ;


//Code to show content on 
function wp_dev_test_manage_products_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
			case "products_cat":
				echo get_the_term_list($post->ID, 'products_cat', '', ', ','');
				break;
				
			case "main_image":
			    echo the_post_thumbnail('thumbnail', array('class' => 'alignleft'));
                break;			

		default :
			break;
	}
}
add_action( 'manage_products_posts_custom_column', 'wp_dev_test_manage_products_columns', 10, 2 );

function wp_dev_test_manage_products_sortable_columns( $columns ) {

	$columns['products_cat'] = 'products_cat';

	return $columns;
}
add_filter( 'manage_edit-products_sortable_columns', 'wp_dev_test_manage_products_sortable_columns' );




//Code to get all product posts on home page
function my_get_posts( $query ) {

	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', array( 'products' ) );

	return $query;
}
add_filter( 'pre_get_posts', 'my_get_posts' );


function showrelatedproducts($post){
	$custom_taxterms = wp_get_object_terms( $post->ID, 'products_cat', array('fields' => 'ids') );
	$args = array(
		'post_type' => 'products',
		'post_status' => 'publish',
		'posts_per_page' => 2,
		'orderby' => 'rand',
		'tax_query' => array(
			array(
				'taxonomy' => 'products_cat',
				'field' => 'id',
				'terms' => $custom_taxterms
			)
		),
		'post__not_in' => array ($post->ID),
	);
	$related_items = new WP_Query( $args );
	// loop over query
	if ($related_items->have_posts()){
		echo '<ul class="related-prod-list">';
			while ( $related_items->have_posts() ){ $related_items->the_post(); 
			?>
				<li>
				   <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					   <div class="rel-prod-img"><?php echo the_post_thumbnail('thumbnail'); ?></div>
					   <div class="rel-prod-title"><?php the_title(); ?></div>
				   </a>
				</li>
			<?php
			}
		echo '</ul>';
	}
	// Reset Post Data
	wp_reset_postdata();
}

function product_shortcode_func( $attr ) {
	$onsale = get_post_meta( $attr['id'], 'onsale_field', true );
    $content ="";
	$content .='<div class="shortcode-product-wrap" style="background: '.$attr['bgcolor'].';">';
    get_post($attr['id']);
	$content .='<a href="'.get_permalink($attr['id']).'">
		   <div class="rel-prod-img">'. get_the_post_thumbnail($attr['id'], 'thumbnail' ).'</div>';
	$content .='<div class="rel-prod-title"><b>'.get_the_title($attr['id']).'</b></div>
		</a><div><b>Price: </b><span class="';
		if($onsale == "YES"){ $content .='strikethrough-text'; }
        $content .='">Rs. '.get_post_meta( $attr['id'], 'price_field', true ).'</span></div>';
		if($onsale == "YES"){
		     $content .='<div><b>Sale Price: </b>Rs. '.get_post_meta( $attr['id'], 'saleprice_field', true ).'</div>';
		}
	wp_reset_postdata();
	return apply_filters( 'product_shortcode_alter', $content );
}
add_shortcode( 'product', 'product_shortcode_func' );

//CODE to enable shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );

//Custom filtr to modify output of shortcode
function product_modify_html($html) {
    return '<div class="extra_div" style="border: 1px solid #999;">' . $html . '</div>';
}
add_filter( 'product_shortcode_alter', 'product_modify_html' );


//Code to demo JSON API
function get_the_product_dtls($prodid){
	$prod_arr = array();
	if($prodid <> ""){
		if(!is_numeric($prodid)){
			$proid = "";
			$args = array(
				'post_type' => 'products',
				'post_status' => 'publish',
				'orderby' => 'ID',
				'order'   => 'DESC',
				's' => $prodid,
			);
			$related_items = new WP_Query( $args );
			//echo $related_items->request;
			//print_r($related_items);
			if(!empty($related_items->posts)){
				foreach($related_items->posts as $post){
					$prod_arr[] = get_the_prod($post->ID);
				}
				return json_encode($prod_arr);
			}else{ return 0; }
		}
	   	else{
			$args = array(
				'post_type' => 'products',
				'post_status' => 'publish',
				'p' => $prodid,
			);
			$related_items = new WP_Query( $args );
		    //echo $related_items->request;
			//print_r($related_items);
			if(!empty($related_items->posts)){
			  $prod_arr[] = get_the_prod($related_items->posts[0]->ID);
			  return json_encode($prod_arr);
			}else{ return 0; }
		}
	}else{ return 'default'; }
}

function get_the_prod($prodid){
	    $prodtitle = get_the_title($prodid);
		$imgpath = get_the_post_thumbnail_url($prodid);
		$myvals = get_post_meta($prodid);
		$link = get_the_permalink($prodid);
		$prod_arr = array();
		if(!empty($myvals)){
			foreach($myvals as $key=>$val){
				$prod_arr[$key] = $val[0];
			}
			$prod_arr['title'] = $prodtitle;
			$prod_arr['imgpath'] = $imgpath;
			$prod_arr['perlink'] = $link;
		}
	  return $prod_arr;
}

?>