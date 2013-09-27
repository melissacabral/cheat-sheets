<?php 
/*
Plugin Name: Cheat Sheets
Plugin URI: http://wordpress.melissacabral.com/
Description:  displays useful Cheat Sheets in the toolbar when logged in as admin
Author: Melissa Cabral
Version: 0.1
Author URI: http://wordpress.melissacabral.com/
*/

/**
 * Display Cheat Sheets as Toolbar (Admin Bar) Item
 * @since ver 2.0
 */
add_action( 'admin_bar_menu', 'cheatsheets_toolbar_link', 999 );
function cheatsheets_toolbar_link( $wp_admin_bar ) {
	
		//$html = mmc_cheatsheets_generate_output();
		//PARENT ITEM
		$wp_admin_bar->add_node( array(
			'id'    => 'cheat-sheets',
			'title' => 'Cheat Sheets',
			'parent' => 'top-secondary',		
			'meta'  => array( 
				'class' => 'cheat-sheets',
				//'html' => $html,
				),
			));
		$wp_admin_bar->add_node( array(
				'id' => 'template-hierarchy',
				'title'=> 'Template Hierarchy Diagram',
				'parent' => 'cheat-sheets',
				'href' => 'http://wptutsplus.s3.amazonaws.com/090_WPCheatSheets/WP_CheatSheet_TemplateMap.jpg',
				'meta'  => array( 
					'target' => '_blank',
				//'html' => $html,
				),			) );

}
/**
 * function to generate output HTML
 * @since 1.0
 */
function mmc_cheatsheets_generate_output(){		
	//make sure user is logged in
	if (is_user_logged_in() && current_user_can( 'manage_options' )) {
		global $post;
		//begin Table Output
		ob_start();
		?>
			<div id="theme-helper-toolbar">
			<table>
				<tr>
					<th>Content Type:</th>
					<td><?php echo cheatsheets_content_type()?></td>
				</tr>
				<?php if(is_singular()){ ?>
				<tr>
					<th>Post ID:</th>
					<td><?php echo cheatsheets_post_id()?></td>
				</tr>
				<?php }	?>
				<tr>
					<th>True Condition(s):</th>
					<td><?php echo cheatsheets_true_conditions()?></td>
				</tr>
				<?php if( !is_404() && ! is_search() ){ ?>
				<tr>
					<th>Post Type:</th>
					<td><?php if ( get_post_type() ) { echo  get_post_type(); } ?></td>
				</tr>
				<?php } ?>
				<?php 
				if( is_category() ){ ?>
				<tr>
					<th>Taxonomy:</th>
					<td><?php 
					echo 'category'; 
					echo ' > ';
					single_cat_title(); ?>
					</td>
				</tr>
				<?php }				
				elseif( is_tax() ){?>
				<tr>
					<th>Taxonomy:</th>
					<td><?php 
					echo get_query_var( 'taxonomy' ); 
					echo ' > ';
					single_cat_title(); ?>
					</td>
				</tr>
				<?php }				
				elseif( is_tag() ){?>
				<tr>
					<th>Taxonomy:</th>
					<td><?php 
					echo 'tag'; 
					echo ' > ';
					single_cat_title(); ?>
					</td>
				</tr>
				<?php } //end if taxonomy/category ?>
				<?php
				if (isset($post->ID) && is_page() && get_post_meta($post->ID,'_wp_page_template',true) != 'default') {?>
				<tr>
					<th>Custom Template:</th>
					<td><?php echo get_post_meta($post->ID,'_wp_page_template',true) ?></td>
				</tr>
				<tr>
					<th>Order:</th>
					<td><?php echo $post->menu_order ?></td>
				</tr>
				<?php 
				}	?>
				<tr class="file-loaded">
					<th>File Loaded:</th>
					<td><?php echo cheatsheets_get_current_template() ?></td>
				</tr>
				<tr class="credits">
					<td colspan="2">Cheat Sheets by <a href="https://github.com/melissacabral/theme_helper">Melissa Cabral.</td>
				</tr>
				<tr class="credits usewith">
					<td colspan="2">Use with <a href="http://wptutsplus.s3.amazonaws.com/090_WPCheatSheets/WP_CheatSheet_TemplateMap.jpg">Hierarchy Diagram</a></td>
				</tr>
			</table>
		</div><!-- End Cheat Sheets-->
	<?php 	
	return ob_get_clean();			
	} //end is user logged in
}



/**
 * helper functions
 */
function cheatsheets_content_type(){
	global $post;
	$output = '';
	if (is_front_page()) { $output .= "Front Page"; }
	if (is_home()) { $output .= "Home (blog)"; }
	if (is_single()) { $output .= "Single Post "; }
	if (is_page() && !is_front_page()) { $output .= "Page "; }
	if (is_category()) { $output .= "Category "; }
	if (is_tag()) { $output .= "Tag "; }
	if (is_tax()) { $output .= "Taxonomy "; }
	if (is_author()) { $output .= "Author "; }		
	if (is_archive()) { $output .= "Archive "; }
	if (is_date()) { $output .= " - Date "; }
	if (is_year()) { $output .= " (year) "; }
	if (is_month()) { $output .= " (monthly) "; }
	if (is_day()) { $output .= " (daily) "; }
	if (is_time()) { $output .= " (time) "; }		
	if (is_search()) { $output .= "Search "; }
	if (is_404()) { $output .= "404 "; }
	if (is_paged()) { $output .= " (Paged) "; }
	return $output;
}

function cheatsheets_post_id(){
	global $post;
	if( isset($post) ){
		$post_id = $post->ID;
	}else{
		$post_id = 'no post defined';
	}
	return $post_id;
}
function cheatsheets_true_conditions(){
	global $post;
	$conditions = array();	
	$output = '';
	$count = 0;
	if (is_front_page()) { $conditions[] = "is_front_page()"; }
	if (is_home()) { $conditions[] = "is_home()"; }
	if (is_attachment() ){ $conditions[] = "is_attachment()"; }
	if (is_single()) { $conditions[] = "is_single()"; }
	if (is_page()) { $conditions[] = "is_page()"; }
	if (is_singular()) { $conditions[] = "is_singular() "; }
	if (is_category()) { $conditions[] = "is_category()"; }
	if (is_tag()) { $conditions[] = "is_tag()"; }
	if (is_tax()) { $conditions[] = "is_tax()"; }
	if (is_author()) { $conditions[] = "is_author()"; }
	if (is_post_type_archive()){ $conditions[] = "is_post_type_archive()"; }
	if (is_date()) { $conditions[] = "is_date()"; }
	if (is_year()) { $conditions[] = "is_year()"; }
	if (is_month()) { $conditions[] = " is_month()"; }
	if (is_day()) { $conditions[] = " is_day()"; }
	if (is_time()) { $conditions[] = " is_time()"; }	
	if (is_archive()) { $conditions[] = " is_archive() "; }	
	if (is_search()) { $conditions[] = "is_search() "; }
	if (is_404()) { $conditions[] = "is_404() "; }
	if (is_paged()) { $conditions[] = "is_paged() "; }

	foreach($conditions as $condition){
		if($count == 0)
			$output.= '<span class="first condition">'.$condition.'</span>';
		else
			$output.= '<span class="condition">, '.$condition.'</span>';	
		$count ++;
	}	
	return $output;
}


function cheatsheets_get_current_template(  ) {
	if( !isset( $GLOBALS['current_theme_template'] ) ){
		return false;
	}
	return $GLOBALS['current_theme_template'];
}

function cheatsheets_currenturl() {
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/**
 * Enqueue the stylesheet
 * @since ver 2.0
 */
add_action('wp_enqueue_scripts', 'cheatsheets_enqueue_stylesheet');
function cheatsheets_enqueue_stylesheet(){
	$src = plugins_url( 'rad-helper.css', __FILE__ );
	wp_register_style( 'cheatsheet-style', $src, '', '', 'screen' );
	wp_enqueue_style( 'cheatsheet-style');
}