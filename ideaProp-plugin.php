<?php
/**
 * @package ideaPropPlugin
 */
/*
Plugin Name: ideaProp Plugin
Plugin URI: https://github.com/nancyk25
Description: This is my submission for writing a custom plugin for IdeaTree.
Version: 1.0.0
Author: Nancy Kim
Author URI: https://github.com/nancyk25
License: GPLv2 or later
Text Domain: ideaProp-plugin
*/

defined( 'ABSPATH' ) or die('Hey, you can\t access this file you silly human!');

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php') ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

//workaround for settings link
define( 'PLUGIN' , plugin_basename( __FILE__) );
define('PLUGINURL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation
 */
function activate_ideaProp_plugin() {
	src\Base\Activate::activate();
}
/**
 * The code that runs during plugin deactivation
 */
function deactivate_ideaProp_plugin() {
	src\Base\Deactivate::deactivate();
}


//activation
register_activation_hook( __FILE__, 'activate_ideaProp_plugin');

//deactivation
register_deactivation_hook( __FILE__, 'deactivate_ideaProp_plugin');

//trigger classes to register all services
if ( class_exists( 'src\\Init' ) ) {
    src\Init::register_services();
}









/*
 This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/