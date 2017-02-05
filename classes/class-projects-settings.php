<?php

class LSX_Project_Settings {

	public function __construct()
	{
		add_action( 'admin_init', array( $this, 'options_init' ) );
		add_action( 'admin_menu', array( $this, 'options_add_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'options_scripts' ) );   
	}

	// Init plugin options to white list our options
	function options_init()
	{
		register_setting( 'project_options', 'project_options', array( $this, 'options_validate' ) );
	}

	// Add menu page
	function options_add_page() 
	{
		add_options_page('Bootstrap Project Options', 'Bootstrap Project', 'manage_options', 'project_options', array( $this, 'options_do_page' ) );
	}

	// Draw the menu page itself
	function options_do_page() 
	{
		?>
		<div class="wrap">
			<h2>Bootstrap Project Options</h2>
			<form method="post" action="options.php">
				<?php settings_fields('project_options'); ?>
				<?php $options = get_option('project_options'); ?>
				<table class="form-table">
					<tr valign="top"><th scope="row">Disable Single Posts</th>
						<td>
							<input name="project_options[disable_single]" type="checkbox" value="1" <?php checked('1', $options['disable_single']); ?> />
						</td>
					</tr>					
					<tr valign="top"><th scope="row">Placeholder Image</th>
						<td>
							<label for="upload_image">
								<?php
									if ( ! $options['placeholder'] ) {
										$options['placeholder'] = dirname( plugin_dir_url( __FILE__ ) ) . '/img/placeholder.gif';
									}									
								?>
							    <input id="upload_image" type="text" size="36" name="project_options[placeholder]" value="<?php echo $options['placeholder']; ?>" />
							    <input id="upload_image_button" class="button" type="button" value="Upload Image" />
							    <br />Enter a URL or upload an image
							</label>
							<br /><br />
							<?php 
							if ( $options['placeholder'] ) 
								echo '<img src="' . $options['placeholder'] . '" alt="placeholder">';
							?>
	  					</td>
					</tr>			
				</table>
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
		<?php	
	}

	// Sanitize and validate input. Accepts an array, return a sanitized array.
	function options_validate($input) 
	{
		// Our first value is either 0 or 1
		$input['disable_single'] = ( $input['disable_single'] == 1 ? 1 : 0 );
		
		// Say our second option must be safe text with no HTML tags
		$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
		
		return $input;
	}

	function options_scripts() {
	    if ( isset( $_GET['page'] ) && $_GET['page'] == 'project_options' ) {
	        wp_enqueue_media();
	        wp_register_script('my-admin-js', dirname( plugin_dir_url( __FILE__ ) ) . '/js/add-media.js', array('jquery'));
	        wp_enqueue_script('my-admin-js');
	    }
	}

}

$LSX_Project_Settings = new LSX_Project_Settings();