<?php

/**
 * LSX Projects Widget
 */
class LSX_Project_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::WP_Widget(false, $name = esc_html__('LLSX Projects', 'lsx-projects'));
    }

    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $title_link = $instance['title_link'];
        $tagline = $instance['tagline'];
        $columns = $instance['columns'];
        $orderby = $instance['orderby'];
        $order = $instance['order'];
        $limit = $instance['limit'];

        $include = $instance['include'];
        $size = $instance['size'];

        // If limit not set, display all posts
        if ($limit == '' || $limit == false) {
            $limit = "-1";
        }

        // If specific posts included, don't set a limit
        if ($include != '') {
            $limit = "-1";
        }

        if ($title_link) {
            $link_open = "<a href='$title_link'>";
            $link_close = "</a>";
        } else {
            $link_open = "";
            $link_close = "";
        }

        echo $before_widget;
        if ($title) {
            echo $before_title . $link_open . $title . $link_close . $after_title;
        }

        if ($tagline) {
            echo "<p class='tagline text-center'>$tagline</p>";
        }

        if (class_exists('LSX_Project')) {
            project(array(
                    'columns' => $columns,
                    'orderby' => $orderby,
                    'order' => $order,
                    'limit' => $limit,
                    'include' => $include,
                    'size' => $size,
                )
            );
        };
        echo $after_widget;
    }

    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['title_link'] = strip_tags($new_instance['title_link']);
        $instance['tagline'] = strip_tags($new_instance['tagline']);
        $instance['columns'] = strip_tags($new_instance['columns']);
        $instance['orderby'] = strip_tags($new_instance['orderby']);
        $instance['order'] = strip_tags($new_instance['order']);
        $instance['limit'] = strip_tags($new_instance['limit']);
        $instance['include'] = strip_tags($new_instance['include']);
        $instance['size'] = strip_tags($new_instance['size']);

        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    function form($instance)
    {

        $defaults = array(
            'title' => 'Projects',
            'title_link' => '',
            'tagline' => '',
            'columns' => '3',
            'orderby' => 'name',
            'order' => 'ASC',
            'limit' => '',
            'include' => '',
            'size' => '300',
        );
        $instance = wp_parse_args((array)$instance, $defaults);

        $title = esc_attr($instance['title']);
        $title_link = esc_attr($instance['title_link']);
        $tagline = esc_attr($instance['tagline']);
        $columns = esc_attr($instance['columns']);
        $orderby = esc_attr($instance['orderby']);
        $order = esc_attr($instance['order']);
        $limit = esc_attr($instance['limit']);
        $include = esc_attr($instance['include']);
        $size = esc_attr($instance['size']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bs-project'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title_link'); ?>"><?php _e('Title Link:',
                    'bs-project'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title_link'); ?>"
                   name="<?php echo $this->get_field_name('title_link'); ?>" type="text"
                   value="<?php echo $title_link; ?>"/>
            <small>Link the widget title to a URL</small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tagline'); ?>"><?php _e('Tagline:'); ?></label>
            <textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('tagline'); ?>"
                      name="<?php echo $this->get_field_name('tagline'); ?>"><?php echo $tagline; ?></textarea>
            <small>Tagline to display below the widget title</small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Columns:', 'bs-project'); ?></label>
            <select name="<?php echo $this->get_field_name('columns'); ?>"
                    id="<?php echo $this->get_field_id('columns'); ?>" class="widefat">
                <?php
                $options = array('1', '2', '3', '4');
                foreach ($options as $option) {
                    echo '<option value="' . lcfirst($option) . '" id="' . $option . '"', $columns == lcfirst($option) ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By:', 'bs-project'); ?></label>
            <select name="<?php echo $this->get_field_name('orderby'); ?>"
                    id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
                <?php
                $options = array(
                    'None' => 'none',
                    'ID' => 'ID',
                    'Name' => 'name',
                    'Menu Order' => 'menu-order',
                    'Date' => 'date',
                    'Modified Date' => 'modified',
                    'Random' => 'rand'
                );
                foreach ($options as $name => $value) {
                    echo '<option value="' . $value . '" id="' . $value . '"', $orderby == $value ? ' selected="selected"' : '', '>', $name, '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:', 'bs-project'); ?></label>
            <select name="<?php echo $this->get_field_name('order'); ?>"
                    id="<?php echo $this->get_field_id('order'); ?>" class="widefat">
                <?php
                $options = array(
                    'Ascending' => 'ASC',
                    'Descending' => 'DESC'
                );
                foreach ($options as $name => $value) {
                    echo '<option value="' . $value . '" id="' . $value . '"', $order == $value ? ' selected="selected"' : '', '>', $name, '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Maximum amount:',
                    'bs-project'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>"
                   name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>"/>
            <small><?php _e('Leave empty to display all'); ?></small>
        </p>
        <p class="bs-projects-specify">
            <label for="<?php echo $this->get_field_id('include'); ?>"><?php _e('Specify Project s by ID:',
                    'bs-project'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('include'); ?>"
                   name="<?php echo $this->get_field_name('include'); ?>" type="text" value="<?php echo $include; ?>"/>
            <small><?php _e('Comma separated list, overrides limit setting'); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Image size:', 'bs-project'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('size'); ?>"
                   name="<?php echo $this->get_field_name('size'); ?>" type="text" value="<?php echo $size; ?>"/>
        </p>

        <?php

    }

} // end class project_widget
add_action('widgets_init', create_function('', 'return register_widget("LSX_Project_Widget");'));
?>