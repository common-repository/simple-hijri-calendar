<?php

if ( ! class_exists( 'Simple_Hijri_Calendar' ) ) {

	/**
	* Hijri Calendar
	*/
	class Simple_Hijri_Calendar extends WP_Widget {
		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			// widget actual processes
			$widget_ops = array( 'classname' 	=> 'widget_simple_hijri_calendar', 'description' 	=> __( 'Simple hijri calendar widget', 'simple-hijri-calendar' ) );
			parent::__construct( 'simple_hijri_calendar', __( 'Simple Hijri Calendar', 'simple-hijri-calendar' ), $widget_ops );
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			// outputs the content of the widget
			$hijri = new uCal;
			$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Simple Hijri Calendar', 'simple-hijri-calendar' );
			$format = isset( $instance['format'] ) ? $instance['format'] : get_option( 'date_format' );
			$language = isset( $instance['language'] ) ? $instance['language'] : 'arabic';
			$fonts = isset( $instance['fonts'] ) ? $instance['fonts'] : '';
			$align = isset( $instance['align'] ) ? $instance['align'] : 'alignleft';
			$morning = isset( $instance['morning'] ) ? $instance['morning'] : 7;
			$noon = isset( $instance['noon'] ) ? $instance['noon'] : 11;
			$afternoon = isset( $instance['afternoon'] ) ? $instance['afternoon'] : 15;
			$evening = isset( $instance['evening'] ) ? $instance['evening'] : 18;
			$donate = $instance['donate'];

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			#date_default_timezone_set('Asia/Jakarta');
			$hijri->setLang( $language );

			$hijri_date = $hijri->date( $format, 0, 1 );
			$calendar 	= 'ar' == $language ? simple_hijri_calendar_arabic( $hijri_date ) : $hijri_date;
			$class 		= 'ar' == $language ? 'arabic' : 'english'; 
			
			if ( ! empty( $fonts ) ) { 
				if ( 'ar' == $language ) {
					echo simple_hijri_calendar_fonts_css( $fonts );
				}
			}

			?>

			<div class="simple-hijri-calendar ds">
				<div class="hijri-calendar clearfix"
				data-morning="<?php echo esc_attr( $morning ); ?>"
				data-noon="<?php echo esc_attr( $noon ); ?>"
				data-afternoon="<?php echo esc_attr( $afternoon ); ?>"
				data-evening="<?php echo esc_attr( $evening ); ?>">

		            <div class="date <?php echo esc_attr( $align ); ?>">
		                <div class="hijri time <?php echo esc_attr( $class ); ?>">
		                    <time datetime="<?php echo esc_attr( $hijri->date( 'd-m-Y h:i' ) ); ?>"><?php esc_html_e( $calendar ); ?></time>
		                </div>
		                <div class="clear"></div>
		                <div class="gregori time">
		                    <time datetime="<?php echo esc_attr( date( 'd-m-Y h:i' ) ); ?>"><?php echo date( 'j M Y' ); ?></time>
		                </div>
		            </div>
		            <!-- end .hijri-date -->
		            <?php if ( $donate ) { ?>
		            <a class="donate" href="" title="Donate this plugin!">
		            	<span class="screen-reader-text"><?php _e( 'Designed by Dakwah Studio' ); ?></span>
		            </a>
		            <?php } ?>
		        </div>
		    </div>
			<?php

			echo $args['after_widget'];
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			// outputs the options form on admin
			$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Simple Hijri Calendar', 'simple-hijri-calendar' );
			$format = isset( $instance['format'] ) ? $instance['format'] : get_option( 'date_format' );
			$language = isset( $instance['language'] ) ? $instance['language'] : 'arabic';
			$align = isset( $instance['align'] ) ? $instance['align'] : 'alignleft';
			$fonts = isset( $instance['fonts'] ) ? $instance['fonts'] : '';
			$morning = isset( $instance['morning'] ) ? $instance['morning'] : 7;
			$noon = isset( $instance['noon'] ) ? $instance['noon'] : 11;
			$afternoon = isset( $instance['afternoon'] ) ? $instance['afternoon'] : 15;
			$evening = isset( $instance['evening'] ) ? $instance['evening'] : 18;
			$donate = $instance['donate'];
			?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'simple-hijri-calendar' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'format' ); ?>"><?php _e( 'Format:', 'simple-hijri-calendar' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>" type="text" value="<?php echo esc_attr( $format ); ?>">
				<small><?php _e( 'Example: l, jS F, Y. For complete list time formatting, read:', 'simple-hijri-calendar' ); ?> <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php _e( 'Documentation on date and time formatting.', 'simple-hijri-calendar' ); ?></a></small>
			</p>

			<p>
				<div style="display:inline-block">
					<label for="<?php echo $this->get_field_id( 'language' ); ?>"><?php _e( 'Text:', 'simple-hijri-calendar' ); ?></label><br> 
					<select id="<?php echo $this->get_field_id( 'language' ); ?>" name="<?php echo $this->get_field_name( 'language' ); ?>">
						<option value="ar" <?php selected( $language, 'ar' ); ?>><?php _e( 'Arabic', 'simple-hijri-calendar' ); ?></option>
						<option value="en" <?php selected( $language, 'en' ); ?>><?php _e( 'English', 'simple-hijri-calendar' ); ?></option>
					</select>
				</div>

				<div style="display:inline-block">
					<label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e( 'Align:', 'simple-hijri-calendar' ); ?></label><br> 
					<select id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
						<option value="alignleft" <?php selected( $align, 'alignleft' ); ?>><?php _e( 'Left', 'simple-hijri-calendar' ); ?></option>
						<option value="aligncenter" <?php selected( $align, 'aligncenter' ); ?>><?php _e( 'Center', 'simple-hijri-calendar' ); ?></option>
						<option value="alignright" <?php selected( $align, 'alignright' ); ?>><?php _e( 'Right', 'simple-hijri-calendar' ); ?></option>
					</select>
				</div>
				
				<div style="display:inline-block">
					<label for="<?php echo $this->get_field_id( 'fonts' ); ?>"><?php _e( 'Fonts:', 'simple-hijri-calendar' ); ?></label><br>
					<select id="<?php echo $this->get_field_id( 'fonts' ); ?>" name="<?php echo $this->get_field_name( 'fonts' ); ?>">
						<option value=""><?php _e( '&mdash; Select Fonts &mdash;', 'simple-hijri-calendar' ); ?></option>
						<?php
							$fonts_options = simple_hijri_calendar_fonts();
							foreach ($fonts_options as $css => $family ) {
								printf(
									'<option value="%1$s"%3$s>%2$s</option>',
									esc_attr( $css ),
									esc_html( $family ),
									selected( $fonts, $css, false )
								);
							}
						?>
					</select>
				</div>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'morning' ); ?>"><?php _e( 'Morning time:', 'simple-hijri-calendar' ); ?></label><br> 
				<select id="<?php echo $this->get_field_id( 'morning' ); ?>" name="<?php echo $this->get_field_name( 'morning' ); ?>">
					<?php simple_hijri_calendar_hour( $morning ); ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'noon' ); ?>"><?php _e( 'Noontime:', 'simple-hijri-calendar' ); ?></label><br> 
				<select id="<?php echo $this->get_field_id( 'noon' ); ?>" name="<?php echo $this->get_field_name( 'noon' ); ?>">
					<?php simple_hijri_calendar_hour( $noon ); ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'afternoon' ); ?>"><?php _e( 'Afternoon time:', 'simple-hijri-calendar' ); ?></label><br> 
				<select id="<?php echo $this->get_field_id( 'afternoon' ); ?>" name="<?php echo $this->get_field_name( 'afternoon' ); ?>">
					<?php simple_hijri_calendar_hour( $afternoon ); ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'evening' ); ?>"><?php _e( 'Evening time:', 'simple-hijri-calendar' ); ?></label><br> 
				<select id="<?php echo $this->get_field_id( 'evening' ); ?>" name="<?php echo $this->get_field_name( 'evening' ); ?>">
					<?php simple_hijri_calendar_hour( $evening ); ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'donate' ); ?>"><?php _e( 'Show Donate link:', 'simple-hijri-calendar' ); ?> 
					<input class="checkbox" id="<?php echo $this->get_field_id( 'donate' ); ?>" name="<?php echo $this->get_field_name( 'donate' ); ?>" type="checkbox" <?php checked( $donate, true ) ?> />
				</label>
			</p>
			<?php 
		}

		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			// processes widget options to be saved
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['format'] = ( ! empty( $new_instance['format'] ) ) ? strip_tags( $new_instance['format'] ) : '';
			$instance['language'] = $new_instance['language'];
			$instance['fonts'] = $new_instance['fonts'];
			$instance['align'] = $new_instance['align'];
			$instance['morning'] = $new_instance['morning'];
			$instance['noon'] = $new_instance['noon'];
			$instance['afternoon'] = $new_instance['afternoon'];
			$instance['evening'] = $new_instance['evening'];
			$instance['donate'] = isset( $new_instance['donate'] ) ? 1 : 0;

			return $instance; 
		}
	}
}
/*EOF*/