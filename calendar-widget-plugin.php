<?php

/*
 * Plugin Name: Calendar Widget
 * Plugin URI: https://github.com/clirdlf/calendar-widget
 * Description: Widget for the Digital Conferences Calendar
 * Author: Wayne Graham
 * Version 0.0.1
 * Author URI: https://www.clir.org
 */

// Block direct requests
if (!defined('ABSPATH')) {
    die('-1');
}

add_action(
    'widgets_init',
    function () {
        register_widget('Community_Calendar_Widget');
    }
);

/**
 * Adds Community_Calendar_Widget
 */
class Community_Calendar_Widget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'Community_Calendar_Widget', // Base ID
            __('Community Calendar Widget', 'clir_calendar_widget'), // Name
            array( 'description' => __('Adds the Community Calendar to the site.', 'clir_calendar_widget'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance )
    {
        if (array_key_exists('before_widget', $args)) echo $args['before_widget'];

        echo '<h2 class="widget-title">Upcoming Events</h2>';
        echo '<div id="community_calendar"><ul class="list-unstyled" id="upcoming-events"></ul></div>';
        wp_enqueue_script('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js');

        wp_enqueue_script('format-google-calendar', plugins_url('/js/format-google-calendar.js', __FILE__), array('jquery', 'moment'));
        wp_register_script('community_calendar', plugins_url('/js/community_calendar.js', __FILE__), array('format-google-calendar'));
        // wp_register_script('community_calendar', plugins_url('/js/community_calendar.js', __FILE__), array('jquery', 'momentjs', 'format-google-calendar'));
        wp_enqueue_script('community_calendar');
        wp_localize_script('community_calendar', 'php_vars', array('count' => $instance['count']));


        //if (! empty($instance['count'])) {
            //echo $args['before_title'] . apply_filters('widget_title', $instance['count']). $args['after_title'];
        //}
        //echo __( 'Hello, World!', 'clir_calendar_widget' );
        if (array_key_exists('after_widget', $args)) echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance )
    {

        if (isset($instance['count'])) {
            $count = $instance[ 'count' ];
        } else {
            $count = 2;
        }
?>
    <p>
      <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Count:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>">
    </p>
<?php
    }

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
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['count'] = (! empty($new_instance['count'])) ? strip_tags($new_instance['count']) : '';
        return $instance;
    }
} // class Community_Calendar_Widget
