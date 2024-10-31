<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once EDS_BPM_Loader::$abs_path. '/classes/eds-bpm-config.php';

if(!class_exists("EDS_BPM_DB")){
class EDS_BPM_DB{
	
	public function create_category_table(){
		//Creating the category table on activation of plugin
		global $wpdb;
		$table_name = $wpdb->prefix . EDS_BPM_Config::$category_table; 
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
			$sql = "CREATE TABLE $table_name (
					  `id` integer(10) UNSIGNED NOT NULL auto_increment,
					  `name` varchar(255) NOT NULL DEFAULT '',
					  `slug` varchar(255) NOT NULL DEFAULT '',
					  `icon` varchar(255) DEFAULT '',					  
					  `description` text DEFAULT '',				  			  				
					  `status` varchar(20) NOT NULL DEFAULT 'unpublished',	  
					  `doc` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',					  
					  
					  PRIMARY KEY (`id`),
					  UNIQUE `idx_slug` (`slug`),
					  KEY `idx_status` (`status`),
					  KEY `idx_doc` (`doc`)   					  					     
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Portfolio Manager - Powered by Behance Categories' AUTO_INCREMENT=0;";
			//reference to upgrade.php file
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}
	
	public function create_project_table(){
		//Creating the project table on actiavation of plugin
		global $wpdb;
		$project_table_name = $wpdb->prefix. EDS_BPM_Config::$project_table;
		$category_table_name = $wpdb->prefix. EDS_BPM_Config::$category_table;
		if($wpdb->get_var("SHOW TABLES LIKE '$project_table_name'") != $project_table_name)
		{
			$sql = "CREATE TABLE $project_table_name (
					  	`id` integer(10) UNSIGNED NOT NULL auto_increment,
					  	`catid` integer NOT NULL default '0',
					  	`slug` varchar (255) NOT NULL DEFAULT '',
					  	`b_project_id`  varchar(20) NOT NULL DEFAULT '',
					  	`b_project_name` varchar (255) NOT NULL DEFAULT '',					  	
 					   	`b_project_thumb` varchar (255) NOT NULL DEFAULT '',
 						`b_creative_fields` varchar(255) NOT NULL DEFAULT '',
 						`b_create_date` datetime NOT NULL default '0000-00-00 00:00:00',
 						`b_modified_timestamp` integer NOT NULL default '0',
 						`params` text NOT NULL default '',					  				  			  				
					  	`status` varchar(20) NOT NULL DEFAULT 'unpublished',
					  	`featured` tinyint NOT NULL default '0',	  
					  	`doc` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',					  
					  	PRIMARY KEY (`id`),
					  	UNIQUE `idx_slug` (`slug`),					  	
					  	KEY `idx_b_prjct_id` (`b_project_id`),					  	
   						KEY `idx_status` (`status`),
   						KEY `idx_catid` (`catid`),
					  	KEY `idx_doc` (`doc`)					  	   					  					     
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Portfolio Manager - Powered by Behance Projects' AUTO_INCREMENT=0;";
			//reference to upgrade.php file
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
		
	}
	
	public function create_default_category(){
		try{
			global $wpdb;
			
			$table_name = $wpdb->prefix . EDS_BPM_Config::$category_table;
			
			$row = $wpdb->get_row("SELECT * FROM $table_name WHERE slug='default'", OBJECT, 0);
			
			if($row != NULL)
				return;
							
			$default_cat_icon = plugin_dir_url(__FILE__).'../images/default-category-icon.jpg';  
						 
			$wpdb->insert
			(
				$table_name,
				array(
				            'name' => 'Default',
							'slug' => 'default', 
				            'icon' => $default_cat_icon,
				        	'description' => 'Default Category',
				        	'status' => 'published',
				        	'doc' =>  date("Y-m-d H:i:s")		        	
				),
				array(
				            '%s', 
				            '%s',
							'%s',
				        	'%s',
				        	'%s',
			        		'%s'			        	
				)
			);
		
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	public function get_categories($filters){
		try{
			global $wpdb;

			$response = new stdClass();

			$queries = $this->get_category_query($filters);

			$response->rows = $wpdb->get_results( $queries->category_query ,OBJECT );
			$response->total_rows = ($wpdb->get_col($queries->count_query, 0));

			return $response;
		}catch(Exception $e){
			return null;
		}
	}
	
	private function get_category_query($filters){
		try{
			global $wpdb;

			$counter = 0;
			$where_clause = array();

			$category_table = $wpdb->prefix .EDS_BPM_Config::$category_table;

			$query = "SELECT * FROM `$category_table` ";
			$count_query = "SELECT count(*) as total FROM `$category_table` ";

			if($filters->get_filter_category()!=null && $filters->get_filter_category()!=-1)
				$where_clause[$counter++] = " name LIKE ('%" . $filters->get_filter_category() . "%')";

			if($filters->get_filter_status()!=null)
			$where_clause[$counter++] = " status = '" . $filters->get_filter_status() . "'";
			else
			$where_clause[$counter++] = " status != 'deleted'";			

			$order_by_clause = " ORDER BY " . $filters->get_order_by(). " " . $filters->get_ordering();

			$limit_clause = " LIMIT ".(($filters->get_page_number() - 1) * intval(EDS_BPM_Config::$result_per_page)).",". EDS_BPM_Config::$result_per_page;

			if($counter != 0)
			{
				$query .= " WHERE " . implode(' AND ', $where_clause);
				$count_query .= " WHERE " . implode(' AND ', $where_clause);
			}

			$query .= $order_by_clause;
			$query .= $limit_clause;

			$queries = new stdClass();

			$queries->category_query = $query;
			$queries->count_query = $count_query;


			return $queries;

		}catch(Exception $e){
			return null;
		}
	}
	
	public function get_category_details($cat_id){
		try{
			global $wpdb;
			$category_table = $wpdb->prefix . EDS_BPM_Config::$category_table;		
			
			$query = $wpdb->prepare("SELECT * FROM `$category_table` WHERE id = %d" , $cat_id);
				
			$category = $wpdb->get_row($query, OBJECT);
				
			return $category;			
			
		}catch(Exception $e){
			return null;
		}
	}
	
	
	public function save_category(){
		try{
			global $wpdb;
			$category_table = $wpdb->prefix . EDS_BPM_Config::$category_table;
						
			$category_slug = $this->get_slug("category",$_REQUEST['cat-name']);
			
			$response = true;	
			
			// Adding/Updating the Category Table 
			$id = intval($_REQUEST['bpm-id']);	
								
			if($id == 0 ){
				$response = $wpdb->insert(
				$category_table,
				array(
					            'name' => $_REQUEST['cat-name'],
								'slug' => $category_slug,
								'icon' => $_REQUEST['cat-icon'],								
								'description' => $_REQUEST['cat-desc'],				        	
					        	'status' => 'published',				        	  	
					        	'doc' => date("Y-m-d H:i:s")         
				),
				array(
					            '%s',
								'%s',
								'%s',
								'%s',
					        	'%s',								
					        	'%s'				        					        	
				));
			}
			else{
				$response = $wpdb->update(
				$category_table,
				array(
					            'name' => $_REQUEST['cat-name'],
								'slug' => $category_slug,
								'icon' => $_REQUEST['cat-icon'],								
								'description' => $_REQUEST['cat-desc'],				        	  
				),
				array(
								'id' => $id
				),
				array(					           
					        	'%s',
								'%s',
								'%s',
								'%s'					        				        	
				),
				array( '%d'));
					        		
			}
		
			return true;				
				
		}catch(Exception $e){
			return false;
		}
	}
	
	private function get_slug($type , $text){
		try{
			global $wpdb;
			$table = '';
			switch($type){
				case "category":
					$table = $wpdb->prefix . EDS_BPM_Config::$category_table;				
				break;
			
				case "project":
					$table = $wpdb->prefix . EDS_BPM_Config::$project_table;
				break;
			}
			 
			//$name = strtolower(trim($text));
			//$slug = str_replace(" ","-", $name);
			$slug = $this->convert_to_slug($text);
			$new_slug = $slug;
			
			$count_query = "SELECT count(*) as total FROM `$table` WHERE slug ='$slug'";		
			$total_rows = ($wpdb->get_col($count_query, 0));						
			$counter = 1;						
			while($total_rows[0]){
				$new_slug = $slug.'-'.$counter++;				
				$count_query = "SELECT count(*) as total FROM `$table` WHERE slug ='$new_slug'";			
				$total_rows = ($wpdb->get_col($count_query, 0));
			}	

			return $new_slug;
		}catch(Exception $e){
			return null;
		}
	}
	
	private function convert_to_slug($str, $replace=array(), $delimiter='-'){
		setlocale(LC_ALL, 'en_US.UTF8');
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
		$str = urldecode($str);
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("%[^-/+|\w ]%", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;		
	}
	
	
		
	public function publish_category(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$category_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("UPDATE `$tableName` SET status = 'published' WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	public function unpublish_category(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$category_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("UPDATE `$tableName` SET status = 'unpublished' WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	public function delete_category(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$category_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("UPDATE `$tableName` SET status = 'deleted' WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	
	public function trash_category(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$category_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("DELETE FROM `$tableName` WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}		
	}
	
	
	
	public function get_projects($filters){
		try{
			global $wpdb;

			$response = new stdClass();

			$queries = $this->get_project_query($filters);

			$response->rows = $wpdb->get_results( $queries->project_query ,OBJECT );
			$response->total_rows = ($wpdb->get_col($queries->count_query, 0));
			$response->category_list = $this->get_category_list('published');

			return $response;
		}catch(Exception $e){
			return null;
		}
	}
	
	private function get_project_query($filters){
		try{
			global $wpdb;

			$counter = 0;
			$where_clause = array();
			
			
			$project_table = $wpdb->prefix .EDS_BPM_Config::$project_table;
			$category_table = $wpdb->prefix .EDS_BPM_Config::$category_table;
								
			$query = "SELECT * FROM `$project_table` p INNER JOIN (SELECT id AS cat_id, name AS cat_name FROM `$category_table`) c ON p.catid = c.cat_id ";
			
			$count_query = "SELECT count(*) as total FROM `$project_table` p INNER JOIN (SELECT id AS cat_id, name AS cat_name FROM `$category_table`) c ON p.catid = c.cat_id ";

			if($filters->get_filter_pname()!=null && $filters->get_filter_pname()!='')
			{
				if(is_numeric(trim($filters->get_filter_pname())))
					$where_clause[$counter++] = " p.b_project_id = '" . trim($filters->get_filter_pname()) . "'";
				else
					$where_clause[$counter++] = " p.b_project_name LIKE ('%" . $filters->get_filter_pname() . "%')";
			}
				

			if($filters->get_filter_pcategory()!=null && $filters->get_filter_pcategory()!=-1)
				$where_clause[$counter++] = " c.cat_id = ". $filters->get_filter_pcategory();	
				
			if($filters->get_filter_pstatus()!=null)
			{
				if($filters->get_filter_pstatus()!='featured')
					$where_clause[$counter++] = " p.status = '" . $filters->get_filter_pstatus() . "'";
				else
					$where_clause[$counter++] = " p.featured = 1";
			}
			else
				$where_clause[$counter++] = " p.status != 'deleted'";			
				

			$order_by_clause = " ORDER BY " . $filters->get_order_by(). " " . $filters->get_ordering();

			$limit_clause = " LIMIT ".(($filters->get_page_number() - 1) * intval(EDS_BPM_Config::$result_per_page)).",". EDS_BPM_Config::$result_per_page;

			if($counter != 0)
			{
				$query .= " WHERE " . implode(' AND ', $where_clause);
				$count_query .= " WHERE " . implode(' AND ', $where_clause);
			}

			$query .= $order_by_clause;
			$query .= $limit_clause;

			$queries = new stdClass();

			$queries->project_query = $query;
			$queries->count_query = $count_query;


			return $queries;

		}catch(Exception $e){
			return null;
		}
	}
	
	
	public function get_category_list($status){
		try{
			global $wpdb;
			$category_table = $wpdb->prefix . EDS_BPM_Config::$category_table;
			
			$query = '';
			
			if($status != null)
				$query = $wpdb->prepare("SELECT * FROM `$category_table` WHERE status = %s" , $status);
			else 
				$query = $wpdb->prepare("SELECT * FROM `$category_table`");
				
			$category_list = $wpdb->get_results($query, OBJECT);
				
			return $category_list;			
			
		}catch(Exception $e){
			return null;
		}
	}
	
	public function save_project(){
		try{
			global $wpdb;
			$project_table = $wpdb->prefix . EDS_BPM_Config::$project_table;
						
			$project_slug = $this->get_slug("project", $_REQUEST["b_project_name"]);
			
			$response = true;	
			
			// Adding/Updating the Category Table 
			$id = intval($_REQUEST['bpm-id']);	
								
			if($id == 0 ){
				$response = $wpdb->insert(
				$project_table,
				array(
								'catid' => $_REQUEST['bpm-project-category'],
								'slug' => $project_slug,
					            'b_project_id' => $_REQUEST['b_project_id'],
								'b_project_name' => $_REQUEST['b_project_name'],
								'b_project_thumb' => $_REQUEST['b_project_thumb'],
								'b_creative_fields' => $_REQUEST['b_creative_fields'],								
								'b_create_date' => $_REQUEST['b_create_date'],
								'b_modified_timestamp' => $_REQUEST['b_modified_timestamp'],
								'params' => '',				        	
					        	'status' => $_REQUEST['bpm-project-status'],
								'featured' => 0,				        	  	
					        	'doc' => date("Y-m-d H:i:s")         
				),
				array(
					            '%d',
								'%s',
								'%s',
								'%s',
					        	'%s',								
					        	'%s',
					        	'%s',
								'%s',
								'%s',
					        	'%s',								
					        	'%d',
								'%s'				        					        	
				));
			}
			else{
				$response = $wpdb->update(
				$project_table,
				array(
					            'catid' => $_REQUEST['bpm-project-category'],
								'slug' => $project_slug,
					            'b_project_id' => $_REQUEST['b_project_id'],
								'b_project_name' => $_REQUEST['b_project_name'],
								'b_project_thumb' => $_REQUEST['b_project_thumb'],
								'b_creative_fields' => $_REQUEST['b_creative_fields'],								
								'b_create_date' => $_REQUEST['b_create_date'],
								'b_modified_timestamp' => $_REQUEST['b_modified_timestamp'],
								'params' => '',				        	
					        	'status' => $_REQUEST['bpm-project-status'],
								'featured' => 0					        					        	  
				),
				array(
								'id' => $id
				),
				array(					           
					        	'%d',
								'%s',
								'%s',
								'%s',
					        	'%s',								
					        	'%s',
					        	'%s',
								'%s',
								'%s',
					        	'%s',								
					        	'%d'													        				        	
				),
				array( '%d'));
					        		
			}
		
			return true;				
				
		}catch(Exception $e){
			return false;
		}
	}
	
	public function get_project_details($project_id){
		try{
			global $wpdb;
			$project_table = $wpdb->prefix . EDS_BPM_Config::$project_table;
			
			$query = $wpdb->prepare("SELECT * FROM `$project_table` WHERE id = %d" , $project_id);
				
			$project = $wpdb->get_row($query, OBJECT);
				
			return $project;			
			
		}catch(Exception $e){
			return null;
		}	
	}
	
	public function publish_project(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$project_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("UPDATE `$tableName` SET status = 'published' WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
		
	}
	
	public function unpublish_project(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$project_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("UPDATE `$tableName` SET status = 'unpublished' WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
		
	}
	
	public function set_project_featured($flag){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$project_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("UPDATE `$tableName` SET featured = $flag WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	public function delete_project(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$project_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("UPDATE `$tableName` SET status = 'deleted' WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	public function trash_project(){
		try{				
			global $wpdb;				
			$tableName = $wpdb->prefix . EDS_BPM_Config::$project_table;
							
			$ids = $_REQUEST['entries'];
			
			$wpdb->query("DELETE FROM `$tableName` WHERE id IN (".implode(",", $ids).")");
						
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	public function update_project($project, $b_pr_data){
		try{
			global $wpdb;
			$tableName = $wpdb->prefix . EDS_BPM_Config::$project_table;
			
			$b_project_thumb = '';
			if(isset($b_pr_data['covers']['404']) && trim($b_pr_data['covers']['404']) != '')
				$b_project_thumb = $b_pr_data['covers']['404'];
			else if (isset($b_pr_data['covers']['230']) && trim($b_pr_data['covers']['230']) != '')
				$b_project_thumb = $b_pr_data['covers']['230'];
			else if (isset($b_pr_data['covers']['202']) && trim($b_pr_data['covers']['202']) != '')
				$b_project_thumb = $b_pr_data['covers']['202'];
			else if (isset($b_pr_data['covers']['115']) && trim($b_pr_data['covers']['115']) != '')
				$b_project_thumb = $b_pr_data['covers']['115'];
			else 
				$b_project_thumb = plugin_dir_url(__FILE__).'../images/default-project-thumb.jpg';
				
			$b_fields ='';
			foreach ($b_pr_data['fields'] as $b_field){ 
				$b_fields = $b_fields. ', ' . $b_field;
			}
		
			
			if($project->b_modified_timestamp != $b_pr_data["modified_on"]){				
				$response = $wpdb->update(
				$tableName,
				array(
					            'b_project_id' => $b_pr_data['id'],
								'b_project_name' => $b_pr_data['name'],
								'b_project_thumb' => $b_project_thumb,
								'b_creative_fields' => substr($b_fields, 2),								
								'b_create_date' => date('Y-m-d H:i:s', $b_pr_data['created_on']),
								'b_modified_timestamp' => $b_pr_data['modified_on']							        					        	  
				),
				array(
								'id' => $project->id
				),
				array(					           
					        	'%s',
								'%s',
								'%s',
					        	'%s',								
					        	'%s',
					        	'%s'																					        				        	
				),
				array( '%d'));
					        		
		
			}
			return true;
			
		}catch(Exception $e){
			return false;
		}
	}
	
	public function get_project_list(){
		try{
			global $wpdb;
			$project_table = $wpdb->prefix . EDS_BPM_Config::$project_table;
			
			$query = "SELECT id, b_project_name as name FROM `$project_table` WHERE status = 'published'";			
				
			$project_list = $wpdb->get_results($query, ARRAY_A );
				
			return $project_list;			
			
		}catch(Exception $e){
			return null;
		}
	}
	
	public function get_layout_category_list(){
		try{
			global $wpdb;
			$category_table = $wpdb->prefix . EDS_BPM_Config::$category_table;
			
			$query = "SELECT id, name FROM `$category_table` WHERE status = 'published'";			
				
			$category_list = $wpdb->get_results($query, ARRAY_A );
				
			return $category_list;			
			
		}catch(Exception $e){
			return null;
		}
	}
}
}