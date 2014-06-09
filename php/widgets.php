<?php

/** 
 * NYLOSIA WIDGETS
 */

// register widgets
add_action( 'widgets_init', 'register_nylosia_widgets' );
function register_nylosia_widgets() {
    register_widget( 'NylosiaSocialWidget' );
    register_widget( 'NylosiaRatingWidget' );
}

/**
 *
 */
class NylosiaSocialWidget extends WP_Widget
{

	function NylosiaSocialWidget() {
    	$widget_ops = array('classname' => 'NylosiaSocialWidget', 'description' => 'Add nylosia social widget' );
		$control_ops = array('width' => 350, 'height' => 350);
		$this->WP_Widget('NylosiaSocialWidget', 'Nylosia Social Widget', $widget_ops, $control_ops); //base id, name, args
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'social_title' => 'Condividi', 'show_fb' => 1, 'show_tw' => 1, 'show_gp' => 1, 'show_in' => 1, 'show_pi' => 1 ) );
		$social_title = $instance['social_title'];
		$show_fb = $instance['show_fb'];
		$show_tw = $instance['show_tw'];
		$show_gp = $instance['show_gp'];
		$show_in = $instance['show_in'];
		$show_pi = $instance['show_pi'];
		
		?>

		<div>
			<p><label for="<?php echo $this->get_field_id('social_title'); ?>">Titolo:</label> <input class="widefat" id="<?php echo $this->get_field_id('social_title'); ?>" name="<?php echo $this->get_field_name('social_title'); ?>" type="text" value="<?php echo esc_attr($social_title); ?>"></p>			
			<p><input class="checkbox" id="<?php echo $this->get_field_id('show_fb'); ?>" name="<?php echo $this->get_field_name('show_fb'); ?>" type="checkbox" value="1" <?php echo ($show_fb == 1 ? 'checked' : ''); ?> /><label for="<?php echo $this->get_field_id('show_fb'); ?>">Show Facebook button</label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('show_tw'); ?>" name="<?php echo $this->get_field_name('show_tw'); ?>" type="checkbox" value="1" <?php echo ($show_tw == 1 ? 'checked' : ''); ?> /><label for="<?php echo $this->get_field_id('show_tw'); ?>">Show Twitter button</label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('show_gp'); ?>" name="<?php echo $this->get_field_name('show_gp'); ?>" type="checkbox" value="1" <?php echo ($show_gp == 1 ? 'checked' : ''); ?> /><label for="<?php echo $this->get_field_id('show_gp'); ?>">Show Google+ button</label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('show_in'); ?>" name="<?php echo $this->get_field_name('show_in'); ?>" type="checkbox" value="1" <?php echo ($show_in == 1 ? 'checked' : ''); ?> /><label for="<?php echo $this->get_field_id('show_in'); ?>">Show LinkedIn button</label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('show_pi'); ?>" name="<?php echo $this->get_field_name('show_pi'); ?>" type="checkbox" value="1" <?php echo ($show_pi == 1 ? 'checked' : ''); ?> /><label for="<?php echo $this->get_field_id('show_pi'); ?>">Show Pinterest button</label></p>
		</div>

		<?php
	} //end form

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
	    $instance = $old_instance;
	    $instance['social_title'] = $new_instance['social_title'];
	    $instance['show_fb'] = $new_instance['show_fb'];
		$instance['show_tw'] = $new_instance['show_tw'];
		$instance['show_gp'] = $new_instance['show_gp'];
		$instance['show_in'] = $new_instance['show_in'];
		$instance['show_pi'] = $new_instance['show_pi'];	    
	    return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		//TODO add script -> usare una pagina di opzioni del plugin?
		//TODO localizzare scritte
		//TODO aggiungere meta con php (non con javascript) in modo che i vari social possano leggere le info dalla pagina
		//TODO formattare conteggio condivisioni (#mila, etc)

		//FB ok
		//TW aggiungere meta (quali sono?)
		//G+ count e meta (vedi fb.php)
		//PI share, count, meta
		//IN share, count, meta

		//i meta og sono aggiunti dalla funzione nylosia_social_add_meta

		//info sulla pagina
		//$link = wp_get_shortlink();
		$link = get_permalink();
		$name = get_bloginfo('name');
		$slogan = get_bloginfo('description');
		$page_title = get_the_title();
		//facebook
		$fbappid = get_option('nylosia_fb_appid');
		$fblang = get_option('nylosia_fb_lang');
		$fbpicture = get_option('nylosia_fb_picture');
		if( is_numeric($fbpicture) ) {
			//restituisce url, larghezza, altezza, true se icona
			$fbpicutre_attr = wp_get_attachment_image_src( $fbpicture );
			$fbpicutre_url = $fbpicutre_attr[0];
		} else {
			$fbpicutre_url = $fbpicture;
		}
		$twuser = get_option('nylosia_tw_user');
		if ( $twuser ) {
			$twvia = '&via='.$twuser;
		}

		echo $args['before_widget'].$args['before_title'].$instance['social_title'].$args['after_title'];
		?>

		<div class="nylosia-social-container">
		<?php 
		if ($instance['show_fb']) { ?>
			<a href="#" class="nylosia-social-sprite nylosia-social-fb" data-link="<?php echo esc_url($link) ?>" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_tw']) { ?>
			<a href="#" class="nylosia-social-sprite nylosia-social-tw" data-link="<?php echo esc_url($link) ?>" data-twuser="<?php echo esc_attr($twuser) ?>" data-twtext="<?php echo esc_attr($name.' - '.$page_title) ?>" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_gp']) { ?>
			<a href="#" class="nylosia-social-sprite nylosia-social-gp" data-link="<?php echo esc_url($link) ?>" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_in']) { ?>
			<a href="#" class="nylosia-social-sprite nylosia-social-in" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_pi']) { ?>
			<a href="#" class="nylosia-social-sprite nylosia-social-pi" data-nylosia-social-title="0"></a>
		<?php } ?>
		</div>
		<?php echo $args['after_widget'] ?>

		<?php
	}

} //end NylosiaSocialWidget

/**
 *
 */
class NylosiaRatingWidget extends WP_Widget
{

	function NylosiaRatingWidget() {
    	$widget_ops = array('classname' => 'NylosiaRatingWidget', 'description' => 'Add nylosia rating widget' );
		$control_ops = array('width' => 350, 'height' => 350);
		$this->WP_Widget('NylosiaRatingWidget', 'Nylosia Rating Widget', $widget_ops, $control_ops); //base id, name, args
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'rating_title' => 'Vota' ) );
		$rating_title = $instance['rating_title'];
		
		?>

		<div>
			<p><label for="<?php echo $this->get_field_id('rating_title'); ?>">Titolo:</label> <input class="widefat" id="<?php echo $this->get_field_id('rating_title'); ?>" name="<?php echo $this->get_field_name('rating_title'); ?>" type="text" value="<?php echo esc_attr($rating_title); ?>"></p>			
		</div>

		<?php
	} //end form

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
	    $instance = $old_instance;
	    $instance['rating_title'] = $new_instance['rating_title'];
	    return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$id = get_the_ID();

		echo $args['before_widget'].$args['before_title'].$instance['rating_title'].$args['after_title'];
		?>

		<div class="nylosia-rating-container" data-post-id="<?php echo $id ?>" data-rating="0" data-url="<?php echo admin_url( 'admin-ajax.php' ) ?>">
			<a href="#" class="nylosia-rating-star nylosia-rating-star-1" data-star="1" title="seleziona per assegnare 1 stella all'articolo"></a>
			<a href="#" class="nylosia-rating-star nylosia-rating-star-2" data-star="2" title="seleziona per assegnare 2 stelle all'articolo"></a>
			<a href="#" class="nylosia-rating-star nylosia-rating-star-3" data-star="3" title="seleziona per assegnare 3 stelle all'articolo"></a>
			<a href="#" class="nylosia-rating-star nylosia-rating-star-4" data-star="4" title="seleziona per assegnare 4 stelle all'articolo"></a>
			<a href="#" class="nylosia-rating-star nylosia-rating-star-5" data-star="5" title="seleziona per assegnare 5 stelle all'articolo"></a>
			<span class="nylosia-rating-caption"></span>
			<br>
			<span class="nylosia-rating-history"></span>			
		</div>
		<?php echo $args['after_widget'] ?>

		<?php
	}

} //end NylosiaRatingWidget


?>