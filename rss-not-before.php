<?php
/*
Plugin Name: RSS Not Before
Plugin URI: http://www.inventfilm.com/code/rss-not-before/
Description: Removes all rss posts before a certain date.
Version: 1.0
Author: Paul Moukperian
Author URI: http://www.inventfilm.com/

*/

/*  
RSS Not Before - Wordpress plugin to remove post from RSS feeds before a given date.
Copyright (c) 2011 Paul Moukperian http://inventfilm.com 

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, version 3 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

/* ================================ */

function rss_not_before_create_menu() {

	//create new top-level menu
	add_menu_page('RSS Not Before Plugin Settings', 'RSS Not Before Settings', 'administrator', __FILE__, 'rss_not_before_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'rss_not_before_register' );


}
// create custom plugin settings menu
//add_action('admin_menu', 'rss_not_before_create_menu');

function rss_not_before_link() {
	add_options_page('RSS Not Before Plugin Settings', 'RSS Not Before', 'administrator', __FILE__, 'rss_not_before_settings_page',plugins_url('/images/icon.png', __FILE__));
	add_action( 'admin_init', 'rss_not_before_register' );
}
if ( is_admin() )
{ // admin actions
	add_action('admin_menu', 'rss_not_before_link');
} else 
{
	// non-admin enqueues, actions, and filters
}


function rss_not_before_register() {
	//register our settings
	register_setting( 'rss-not-before-settings-group', 'rss_not_before_date' );
}

function rss_not_before_settings_page() {
?>
<div class="wrap">
<h2>RSS Not Before</h2>

<form method="post" action="options.php">
	<?php settings_fields( 'rss-not-before-settings-group' ); ?>
	<table class="form-table">
	<tr valign="top">
	<th scope="row">Remove RSS posts before: <br />YYYY-MM-DD HH:MM:SS</th>
	<td><input type="text" name="rss_not_before_date" value="<?php if (get_option('rss_not_before_date')) {echo get_option('rss_not_before_date');}  ?>" /><br />Current time is: <?php echo date_i18n('Y-m-d H:i:s');?></td>
		</tr>
	</table>
	
	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
</form>
</div>
<?php } 

function posts_where( $where ) {

		$when=get_option('rss_not_before_date');
	
		$where .= " AND post_date > '".$when."'";
		//echo $where;
	return $where;
}

function rss_not_before_filter($query) {
	if ($query->is_feed) {
		add_filter( 'posts_where' , 'posts_where' );
		//print_r($query);
	}
	return $query;
}
add_filter('pre_get_posts','rss_not_before_filter');

?>