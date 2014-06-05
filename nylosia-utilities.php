<?php
/**
 * @package Nylosia_Utilities
 * @version 1.0
 */
/*
Plugin Name: Nylosia Utilities
Description: nylosia plugin + widgets
Author: CGF
Version: 1.0
*/

include 'php/functions.php';
include 'php/widgets.php';

//attivazione plugin
register_activation_hook( __FILE__, 'nylosia_utilities_activation' );
function nylosia_utilities_activation() {
	//create table wp_nylosiarating_termmeta
    global $wpdb;

	$type = 'nylosiarating_term';
    $table_name = $wpdb->prefix . $type . 'meta';
 
    if (!empty ($wpdb->charset))
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    if (!empty ($wpdb->collate))
        $charset_collate .= " COLLATE {$wpdb->collate}";
             
      $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
        meta_id bigint(20) NOT NULL AUTO_INCREMENT,
        {$type}_id bigint(20) NOT NULL default 0,
     
        meta_key varchar(255) DEFAULT NULL,
        meta_value longtext DEFAULT NULL,
                 
        UNIQUE KEY meta_id (meta_id)
    ) {$charset_collate};";
     
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_deactivation_hook( __FILE__, 'nylosia_utilities_deactivation' );
function nylosia_utilities_deactivation() {
	//TODO drop table
}

//pagina menu del plugin
add_action( 'admin_menu', 'nylosia_plugin_menu' );
function nylosia_plugin_menu() {
	add_options_page( 'Nylosia Utilities Options', 'Nylosia Utilities', 'manage_options', 'nylosia-menu', 'nylosia_plugin_options' );
}

function nylosia_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$nylosia_fb_appid = get_option('nylosia_fb_appid');
	$nylosia_fb_lang = get_option('nylosia_fb_lang');
	$nylosia_fb_picture = get_option('nylosia_fb_picture');
	$nylosia_add_og = get_option('nylosia_add_og');
	$nylosia_tw_user = get_option('nylosia_tw_user');
	$nylosia_posts_order = get_option('nylosia_posts_order');
	$nylosia_posts_order_meta = get_option('nylosia_posts_order_meta');
	$nylosia_rating = get_option('nylosia_rating');

    ?>
    <div class="wrap">
        <form action="options.php" method="post" name="options">
			<h2>Nylosia Utilities Options</h2>
			<?php wp_nonce_field('update-options'); ?>

			<h3>Social Widget</h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="nylosia_fb_appid">Facebook AppId</label></th>
						<td><input type="text" class="regular-text" id="nylosia_fb_appid" name="nylosia_fb_appid" value="<?php echo esc_attr($nylosia_fb_appid) ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="nylosia_fb_lang">Facebook Lingua</label></th>
						<td><input type="text" class="regular-text" id="nylosia_fb_lang" name="nylosia_fb_lang" value="<?php echo esc_attr($nylosia_fb_lang) ?>">
							<p class="description">Nel formato xx_YY</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="nylosia_fb_picture">Facebook Immagine Feed</label></th>
						<td><input type="text" class="regular-text" id="nylosia_fb_picture" name="nylosia_fb_picture" value="<?php echo esc_attr($nylosia_fb_picture) ?>">
							<p class="description">Indicare l'id dell'immagine o l'url da mostrare nella finestra del feed se non &egrave; stata impostata <strong>l'immagine in evidenza</strong> nel post.</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Open Graph meta tags</th>
						<td><label for="nylosia_add_og">
							<input type="checkbox" id="nylosia_add_og" name="nylosia_add_og" <?php echo $nylosia_add_og ? 'checked' : '' ?>>
							Aggiungi
							</label>
							<p class="description">Possono essere utilizzati da Facebook per arricchire le informazioni di condivisione</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="nylosia_tw_user">Twitter username</label></th>
						<td><input type="text" class="regular-text" id="nylosia_tw_user" name="nylosia_tw_user" value="<?php echo esc_attr($nylosia_tw_user) ?>">
							<p class="description">@username verr&agrave; aggiunto al post di Twitter</p>
						</td>
					</tr>
				</tbody>
			</table>

			<h3>Ordinamento post in riepilogo per categoria</h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Ordinamento</th>
						<td><label for="nylosia_posts_order">
							<input type="checkbox" id="nylosia_posts_order" name="nylosia_posts_order" <?php echo $nylosia_posts_order ? 'checked' : '' ?>>
							Abilita
							</label>
							<p class="description">Specificare un <strong>campo personalizzato</strong> per cui ordinare.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="nylosia_posts_order_meta">Campo personalizzato</label></th>
						<td><input type="text" class="regular-text" id="nylosia_posts_order_meta" name="nylosia_posts_order_meta" value="<?php echo esc_attr($nylosia_posts_order_meta) ?>">
							<p class="description">Campo personalizzato per cui ordinare i post.</p>
						</td>
					</tr>
				</tbody>
			</table>

			<h3>Rating</h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Raccolta informazioni</th>
						<td><label for="nylosia_rating">
							<input type="checkbox" id="nylosia_rating" name="nylosia_rating" <?php echo $nylosia_rating ? 'checked' : '' ?>>
							Abilita
							</label>
							<p class="description">Individua univocamente l'utente, in forma anonima, per la raccolta di informazioni sul rating degli articoli.</p>
						</td>
					</tr>
				</tbody>
			</table>			

			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="nylosia_fb_appid,nylosia_fb_lang,nylosia_fb_picture,nylosia_add_og,nylosia_tw_user,nylosia_posts_order,nylosia_posts_order_meta,nylosia_rating" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /> 
			</p>
        </form>
    </div>
    <?php   

} //end nylosia_plugin_options

//aggiungo i meta tag per il widget social
add_action('wp_head', 'nylosia_social_add_meta');
function nylosia_social_add_meta() {
	if ( get_option('nylosia_add_og') ) {
		//come immagine viene usata quella in evidenza se impostata nel post, altrimenti quella impostata nelle opzioni 
		$fbpicture = get_post_thumbnail_id() ? get_post_thumbnail_id() :  get_option('nylosia_fb_picture');
		if( is_numeric($fbpicture) ) {
			$fbpicutre_attr = wp_get_attachment_image_src( $fbpicture );
			$fbpicutre_url = $fbpicutre_attr[0];
		} else {
			$fbpicutre_url = $fbpicture;
		}		
	?>
	  	<meta property="fb:app_id" content="<?php echo get_option('nylosia_fb_appid') ?>">
	  	<meta property="og:site_name" content="<?php echo get_bloginfo('name') ?>">
	  	<meta property="og:title" content="<?php echo (get_the_title() ? get_the_title() : get_bloginfo('name')) ?>">
	  	<meta property="og:description" content="<?php echo get_bloginfo('description') ?>">
	  	<meta property="og:image" content="<?php echo $fbpicutre_url ?>">
	  	<meta property="og:url" content="<?php echo get_current_URL() ?>">
	<?php

	  	// <meta property="og:type" content="article">
	  	// <meta property="article:publisher" content="https://www.facebook.com/nylosia">

	}
} //end nylosia_social_add_meta

//funzione per aggiungere il css
add_action( 'wp_enqueue_scripts', 'nylosia_utilities_css' );
function nylosia_utilities_css() {
    wp_enqueue_style( 'nylosia_utilities_css', plugins_url('/style.css', __FILE__) );
}

//ordina i post nei riepiloghi in base al campo personalizzato priority
//	se dovesse fare casini con la paginazione vedere 
//  http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
add_filter('pre_get_posts', 'nylosia_pre_get_posts' );
function nylosia_pre_get_posts($wp_query) {
    if (get_option('nylosia_posts_order') &&  $wp_query->is_main_query() && (is_archive() || is_category()))
    {
        $wp_query->set( 'orderby', 'meta_value_num' );
        $wp_query->set( 'meta_key', get_option('nylosia_posts_order_meta') );
        $wp_query->set( 'order', 'ASC' );
		return $wp_query;
    }
}

//imposta cookie con id univoco per utente 
add_action( 'init', 'nylosia_cookies' );
function nylosia_cookies() {
	if (get_option('nylosia_rating')) {
		$cookie_name = 'nylosia_rating_uid';
		//se non esiste lo creo, se no aggiorno la scadenza
	    if (!isset($_COOKIE[$cookie_name])) {
	        setcookie($cookie_name, get_unique_id(), time() + 5184000); //60gg
	    } else {
	    	setcookie($cookie_name, $_COOKIE[$cookie_name], time() + 5184000); //60gg
	    }
    }
}

?>