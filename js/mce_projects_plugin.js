// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.project', {
		// creates control instances based on the control's id.
		// our button's id is "project_button"
		createControl : function(id, controlManager) {
			if (id == 'project_button') {
				// creates the button
				var button = controlManager.createButton('project_button', {
					title : 'Project Shortcode', // title of the button
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;						
						tb_show( 'Add Projects', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=project-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin.
	tinymce.PluginManager.add('project', tinymce.plugins.project);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked		
		var form = jQuery('<div id="project-form"><table id="project-table" class="form-table">\
			<tr>\
				<th><label for="project-layout">Layout</label></th>\
				<td>\
					<select name="layout" id="project-layout">\
					  <option value="standard">Standard</option>\
					  <option value="panel">Panel</option>\
					</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="project-columns">Columns</label></th>\
				<td>\
					<select name="columns" id="project-columns">\
					  <option value="1">1</option>\
					  <option value="2">2</option>\
					  <option value="3">3</option>\
					  <option value="4" selected="selected">4</option>\
					</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="project-orderby">Order By</label></th>\
				<td>\
					<select name="orderby" id="project-orderby">\
					  <option value="none">None</option>\
					  <option value="id">ID</option>\
					  <option value="name" selected="selected">Name</option>\
					  <option value="date">Date Created</option>\
					  <option value="modified">Date Modified</option>\
					  <option value="rand">Random</option>\
					</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="project-order">Order</label></th>\
				<td>\
					<select name="order" id="project-order">\
					  <option value="ASC">Ascending</option>\
					  <option value="DESC">Descending</option>\
					</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="project-limit">Maximum Amount</label></th>\
				<td><input type="text" name="limit" id="project-limit" value="" /><br />\
				<small>Leave empty to display all</small>\
			</tr>\
			<tr>\
				<th><label for="project-include">Specify Project s by ID</label></th>\
				<td><input type="text" name="include" id="project-include" value="" /><br />\
				<small>Comma separated list, overrides limit setting</small></td>\
			</tr>\
			<tr>\
				<th><label for="project-size">Image Size</label></th>\
				<td><input type="text" name="size" size="3" id="project-size" value="200" /><small>px</small>\
			</tr>\
			<tr>\
				<th><label for="project-link">Link Titles</label></th>\
				<td><select name="link" id="project-link">\
					<option value="1">Yes</option>\
					<option value="0">No</option>\
				</select><br/>\
				<small>Will only be linked if single project s are not disabled in the settings</small></td>\
			</tr>\
			<tr>\
				<th><label for="project-show_roles">Show Roles</label></th>\
				<td><select name="show_roles" id="project-show_roles">\
					<option value="1">Yes</option>\
					<option value="0">No</option>\
				</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="project-show_desc">Show Descriptions</label></th>\
				<td><select name="show_desc" id="project-show_desc">\
					<option value="1">Yes</option>\
					<option value="0">No</option>\
				</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="project-excerpt_length">Excerpt Length</label></th>\
				<td><input type="text" name="excerpt_length" id="project-excerpt_length" value="300" /><br />\
				<small>0 to display entire description</small>\
			</tr>\
			<tr>\
				<th><label for="project-show_social">Show Social Icons</label></th>\
				<td><select name="show_social" id="project-show_social">\
					<option value="1">Yes</option>\
					<option value="0">No</option>\
				</select>\
				</td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="project-submit" class="button-primary" value="Insert Project s" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#project-submit').click(function(){
			// defines the options and their default values			
			var options = {
				'layout'	 	: 'standard',
				'columns'    	: '4',
				'orderby'    	: 'name',
				'order'		 	: 'ASC',
				'limit'			: '',
				'include'		: '',			
				'size'       	: '200',				
				'link'       	: false,
				'show_roles' 	: true,
				'show_desc'  	: true,
				'excerpt_length' : '300',
				'show_social'	: true				
				};
			var shortcode = '[project';
			
			for( var index in options) {
				var value = table.find('#project-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()