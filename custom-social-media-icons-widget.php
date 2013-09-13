<?php
/*
Plugin Name: Custom Social Media Icons Widget
Plugin URI: http://zoerooney.com
Description: Creates a widget with a super simple interface that allows users to easily add unlimited custom social media icons to any widgetized area. You'll need the image URL for each icon.
Version: 0.3
Author: Zoe Rooney
Author URI: http://zoerooney.com
License: GPL2

Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Adds our Custom Social Media Icons widget
=============================================*/

class zr_custom_social_media extends WP_Widget {

    function zr_custom_social_media() {
        $widget_ops = array(
            'classname'=>'social-media-icons', // class that will be added to li element in widgeted area ul
            'description'=> __('Add your custom social media icons using image URLs.') // description displayed in admin
            );
        $this->WP_Widget('zr_custom_social_media', __('Custom Social Media Icons'), $widget_ops); // Name in  the control panel
    }
	
	/* Our arguments
	=============================================*/
		
    function widget($args, $instance) {
            extract($args);
			
			$title = $instance['title']; 
			$amount = empty($instance['amount']) ? 2 : $instance['amount'];
			
			for ($i = 1; $i <= $amount; $i++) {
				$icons[$i] = $instance['icon' . $i];
				$names[$i] = $instance['name' . $i];
				$urls[$i] = $instance['url' . $i];
			}
		
			/* Outputting our widget on the front end
			=============================================*/
			echo '<style>
					#social-media-icons {text-align:center;}
					.sm-icon {display:inline-block;margin-right:10px;}
					.sm-icon:last-of-type {margin-right:0;}
				  </style>';
				  
            echo $before_widget . $before_title . $title . $after_title; // widget title
  
            foreach ($icons as $num => $icon) :
            	if (!empty($icon)) :
            		echo '<a href="' . $urls[$num] . '" target="_blank" class="sm-icon"><img src="' . $icon .'" alt="' . $names[$num] . '" /></a>';
            	endif;
            endforeach;
            
            echo $after_widget; // ends the widget
        }
	
	/* Saving updated information
	=============================================*/
	
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
              
        if (empty($new_instance['icon' . $new_instance['new_amount']])){
        			$instance['amount'] = $new_instance['amount'];
        		} else {
        			$instance['amount'] = $new_instance['new_amount'];
        		}
        
        		for ($i = 1; $i <= $instance['amount']; $i++) {
        			$instance['icon' . $i] = strip_tags($new_instance['icon' . $i]);
        			$instance['url' . $i] = $new_instance['url' . $i];
        			$instance['name' . $i] = strip_tags($new_instance['name' . $i]);
        		}		
        		
        		return $instance;
    }
	
	/* The widget configuration form
	=============================================*/
	
    function form( $instance ) {
       $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) ); 
        $title = strip_tags($instance['title']);
        $amount = empty($instance['amount']) ? 2 : $instance['amount'];
        $new_amount = $amount + 1;
        for ($i = 1; $i <= $amount; $i++) {
        	$names[$i] = empty($instance['name' . $i]) ? "" : $instance['name' . $i];
        	$urls[$i] = empty($instance['url' . $i]) ? "" : $instance['url' . $i];
        	$icons[$i] = empty($instance['icon' . $i]) ? "" : $instance['icon' . $i];
        }
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><em>Click save to add blank fields for additional icons.</em></p>
		<?php foreach ($names as $num => $name) : ?>
		<p style="border-bottom:4px double #eee;padding: 0 0 10px;">
			<label for="<?php echo $this->get_field_id( 'name' . $num ); ?>">Name</label>
			<input id="<?php echo $this->get_field_id( 'name' . $num ); ?>" name="<?php echo $this->get_field_name( 'name' . $num ); ?>" value="<?php echo $instance['name' . $num ]; ?>" placeholder="e.g. Twitter, Facebook" style="width:100%;" />
			<label for="<?php echo $this->get_field_id( 'url' . $num ); ?>">URL:</label>
			<input id="<?php echo $this->get_field_id( 'url' . $num ); ?>" name="<?php echo $this->get_field_name( 'url' . $num ); ?>" value="<?php echo $instance['url' . $num]; ?>"  placeholder="http://" style="width:100%;" />
			<label for="<?php echo $this->get_field_id( 'icon' . $num ); ?>">Icon URL:</label>
			<input id="<?php echo $this->get_field_id( 'icon' . $num ); ?>" name="<?php echo $this->get_field_name( 'icon' . $num ); ?>" value="<?php echo $instance['icon' . $num]; ?>" placeholder="http://" style="width:100%;" />
		</p>
	    <?php endforeach; ?> 
	    
	    <?php //Additional form fields ?>
	    <p style="border-bottom:4px double #eee;padding: 0 0 10px;">
	    	<label for="<?php echo $this->get_field_id( 'name' . $new_amount ); ?>">Name</label>
	    	<input id="<?php echo $this->get_field_id( 'name' . $new_amount ); ?>" name="<?php echo $this->get_field_name( 'name' . $new_amount ); ?>" value="" placeholder="e.g. Twitter, Facebook" style="width:100%;" />
	    	<label for="<?php echo $this->get_field_id( 'url' . $new_amount ); ?>">URL:</label>
	    	<input id="<?php echo $this->get_field_id( 'url' . $new_amount ); ?>" name="<?php echo $this->get_field_name( 'url' . $new_amount ); ?>" value=""  placeholder="http://" style="width:100%;" />
	    	<label for="<?php echo $this->get_field_id( 'icon' . $new_amount ); ?>">Icon URL:</label>
	    	<input id="<?php echo $this->get_field_id( 'icon' . $new_amount ); ?>" name="<?php echo $this->get_field_name( 'icon' . $new_amount ); ?>" value="" placeholder="http://" style="width:100%;" />
	    </p>
	    
	 	<input type="hidden" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $amount ?>" />
	    <input type="hidden" id="<?php echo $this->get_field_id('new_amount'); ?>" name="<?php echo $this->get_field_name('new_amount'); ?>" value="<?php echo $new_amount ?>" /> 
<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("zr_custom_social_media");')); 

?>