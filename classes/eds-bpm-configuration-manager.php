<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
include_once EDS_BPM_Loader::$abs_path. '/classes/eds-bpm-config.php'; 


if(!class_exists("EDS_BPM_Configuration_Manager")){
class EDS_BPM_Configuration_Manager{
	
	private static $config = null ;

	private $general_config_key;
	private $advanced_config_key;
	private $slug;

	private $plugin_config_tabs;

	private $general_config ;
	private $advanced_config;

	private function __construct() {
		
		$this->slug = EDS_BPM_Config::$eds_bpm_cofig_menu_slug;
		
		$this->general_config_key = EDS_BPM_Config::$general_config_key;
		$this->advanced_config_key = EDS_BPM_Config::$advanced_config_key;		

		$this->plugin_config_tabs = array();
		$this->general_config = array();
		$this->advanced_config = array();
	}
	
	public static function get_instance(){
		if(NULL == self::$config){
			self::$config = new EDS_BPM_Configuration_Manager();
		}
		return self::$config;
	}
	
	public function get_slug()
	{
		return $this->slug;
	}
	
	public function get_general_config_key()
	{
		return $this->general_config_key;
	}
	
	public function get_advanced_config_key()
	{
		return $this->advanced_config_key;
	}
	
	public function load_configuration(){
		
		$this->general_config = EDS_BPM_Config::get_general_config();
		$this->advanced_config = EDS_BPM_Config::get_advanced_config();	
		
	}


	public function register_general_configuration(){

		$this->plugin_config_tabs[$this->general_config_key] = __('General', 'eds-bpm');
		
		add_settings_section( EDS_BPM_Config::$general_section,
							__('General Settings','eds-bpm'), 
							array( $this, 'section_general_desc' ),
							$this->general_config_key );
			
		add_settings_field( 'behance_api_key',
							__('Behance API Key','eds-bpm'), 
							array( $this, 'field_behance_api_key' ),
							$this->general_config_key,
							EDS_BPM_Config::$general_section);
	
		add_settings_field( 'result_per_page',
							__('Results per Page in Backend','eds-bpm'), 
							array( $this, 'field_result_per_page' ),
							$this->general_config_key,
							EDS_BPM_Config::$general_section);
							
		add_settings_field( 'enable_pretty_url',
							__('Enable Pretty URL','eds-bpm'), 
							array( $this, 'field_enable_pretty_url' ),
							$this->general_config_key,
							EDS_BPM_Config::$general_section);
		
		
		
		
		register_setting( $this->general_config_key, $this->general_config_key, array($this, 'sanitize_general_settings'));

	}

	public function section_general_desc() { 
		echo __('General configurations related to Portfolio Manager.', 'eds-bpm'); 
	}
	
	public function field_behance_api_key() {
		
		$html= '<input
			type="text"
			name="'.$this->general_config_key.'[behance_api_key]"
			value="'.esc_attr(isset($this->general_config['behance_api_key'])?$this->general_config['behance_api_key']:'').'" />';
		
		echo $html;
	}
	
	public function field_result_per_page(){
		$html= '<input
			type="text"
			name="'.$this->general_config_key.'[result_per_page]"
			value="'.esc_attr(isset($this->general_config['result_per_page'])?$this->general_config['result_per_page']:'').'" />';
		
		echo $html;
	}
	
	public function field_enable_pretty_url(){
		$value = isset($this->general_config['enable_pretty_url'])?$this->general_config['enable_pretty_url']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input			
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->general_config_key.'[enable_pretty_url]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
		
	}
	
	public function sanitize_general_settings($input){
		$input['behance_api_key'] = trim($input['behance_api_key']);
		$input['result_per_page'] = absint(trim($input['result_per_page']));
		return $input;
	}	
	
	
	
	public function register_advanced_configuration(){
		$this->plugin_config_tabs[$this->advanced_config_key] = __('Advanced', 'eds-bpm');
	
		add_settings_section( EDS_BPM_Config::$advanced_section, 
							__('Advanced Plugin Settings', 'eds-bpm'), 
							array( $this, 'section_advanced_desc' ), 
							$this->advanced_config_key );
							
		add_settings_field( 'project_background_color', 
							__('Project Background Color'), 
							array( $this, 'field_project_background_color' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
							
		add_settings_field( 'show_project_title', 
							__('Show Project Title'), 
							array( $this, 'field_show_project_title' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
		
		add_settings_field( 'show_creative_fields', 
							__('Show Creative Fields'), 
							array( $this, 'field_show_creative_fields' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
		
		add_settings_field( 'show_project_by', 
							__('Show Project By'), 
							array( $this, 'field_show_project_by' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
	
		add_settings_field( 'show_about_project', 
							__('Show About Project'), 
							array( $this, 'field_show_about_project' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );

		add_settings_field( 'show_publish_date', 
							__('Show Publish Date'), 
							array( $this, 'field_show_publish_date' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
		
		add_settings_field( 'show_views', 
							__('Show Total Project Views'), 
							array( $this, 'field_show_views' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
							
		add_settings_field( 'show_appreciations', 
							__('Show Total Project Appreciations'), 
							array( $this, 'field_show_appreciations' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );		
							
		add_settings_field( 'show_comments', 
							__('Show Total Project Comments'), 
							array( $this, 'field_show_comments' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );	
		
		add_settings_field( 'show_tags', 
							__('Show Project Tags'), 
							array( $this, 'field_show_tags' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
		
		add_settings_field( 'show_tools_used', 
							__('Show Project Tools Used'), 
							array( $this, 'field_show_tools_used' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
		
		add_settings_field( 'show_copyright_info', 
							__('Show Project Copyright Info'), 
							array( $this, 'field_show_copyright_info' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
		
		add_settings_field( 'eds_bpm_custom_css', 
							__('Custom CSS'), 
							array( $this, 'field_show_eds_bpm_custom_css' ), 
							$this->advanced_config_key, 
							EDS_BPM_Config::$advanced_section );
		
							
		register_setting( $this->advanced_config_key, $this->advanced_config_key);
	}

	public function section_advanced_desc() { 
		echo 'Advanced settings.'; 
	}

	public function field_project_background_color() {
		$html= '<input
			type="text"
			id ="project_background_color" 
			name="'.$this->advanced_config_key.'[project_background_color]"
			value="'.esc_attr( isset($this->advanced_config['project_background_color'])?$this->advanced_config['project_background_color']:'').'" />';		
		
		echo $html;
		
	}	
	public function field_show_project_title(){
		
		$value = isset($this->advanced_config['show_project_title'])?$this->advanced_config['show_project_title']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input			
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_project_title]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	
	public function field_show_creative_fields(){
		
		$value = isset($this->advanced_config['show_creative_fields'])?$this->advanced_config['show_creative_fields']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_creative_fields]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	public function field_show_project_by(){
		
		$value = isset($this->advanced_config['show_project_by'])?$this->advanced_config['show_project_by']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_project_by]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	public function field_show_about_project(){
		
		$value = isset($this->advanced_config['show_about_project'])?$this->advanced_config['show_about_project']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_about_project]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	public function field_show_publish_date(){		
		$value = isset($this->advanced_config['show_publish_date'])?$this->advanced_config['show_publish_date']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_publish_date]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	public function field_show_views(){
		
		$value = isset($this->advanced_config['show_views'])?$this->advanced_config['show_views']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_views]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	public function field_show_appreciations(){
		
		$value = isset($this->advanced_config['show_appreciations'])?$this->advanced_config['show_appreciations']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_appreciations]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	public function field_show_comments(){
		
		$value = isset($this->advanced_config['show_comments'])?$this->advanced_config['show_comments']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_comments]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
		
	public function field_show_tags(){
		
		$value = isset($this->advanced_config['show_tags'])?$this->advanced_config['show_tags']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_tags]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	
	
	public function field_show_tools_used(){
		
		$value = isset($this->advanced_config['show_tools_used'])?$this->advanced_config['show_tools_used']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_tools_used]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	public function field_show_copyright_info(){
		
		$value = isset($this->advanced_config['show_copyright_info'])?$this->advanced_config['show_copyright_info']:'';
		$checked = (isset($value) && $value=='yes')?'checked':'';
		$html= '<input
			 data-on-text="YES"
			 data-off-text="NO"
			type="checkbox"			
			class="project_attribute_switches" 
			name="'.$this->advanced_config_key.'[show_copyright_info]"
			value="yes" '.$checked.' 
			 />';
		
		echo $html;
	}
	
	function field_show_eds_bpm_custom_css(){
		$value = isset($this->advanced_config['eds_bpm_custom_css'])?$this->advanced_config['eds_bpm_custom_css']:'';		
		
		$html ='<textarea
					name ="'.$this->advanced_config_key.'[eds_bpm_custom_css]"
					rows ="5"										
				>'.$value.'</textarea>';	
		echo $html;
	}
	
	
	public function init_configuration_page()
	{		
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_config_key;
	    ?>
	    <h2>Portfolio Manager - Settings</h2>
	    <div class="wrap">
	        <?php $this->init_configuration_tab(); ?>
	        <form method="post" action="options.php">
	            <?php wp_nonce_field( 'update-options' ); ?>
	            <?php settings_fields( $tab ); ?>
	            <?php do_settings_sections( $tab ); ?>
	            <?php submit_button(); ?>
	        </form>
	    </div>
	    <?php
	}
	
	
	public function init_configuration_tab(){
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_config_key;

	    screen_icon();
	    
	    echo '<h2 class="nav-tab-wrapper">';
	    foreach ( $this->plugin_config_tabs as $tab_key => $tab_caption ) {
	        $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
	        echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
	    }
	    echo '</h2>';
	}
	
}
}