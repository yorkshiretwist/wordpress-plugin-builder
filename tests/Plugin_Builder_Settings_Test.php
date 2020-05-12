<?php
class Plugin_Builder_Settings_Test extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		
		if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
			define( 'WP_PLUGIN_DIR', 'WP_PLUGIN_DIR' );
		}
		
		require_once( 'mock-wordpress.php' );
		require_once( '../trunk/includes/class-plugin-builder-settings.php' );
		require_once( '../trunk/includes/class-plugin-builder-cpt-settings.php' );
		require_once( '../trunk/includes/class-plugin-builder-class-settings.php' );
		require_once( '../trunk/includes/global-functions.php' );
		
	}
	
	/**
	 * Tests the set_defaults() method sets the correct default values.
	 *
	 * @since    1.0.0
	 */
	public function test_set_defaults() {
		
		global $current_user;
		
        // arrange
        $settings = new Plugin_Builder_Settings();

        // act
        $settings->set_defaults();

        // assert
		$this->assertEquals( '', $settings->plugin_name );
		$this->assertEquals( '', $settings->plugin_slug );
		$this->assertEquals( '', $settings->plugin_package_name );
		$this->assertEquals( $current_user->user_url, $settings->plugin_uri );
		$this->assertEquals( '', $settings->description );
		$this->assertEquals( '1.0.0', $settings->version );
		$this->assertEquals( $current_user->display_name, $settings->author );
		$this->assertEquals( $current_user->user_email, $settings->author_email );
		$this->assertEquals( $current_user->user_url, $settings->author_uri );
		$this->assertEquals( 'GPL-2.0+', $settings->license );
		$this->assertEquals( 'http://www.gnu.org/licenses/gpl-2.0.txt', $settings->license_uri );
		$this->assertEquals( '', $settings->text_domain );
		$this->assertEquals( '/languages', $settings->domain_path );
		$this->assertEquals( false, $settings->renew_cached_includes );
		$this->assertEquals( array(), $settings->selected_includes );
		$this->assertEquals( array(), $settings->custom_post_types );
		$this->assertEquals( array(), $settings->classes );
		$this->assertEquals( false, $settings->renew_cached_includes );
		
    }
	
	/**
	 * Tests the set_from_form() method sets the correct values and is valid when no collections (Custom Post Types, custom classes or includes) are given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_collections() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( true, $settings->is_valid() );
		$this->assertEquals( 0, count( $settings->errors ) );
		
		$this->assertEquals( 'plugin_name', $settings->plugin_name );
		$this->assertEquals( 'plugin_slug', $settings->plugin_slug );
		$this->assertEquals( 'plugin_package_name', $settings->plugin_package_name );
		$this->assertEquals( 'http://pluginuri.com', $settings->plugin_uri );
		$this->assertEquals( 'plugin_description', $settings->description );
		$this->assertEquals( 'plugin_version', $settings->version );
		$this->assertEquals( 'plugin_author', $settings->author );
		$this->assertEquals( 'plugin@authoremail.com', $settings->author_email );
		$this->assertEquals( 'http://pluginauthoruri.com', $settings->author_uri );
		$this->assertEquals( 'plugin_license', $settings->license );
		$this->assertEquals( 'plugin_license_uri', $settings->license_uri );
		$this->assertEquals( 'plugin_text_domain', $settings->text_domain );
		$this->assertEquals( 'plugin_domain_path', $settings->domain_path );
		$this->assertEquals( true, $settings->renew_cached_includes );
		$this->assertEquals( true, $settings->is_valid() );
		$this->assertEquals( true, $settings->renew_cached_includes );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the correct values and is valid when collections (Custom Post Types, custom classes or includes) are given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_with_collections() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';
		
		$form['includes'] = array();
		$form['includes'][] = 'include1';
		$form['includes'][] = 'include2';
		
		$form['cpt_name'] = array();
		$form['cpt_name'][] = 'cpt_name1';
		$form['cpt_name'][] = 'cpt_name2';
		$form['cpt_description'] = array();
		$form['cpt_description'][] = 'cpt_description1';
		$form['cpt_description'][] = 'cpt_description2';
		$form['cpt_slug'] = array();
		$form['cpt_slug'][] = 'cpt_slug1';
		$form['cpt_slug'][] = 'cpt_slug2';
		$form['cpt_register_method'] = array();
		$form['cpt_register_method'][] = 'cpt_register_method1';
		$form['cpt_register_method'][] = 'cpt_register_method2';
		$form['cpt_create_manager'] = array();
		$form['cpt_create_manager'][] = '';
		$form['cpt_create_manager'][] = '1';
		
		$form['class_name'] = array();
		$form['class_name'][] = 'class_name1';
		$form['class_name'][] = 'class_name2';
		$form['class_description'] = array();
		$form['class_description'][] = 'class_description1';
		$form['class_description'][] = 'class_description2';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( true, $settings->is_valid() );
		$this->assertEquals( 0, count( $settings->errors ) );
		
		$this->assertEquals( 2, count( $settings->includes ) );
		$this->assertEquals( 'include1', $settings->includes[0] );
		$this->assertEquals( 'include2', $settings->includes[1] );
		
		$this->assertEquals( 2, count( $settings->custom_post_types ) );
		$this->assertEquals( 'cpt_name1', $settings->custom_post_types[0]->name );
		$this->assertEquals( 'cpt_name2', $settings->custom_post_types[1]->name );
		$this->assertEquals( 'cpt_description1', $settings->custom_post_types[0]->description );
		$this->assertEquals( 'cpt_description2', $settings->custom_post_types[1]->description );
		$this->assertNotEmpty( $settings->custom_post_types[0]->slug );
		$this->assertNotEmpty( $settings->custom_post_types[1]->slug );
		$this->assertEquals( 'cpt_register_method1', $settings->custom_post_types[0]->register_method );
		$this->assertEquals( 'cpt_register_method2', $settings->custom_post_types[1]->register_method );
		$this->assertEquals( false, $settings->custom_post_types[0]->create_manager );
		$this->assertEquals( true, $settings->custom_post_types[1]->create_manager );
		
		$this->assertEquals( 2, count( $settings->classes ) );
		$this->assertEquals( 'class_name1', $settings->classes[0]->name );
		$this->assertEquals( 'class_name2', $settings->classes[1]->name );
		$this->assertEquals( 'class_description1', $settings->classes[0]->description );
		$this->assertEquals( 'class_description2', $settings->classes[1]->description );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when no name is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_plugin_name() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = '';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when no slug is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_plugin_slug() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = '';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when no package name is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_plugin_package_name() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = '';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when no plugin URI is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_plugin_uri() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = '';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when an invalid plugin URI is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_invalid_plugin_uri() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'NOT A URI';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when no author name is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_plugin_author() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = '';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when an invalid author email is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_invalid_plugin_author_email() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'NOT AN EMAIL';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when an invalid author URI is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_invalid_plugin_author_uri() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'NOT A URI';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when no text domain is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_plugin_text_domain() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = '';
		$form['plugin_domain_path'] = 'plugin_domain_path';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
	/**
	 * Tests the set_from_form() method sets the settings as invalid when not domain path is given.
	 *
	 * @since    1.0.0
	 */
	public function test_set_from_form_no_plugin_domain_path() {
		
		global $current_user;
		
		// arrange
        $settings = new Plugin_Builder_Settings();
		$form = array();
		$form['plugin_name'] = 'plugin_name';
		$form['plugin_slug'] = 'plugin_slug';
		$form['plugin_package_name'] = 'plugin_package_name';
		$form['plugin_uri'] = 'http://pluginuri.com';
		$form['plugin_description'] = 'plugin_description';
		$form['plugin_version'] = 'plugin_version';
		$form['plugin_author'] = 'plugin_author';
		$form['plugin_author_email'] = 'plugin@authoremail.com';
		$form['plugin_author_uri'] = 'http://pluginauthoruri.com';
		$form['plugin_license'] = 'plugin_license';
		$form['plugin_license_uri'] = 'plugin_license_uri';
		$form['plugin_text_domain'] = 'plugin_text_domain';
		$form['plugin_domain_path'] = '';
		$form['renew_cached_includes'] = '1';

        // act
        $settings->set_from_form( $form );
		
		// assert
		$this->assertEquals( false, $settings->is_valid() );
		$this->assertEquals( 1, count( $settings->errors ) );
		
	}
	
}