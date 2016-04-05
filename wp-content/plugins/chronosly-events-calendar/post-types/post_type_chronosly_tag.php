<?php
if(!class_exists('Post_Type_Chronosly_Tag'))
{
	/**
	 * A PostTypeTemplate class that provides 3 additional meta fields
	 */
	class Post_Type_Chronosly_Tag
	{
		const POST_TYPE	= "chronosly_tag";
		
		
    	/**
    	 * The Constructor
    	 */
    	public function __construct()
    	{
    		// register actions
    		add_action('init', array(&$this, 'init'));
    		add_action('admin_init', array(&$this, 'admin_init'));
    	} // END public function __construct()

    	/**
    	 * hook into WP's init action hook
    	 */
    	public function init()
    	{
    		// Initialize Post Type
    		$this->create_post_type();
    		add_action('save_post', array(&$this, 'save_post'));
    	} // END public function init()

    	/**
    	 * Create the post type
    	 */
    	public function create_post_type()
    	{
            global $Post_Type_Chronosly;
            $slug = "chronosly-tag";
            if($Post_Type_Chronosly->settings['chronosly-tag-slug']) $slug = $Post_Type_Chronosly->settings['chronosly-tag-slug'];
    		register_taxonomy(self::POST_TYPE,array("chronosly"),
    			array(
    				'labels' => array(
    					'name' => __("Event Tags", "chronosly"),
    					'singular_name' => __("Event Tag", "chronosly"),
						'add_new' =>  __("Add new tag", "chronosly"),
						'add_new_item' =>  __("Add new tag", "chronosly"),
						'view_item' =>  __("View tag", "chronosly"),
						

    				),
					'hierarchical' => false,
                    'rewrite' => array('slug' => $slug, 'with_front' => false, 'feeds' => true),
    				'public' => true,
					'capability' => 'chronosly_author',
                    "show_tagcloud" => 1,
    				
    			)
    		);
            if(isset($Post_Type_Chronosly->settings['chronosly-allow-flush']) and !$Post_Type_Chronosly->settings['chronosly-tags-flushed']) {
                flush_rewrite_rules();
                $Post_Type_Chronosly->settings['chronosly-tags-flushed'] = 1;
                update_option('chronosly-settings', serialize($Post_Type_Chronosly->settings));
            
            }            /*add_filter( 'map_meta_cap', array("Post_Type_Chronosly",'chronosly_map_meta_cap'), 10, 4 );
            add_filter( 'template_include', array("Post_Type_Chronosly",'chronosly_templates') );*/
        }
	
    	/**
    	 * Save the metaboxes for this custom post type
    	 */
    	public function save_post($post_id)
    	{
            // verify if this is an auto save routine. 
            // If it is our form has not been submitted, so we dont want to do anything
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }
            // handle the case when the custom post is quick edited
            // otherwise all custom meta fields are cleared out
            if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce'))
                return;

            if(isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    		{
                Chronosly_Cache::delete_item($post_id);
                foreach($this->_meta as $field_name)
    			{
    				// Update the post's meta field
    				update_post_meta($post_id, $field_name, $_POST[$field_name]);
    			}
    		}
    		else
    		{
    			return;
    		} // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    	} // END public function save_post($post_id)

    	/**
    	 * hook into WP's admin_init action hook
    	 */
    	public function admin_init()
    	{			
    		// Add metaboxes
    		add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
    	} // END public function admin_init()
			
    	/**
    	 * hook into WP's add_meta_boxes action hook
    	 */
    	public function add_meta_boxes()
    	{
    		// Add this metabox to every selected post
    		/*add_meta_box( 
    			sprintf('chronosly_%s_section', self::POST_TYPE),
    			sprintf('%s Information', ucwords(str_replace("_", " ", self::POST_TYPE))),
    			array(&$this, 'add_inner_meta_boxes'),
    			self::POST_TYPE
    	    );	*/				
    	} // END public function add_meta_boxes()

		/**
		 * called off of the add meta box
		 */		
		public function add_inner_meta_boxes($post)
		{		
			// Render the job order metabox
			//include(sprintf("%s/../templates/%s.php", dirname(__FILE__), self::POST_TYPE));
		} // END public function add_inner_meta_boxes($post)

	} // END class Post_Type_Template
} // END if(!class_exists('Post_Type_Template'))
