<?php

/** 
 * NYLOSIA WIDGETS
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
			<a href="#" onclick="nylosiaSocialFBShare(); return false;" class="nylosia-social-sprite nylosia-social-fb" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_tw']) { ?>
			<a href="javascript: void(0)" onclick="window.open('http://twitter.com/share?url=<?php echo esc_url($link) ?><?php echo $twvia ?>&text=<?php echo esc_attr($name.' - '.$page_title) ?>', '', 'toolbar=0,status=0,width=548,height=325');" class="nylosia-social-sprite nylosia-social-tw" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_gp']) { ?>
			<a href="javascript: void(0)" onclick="window.open('https://plus.google.com/share?url=<?php echo esc_url($link) ?>', '', 'toolbar=0,status=0,width=548,height=325');" class="nylosia-social-sprite nylosia-social-gp" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_in']) { ?>
			<a href="#" class="nylosia-social-sprite nylosia-social-in" data-nylosia-social-title="0"></a>
		<?php }
		if ($instance['show_pi']) { ?>
			<a href="#" class="nylosia-social-sprite nylosia-social-pi" data-nylosia-social-title="0"></a>
		<?php } ?>
		</div>
		<?php echo $args['after_widget'] ?>

		<script>
			window.fbAsyncInit = function() {
				FB.init({
				  appId      : '<?php echo $fbappid ?>',
				  xfbml      : true,
				  version    : 'v2.0'
				});
			};

			(function(d, s, id){
			 var js, fjs = d.getElementsByTagName(s)[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement(s); js.id = id;
			 js.src = "http://connect.facebook.net/<?php echo $fblang ?>/sdk.js";
			 fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

	 		jQuery(function(){
	 			if (jQuery(".nylosia-social-fb").length > 0)
					nylosiaCountFBShare();
				if (jQuery(".nylosia-social-tw").length > 0)
					nylosiaCountTWShare();
	 		});

	 		function nylosiaCountFBShare() {
	 			//recupero il numero di share della pagina
	 			jQuery.ajax({
	 				url: 'http://graph.facebook.com/?id=<?php echo $link ?>',
	 				dataType: 'json'
	 			}).done(function(data) {
	 				if(data.shares) {
	 					jQuery(".nylosia-social-fb").attr("data-nylosia-social-title", data.shares);
	 				}
	 			});	 			
	 		}

	 		function nylosiaCountTWShare() {
	 			//recupero il numero di share della pagina
	 			jQuery.ajax({
	 				url: 'http://urls.api.twitter.com/1/urls/count.json?url=<?php echo $link ?>',
	 				dataType: 'json'
	 			}).done(function(data) {
	 				if(data.shares) {
	 					jQuery(".nylosia-social-tw").attr("data-nylosia-social-title", data.shares);
	 				}
	 			});	 			
	 		}

			function nylosiaSocialFBFeed() {
				FB.ui(
				  {
				    method: 'feed',
				    name: '<?php echo $page_title ?>',
				    link: '<?php echo $link ?>',
				    picture: '<?php echo $fbpicutre_url ?>',
				    caption: '<?php echo $name ?>',
				    description: '<?php echo $slogan ?>'
				  },
				  function(resp) { }
				);				
			}

			function nylosiaSocialFBShare() {
				FB.ui(
				  {
				    method: 'stream.share',
				    href: '<?php echo $link ?>'
				  },
				  function(resp) { nylosiaCountFBShare(); }
				);				
			}

		</script>

		<?php
	}

} //end NylosiaSocialWidget

// register NylosiaSocialWidget
add_action( 'widgets_init', 'register_nylosia_social_widget' );
function register_nylosia_social_widget() {
    register_widget( 'NylosiaSocialWidget' );
}

?>