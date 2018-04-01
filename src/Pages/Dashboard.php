<?php
/**
 * @package ideaPropPlugin
 */

namespace src\Pages;

use src\Api\SettingsApi;
use src\Base\BaseController;
use src\Api\Callbacks\AdminCallbacks;


class Dashboard extends BaseController
{
    public $settings;

    public $callbacks;
    
    public $pages = array();

    public $subpages = array();
    
    public function register(){
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setPages();
        // $this->setSubPages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();


        $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();
        add_filter( "plugin_action_links_" . PLUGIN , array( $this, 'add_settings_link' ) );

    
    }

    public function setPages()
    {
        $this->pages = array(
            array(
				'page_title' => 'IdeaProp Plugin', 
				'menu_title' => 'IdeaProp', 
				'capability' => 'manage_options', 
				'menu_slug' => 'ideaProp_plugin', 
				'callback' => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url' => 'dashicons-store', 
				'position' => 110
			)

        );
    }

     public function add_settings_link($links){
        //add custom settings link
        $settings_link = '<a href="Admin.php?page=ideaProp_plugin">Settings</a>';
        array_push( $links, $settings_link );
        return $links;
    }
    
    public function setSettings()
    {
        $args = array(
            array(
                //unique option group for each section - unique name for custom field
                'option_group' => 'ideaProp_options_group',
                'option_name' => 'text_example',
                'callback' => array ($this->callbacks, 'ideaPropOptionsGroup')
            ),
            array(
                'option_group' => 'ideaProp_options_group',
                'option_name' => 'first_name'
            )
        );

        $this->settings->setSettings( $args );
    }

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'ideaProp_admin_index',
				'title' => 'Settings',
                'callback' => array( $this->callbacks, 'ideaPropAdminSection' ),
                //menu slug name of page
				'page' => 'ideaProp_plugin'
			)
		);
		$this->settings->setSections( $args );
    }
    
    public function setFields()
	{
		$args = array(
			array(
                //id needs to be same as settings option_name
				'id' => 'text_example',
				'title' => 'Text Example',
				'callback' => array( $this->callbacks, 'ideaPropTextExample' ),
				'page' => 'ideaProp_plugin',
                'section' => 'ideaProp_admin_index',
                //customize here 
				'args' => array(
					'label_for' => 'text_example',
					'class' => 'example-class'
				)
			),
			array(
				'id' => 'first_name',
				'title' => 'First Name',
				'callback' => array( $this->callbacks, 'ideaPropFirstName' ),
				'page' => 'ideaProp_plugin',
				'section' => 'ideaProp_admin_index',
				'args' => array(
					'label_for' => 'first_name',
					'class' => 'example-class'
				)
			)
		);
		$this->settings->setFields( $args );
	}

}