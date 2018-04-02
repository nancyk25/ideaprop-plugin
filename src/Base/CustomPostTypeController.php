<?php 
/**
 * @package  ideaPropPlugin
 */
namespace src\Base;
/**
* 
*/

use src\Api\SettingsApi;
use src\Base\BaseController;
use src\Api\Callbacks\CptCallbacks;
use src\Api\Callbacks\AdminCallbacks;


class CustomPostTypeController extends BaseController
{
    public $settings;

    public $callbacks;

    public $cpt_callbacks;

    public $subpages = array();

	public $custom_post_types = array();
	
	public $metadata = array();

    public function register()
    {
        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->cpt_callbacks = new CptCallbacks();

        $this->setSubpages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();

		$this->settings->addSubpages( $this->subpages )->register();
        
		$this->storeCustomPostTypes();

        if ( ! empty( $this->custom_post_types) ){
			add_action('init', array( $this,'registerCustomPostType') );
			add_action( 'init', array( $this, 'storeCustomTaxonomies' ));
			add_action( 'add_meta_boxes', array( $this , 'addMetaBox' ));
			add_filter('manage_property_posts_columns', array( $this, 'propertySetColumns'));
			add_action('save_post', array( $this, 'saveMetaBoxData'));
			add_action( 'manage_property_posts_custom_column', array($this, 'propertyCustomColumns'), 10 , 2);
		}
	}

	function propertySetColumns( $columns )
	{
		unset($columns['categories']);
		unset($columns['tags']);
		unset($columns['date']);
		$newColumns = array();
		$newColumns['title'] = 'Title';
		$newColumns['price'] = 'Price';
		$newColumns['location'] = 'Location';
		$newColumns['doc'] = 'Date of Construction';
		return array_merge($columns,$newColumns);

	}
	
    public function setSubpages()
    {
        	$this->subpages = array(
			array(
				'parent_slug' => 'ideaProp_plugin', 
				'page_title' => 'Custom Post Types', 
				'menu_title' => 'Property Manager', 
				'capability' => 'manage_options', 
				'menu_slug' => 'ideaProp_cpt',
				//this call back set's the subpage under ideaProp called CPT and sends a callback to the template
				'callback' => array( $this->callbacks, 'adminCpt' )
				
			)
		);
    }
    

    public function setSettings()
    {
        	$args = array(
                array(
                    'option_group' => 'ideaProp_plugin_cpt_settings',
                    'option_name' => 'ideaProp_plugin_cpt',
                    // 'callback' => array( $this->cpt_callbacks, 'cptSanitize' )
                )
		);
		$this->settings->setSettings( $args );
    }


    public function setSections()
	{
		$args = array(
			array(
				'id' => 'ideaProp_cpt_index',
				'title' => 'Property Manager',
				'callback' => array( $this->cpt_callbacks, 'cptSectionManager' ),
				'page' => 'ideaProp_cpt'
			)
		);
		$this->settings->setSections( $args );
    }

    public function setFields()
	{

		$args = array(
			array(
				'id' => 'post_type',
				'title' => 'Custom Post Type ID',
				'callback' => array( $this->cpt_callbacks, 'textField' ),
				'page' => 'ideaProp_cpt',
				'section' => 'ideaProp_cpt_index',
				'args' => array(
					'option_name' => 'ideaProp_plugin_cpt',
					'label_for' => 'post_type',
					'placeholder' => 'eg. My Property'
				)
			),
			array(
				'id' => 'singular_name',
				'title' => 'Singular Name',
				'callback' => array( $this->cpt_callbacks, 'textField' ),
				'page' => 'ideaProp_cpt',
				'section' => 'ideaProp_cpt_index',
				'args' => array(
					'option_name' => 'ideaProp_plugin_cpt',
					'label_for' => 'singular_name',
					'placeholder' => 'eg. Property'
				)
			),
			array(
				'id' => 'plural_name',
				'title' => 'Plural Name',
				'callback' => array( $this->cpt_callbacks, 'textField' ),
				'page' => 'ideaProp_cpt',
				'section' => 'ideaProp_cpt_index',
				'args' => array(
					'option_name' => 'ideaProp_plugin_cpt',
					'label_for' => 'plural_name',
					'placeholder' => 'eg. Properties'
				)
			),
			array(
				'id' => 'public',
				'title' => 'Public',
				'callback' => array( $this->cpt_callbacks, 'checkboxField' ),
				'page' => 'ideaProp_cpt',
				'section' => 'ideaProp_cpt_index',
				'args' => array(
					'option_name' => 'ideaProp_plugin_cpt',
					'label_for' => 'public',
					'class' => 'ui-toggle checkbox'
				)
			),
			array(
				'id' => 'has_archive',
				'title' => 'Archive',
				'callback' => array( $this->cpt_callbacks, 'checkboxField' ),
				'page' => 'ideaProp_cpt',
				'section' => 'ideaProp_cpt_index',
				'args' => array(
					'option_name' => 'ideaProp_plugin_cpt',
					'label_for' => 'has_archive',
					'class' => 'ui-toggle checkbox'
				)
			)
		);
		$this->settings->setFields( $args );
	}


    public function storeCustomPostTypes()
    {
		$options = get_option('ideaProp_plugin_cpt');

        $this->custom_post_types[] = array(
				'post_type'             => 'property',
				'name'                  => $options['plural_name'],
				'singular_name'         => $options['singular_name'],
				'menu_name'             => $options['plural_name'],
				'name_admin_bar'        => $options['singular_name'],
				'archives'              => $options['singular_name'] . ' Archives',
				'attributes'            => $options['singular_name'] . ' Attributes',
				'parent_item_colon'     => 'Parent ' . $options['singular_name'],
				'all_items'             => 'All ' . $options['plural_name'],
				'add_new_item'          => 'Add New ' . $options['singular_name'],
				'add_new'               => 'Add New',
				'new_item'              => 'New ' . $options['singular_name'],
				'edit_item'             => 'Edit ' . $options['singular_name'],
				'update_item'           => 'Update ' . $options['singular_name'],
				'view_item'             => 'View ' . $options['singular_name'],
				'view_items'            => 'View ' . $options['plural_name'],
				'search_items'          => 'Search ' . $options['plural_name'],
				'not_found'             => 'No ' . $options['singular_name'] . ' Found',
				'not_found_in_trash'    => 'No ' . $options['singular_name'] . ' Found in Trash',
				'featured_image'        => 'Featured Image',
				'set_featured_image'    => 'Set Featured Image',
				'remove_featured_image' => 'Remove Featured Image',
				'use_featured_image'    => 'Use Featured Image',
				'insert_into_item'      => 'Insert into ' . $options['singular_name'],
				'uploaded_to_this_item' => 'Upload to this ' . $options['singular_name'],
				'items_list'            => $options['plural_name'] . ' List',
				'items_list_navigation' => $options['plural_name'] . ' List Navigation',
				'filter_items_list'     => 'Filter' . $options['plural_name'] . ' List',
				'label'                 => $options['singular_name'],
				'description'           => $options['plural_name'] . 'Custom Post Type',
				'supports'              => array( 'title', 'editor', 'thumbnail' ),
				'taxonomies'            => array( 'category', 'post_tag'),
				'hierarchical'          => true,
				'public'                => $options['public'],
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => $options['has_archive'],
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'post',
				// 'register_meta_box_cb'  => $options['register_meta_box_cb']
		);
    }

    public function registerCustomPostType()
    {
        foreach ($this->custom_post_types as $post_type){
            register_post_type( 'property',
				array(
					'labels' => array(
						'name'                  => 'Property',
						'singular_name'         => 'Property',
						'menu_name'             => 'Properties',
						'name_admin_bar'        => $post_type['name_admin_bar'],
						'archives'              => $post_type['archives'],
						'attributes'            => $post_type['attributes'],
						'parent_item_colon'     => $post_type['parent_item_colon'],
						'all_items'             => 'All Properties',
						'add_new_item'          => 'Add Property',
						'add_new'               => 'Add New',
						'new_item'              => 'New Item',
						'edit_item'             => 'Edit Item',
						'update_item'           => 'Update Item',
						'view_item'             => 'View Item',
						'view_items'            => 'View Items',
						'search_items'          => 'Search Properties',
						'not_found'             => 'No properties found',
						'not_found_in_trash'    => 'No items found',
						'featured_image'        => $post_type['featured_image'],
						'set_featured_image'    => $post_type['set_featured_image'],
						'remove_featured_image' => $post_type['remove_featured_image'],
						'use_featured_image'    => $post_type['use_featured_image'],
						'insert_into_item'      => $post_type['insert_into_item'],
						'uploaded_to_this_item' => $post_type['uploaded_to_this_item'],
						'items_list'            => $post_type['items_list'],
						'items_list_navigation' => $post_type['items_list_navigation'],
						'filter_items_list'     => $post_type['filter_items_list'],
						
					),
					'label'                     => $post_type['label'],
					'description'               => $post_type['description'],
					'supports'                  => array(
						'title',
						'editor',
						'excerpt',
						'thumbnail',
						'revisions',
						'custom-fields'
					),
					// 'register_meta_box_cb'      => 'met',
					'taxonomies'                => array('category','post_tag'),
					'hierarchical'              => true,
					'public'                    => true,
					'show_ui'                   => $post_type['show_ui'],
					'show_in_menu'              => $post_type['show_in_menu'],
					'menu_position'             => 5,
					'show_in_admin_bar'         => $post_type['show_in_admin_bar'],
					'show_in_nav_menus'         => $post_type['show_in_nav_menus'],
					'can_export'                => $post_type['can_export'],
					'has_archive'               => true,
					'exclude_from_search'       => false,
					'publicly_queryable'        => true,
					'capability_type'           => 'post'
				)
            );
        }
	}

		public function storeCustomTaxonomies()
	{
			$labels = array(
				'name'              => 'Property Status',
				'singular_name'     => 'Property Status',
				'search_items'      => 'Search ' . 'Property Status',
				'all_items'         => 'All ' . 'Property Status',
				'parent_item'       => 'Parent ' . 'Property Status',
				'parent_item_colon' => 'Parent ' . 'Property Status' . ':',
				'edit_item'         => 'Edit ' . 'Property Status',
				'update_item'       => 'Update ' . 'Property Status',
				'add_new_item'      => 'Add New ' . 'Property Status',
				'new_item_name'     => 'New ' . 'Property Status' . ' Name',
				'menu_name'         => 'Property Status',
			);
			$args = array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
                'query_var'         => true,
                //find out slug name Property Status
				'rewrite'           => array( 'slug' => 'propertyStatus'),
			);
		// register the taxonomy
		register_taxonomy( 'propertyStatus', array( 'property' ), $args );
		wp_insert_term( 'Sale', 'propertyStatus', array( 'slug' => 'Sale') );
		wp_insert_term('Rent', 'propertyStatus', array( 'slug' => 'Rent') );

	}

		function addMetaBox() {
		  add_meta_box( 'property-meta-box-id', 'Property information', array($this, 'metaBoxCallBack'), 'property', 'normal', 'high' );
		}
	

		function metaBoxCallBack( $post )
		{
			wp_nonce_field('saveMetaBoxData', 'meta_data_box_nonce');

			$priceValue = get_post_meta( $post->id, 'price_value_key', true );
			$locationValue = get_post_meta( $post->id, 'location_value_key', true );
			$dateValue = get_post_meta( $post->id, 'date_value_key', true );

			
			echo '<label for="price_field"> Property Price </label>';
			echo '<input type="number" class="meta-box" id="price_value_key" name="price_field" size="25" value=" '. esc_attr( $priceValue) .'" /><br>';

			echo '<label for="location_field"> Location </label>';
			echo '<input type="text" class="meta-box" id="location_value_key" name="location_field" size="25" value=" ' . esc_attr( $locationValue ) . '" /><br>';

			echo '<label for="date_field"> Date of construction </label>';
			echo '<input type="date" class="date-meta-box" id="date_value_key" name="date_field" size="25"  value=" '. esc_attr( $dateValue ) . '" /><br>';
			

		}

		public function saveMetaBoxData ( $post_id )
		{
			$post_type = get_post_type($post_id);

			if ( ! isset( $_POST['meta_data_box_nonce'] ) ){
				return;
			}

			if ( ! wp_verify_nonce( $_POST['meta_data_box_nonce'], 'saveMetaBoxData' ) ){
				return;
			}

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			
			$priceData = $_POST[ "price_field" ];

			//save name of field here
			if ( ! isset( $_POST['price_field'])) {
				update_post_meta( $post_type, 'price_field', sanitize_text_field( $_POST[ "price_field" ] ));
			} else if ( ! isset( $_POST['date_field'])){
				update_post_meta( $post_type, 'date_field', sanitize_text_field($_POST[ "date_field" ] ) );
			} else if ( ! isset( $_POST['location_field'])) {
				update_post_meta( $post_type, 'location_field', sanitize_text_field($_POST[ "location_field" ] ) );
			}

		}

			function propertyCustomColumns( $column, $post_id )
				{
					global $post;

					switch( $column ){
						case 'price' :
						$price = get_post_meta( $post_id, 'price_value_key', true );
						echo $price;
							break;
						case 'location' :
						$location = get_post_meta( $post_id, 'location_value_key', true );
						echo '<p>'.$location.'</p>';
							break;
						case 'doc' :
						$date = get_post_meta( $post_id, 'date_value_key', true );
						echo '<p>'.$date.'</p>';
							break;
					}
				}

		


	

		

}
	
