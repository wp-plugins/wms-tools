<?php
/*
Plugin Name: WMS Tools
Plugin Script: wms-tools.php
Plugin URI: http://marto.lazarov.org/plugins/wms-tools
Description: Connect your wordpress blog to wms-tools.com
Version: 1.0.8
Author: mlazarov
Author URI: http://marto.lazarov.org
*/

if (!class_exists('wms_tools')) {
	class wms_tools {

		function wms_tools() {
			$this->__construct();

		}
		function __construct() {

			$this->plugin_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));


			$stored_options = get_option('wms_tools_options');

			$this->options = (array)(is_serialized($stored_options)) ? unserialize($stored_options) : $stored_options;

			// Setting filters, actions, hooks....
			add_action("admin_menu", array (
				& $this,
				"admin_menu_link"
			));

			add_action('wp_footer', array(&$this,'footer'));

		}

		// -----------------------------------------------------------------------------------------------------------
		/**
		* @desc Adds the options subpanel
		*/
		function admin_menu_link() {
			add_management_page('WMS Tools', 'WMS Tools', 8, basename(__FILE__), array (
				& $this,
				'admin_options_page'
			));
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array (
				& $this,
				'filter_plugin_actions'
			), 10, 2);
		}

		// -----------------------------------------------------------------------------------------------------------
		/**
		* Adds the Settings link to the plugin activate/deactivate page
		*/
		function filter_plugin_actions($links, $file) {
			$settings_link = '<a href="tools.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
			array_unshift($links, $settings_link); // before other links

			return $links;
		}

		function Footer(){
			if($this->options['user_code']){
			?>

<script src="http://wms-tools.com/kanalytics.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
kanalytics(<?=$this->options['user_code'];?>);
//-->
</script>

<?php
			}
		}

		// -----------------------------------------------------------------------------------------------------------
		/**
		* Administration options page
		*/
		function admin_options_page() {
			global $wpdb;

			if ($_POST['wms_tools']) {
				$this->options['user_code'] = (int)$_POST['user_code'];
				update_option('wms_tools_options', serialize($this->options));

			}

			?>
			<div class="wrap">
				<div id="dashboard" style="width:150px;padding:10px;">
					<h3>WMS Tools</h3>
					<form method="post">
						<div  style="">
							User Code:<br/>
							<input type="text" name="user_code" value="<?=$this->options['user_code'];?>" size="8"/>
							<input type="submit" name="wms_tools" class="button-primary" value="Save" />
						</div>
					</form>
				</div>
				<img src="<?=$this->plugin_url;?>screenshot-1.png" alt="User code"/>
			</div>
			<?php
		}

	} //End Class
}

if (class_exists('wms_tools')) {
	$wp_delete_posts_var = new wms_tools();
}
?>
