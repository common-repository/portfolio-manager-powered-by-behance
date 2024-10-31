<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(!class_exists("EDS_BPM_Config")){
class EDS_BPM_Config{
	
	public static $project_table = "bpm_projects";
	public static $category_table = "bpm_categories";
	
	public static $eds_bpm_top_menu_slug = "eds-bpm-top-menu";
	public static $eds_bpm_new_project_slug = "eds-bpm-new-project";
	public static $eds_bpm_category_menu_slug = "eds-bpm-cat-menu";
	
	public static $eds_bpm_cofig_menu_slug = "eds-bpm-config-menu";
	
	public static $general_config_key = "eds-bpm-general-config";
	public static $advanced_config_key = "eds-bpm-advanced-config";
	public static $general_section = "eds-bpm-general-section";
	public static $advanced_section = "eds-bpm-advanced-section";
	
	public static $result_per_page = 10;
	
	public static $advanced_config = null;
	public static $general_config = null;
	
	public static function get_advanced_config(){
		if(self::$advanced_config ==null){
			self::$advanced_config = array();
				
			if(get_option( self::$advanced_config_key ) === false){			
			    self::$advanced_config = array_merge( array(
			        'project_background_color' => '#f1f1f1',			    	
			    	'show_project_title' => 'yes',
			    	'show_creative_fields' =>'yes',
			    	'show_project_by' => 'yes',
			    	'show_about_project' => 'yes',
			    	'show_publish_date' => 'yes',
			    	'show_views' => 'yes',
			    	'show_appreciations' => 'yes',
			    	'show_comments' => 'yes',			    	
			    	'show_tags' => 'yes',
			    	'show_tools_used' => 'yes',    	
			    	'show_copyright_info' => 'yes',
			    	'eds_bpm_custom_css' => ''		    	
			        ), self::$advanced_config ); 
			}else
				self::$advanced_config = (array) get_option( EDS_BPM_Config::$advanced_config_key);
		}
				
		return self::$advanced_config;		
	}
	
	public static function get_general_config(){
		if(self::$general_config ==null){
			
			self::$general_config = array();
						
			if(get_option( self::$general_config_key ) === false){
				$flag ="";
				if(get_option('permalink_structure'))
					$flag = 'yes';
								
				self::$general_config = array_merge( array(
			        'behance_api_key' => '',			    	
			    	'result_per_page' => self::$result_per_page,
			    	'enable_pretty_url' => $flag			    			    	
			        ), self::$general_config );
			}else{
				self::$general_config = (array) get_option( EDS_BPM_Config::$general_config_key);
			}
				
		}
		
		return self::$general_config;
	}
	
	
	public static function get_current_page_url() {
	 	$pageURL = 'http';
	 	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 	$pageURL .= "://";
	 	if ($_SERVER["SERVER_PORT"] != "80") {
	  		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 	} else {
	  		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 	}
	 	return $pageURL;
	}
	
	public static function trim_all( $str , $what = NULL , $with = ' ' )
	{
	    if( $what === NULL )
	    {
	        //  Character      Decimal      Use
	        //  "\0"            0           Null Character
	        //  "\t"            9           Tab
	        //  "\n"           10           New line
	        //  "\x0B"         11           Vertical Tab
	        //  "\r"           13           New Line in Mac
	        //  " "            32           Space
	       
	        $what   = "\\x00-\\x20";    //all white-spaces and control chars
	    }
	   
	    return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}
}
}