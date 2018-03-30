<?php 
/**
 * @package  ideaPropPlugin
 */

namespace src\Api\Callbacks;

use src\Base\BaseController;

class AdminCallbacks extends BaseController
{
    public function adminDashboard()
    {
        return require_once( "$this->plugin_path/templates/admin.php");
    }

    public function adminCpt()
    {
        return require_once("$this->plugin_path/templates/cpt.php");
    }

    public function adminTaxonomy()
    {
        return require_once("$this->plugin_path/templates/taxonomy.php");
    }

    public function ideaPropOptionsGroup ( $input )
    {
        return $input;
    }

    public function ideaPropAdminSection()
    {
        echo 'Check out this admin section!';
    }

    public function ideaPropTextExample()
    {
        $value = esc_attr( get_option( 'text example' ) );
        //name needs to be the same as field id
        echo '<input type="text" class="regular-text" name="text_example" value="' . $value . '" placeholder="Write Something Here!">';
    }

    public function ideaPropFirstName()
    {
        $value = esc_attr( get_option( 'first_name' ) );
		echo '<input type="text" class="regular-text" name="first_name" value="' . $value . '" placeholder="Write your First Name">';
    }
}