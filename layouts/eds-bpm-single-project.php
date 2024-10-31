<?php
 if ( ! defined( 'WPINC' ) ) {
	die;
} 
?>
<?php
	//Advanced Configuration Parameters
	$show_project_title = (isset($config['show_project_title']) && $config['show_project_title']=="yes");
	$show_creative_fields = (isset($config['show_creative_fields']) && $config['show_creative_fields']=="yes" && $b_pr_data['fields']!=null && sizeof($b_pr_data['fields']));
	$show_project_by = (isset($config['show_project_by']) && $config['show_project_by']=="yes");
	$show_about_project = ((isset($config['show_about_project']) && $config['show_about_project']=="yes") && $b_pr_data['description']!=null && strlen($b_pr_data['description']));
	$show_publish_date =  ((isset($config['show_publish_date']) && $config['show_publish_date']=="yes"));
	$show_views = (isset($config['show_views']) && $config['show_views']=="yes");
	$show_appreciations = (isset($config['show_appreciations']) && $config['show_appreciations']=="yes");
	$show_comments = (isset($config['show_comments']) && $config['show_comments']=="yes");	
	$show_tags = (isset($config['show_tags']) && $config['show_tags']=="yes" && $b_pr_data['tags']!=null && sizeof($b_pr_data['tags']));
	$show_tools_used = (isset($config['show_tools_used']) && $config['show_tools_used']=="yes" && $b_pr_data['tools']!=null && sizeof($b_pr_data['tools']));
	$show_copyright_info = (isset($config['show_copyright_info']) && 
							$config['show_copyright_info']=="yes" && 
							isset($b_pr_data['copyright']) && sizeof($b_pr_data['copyright']));
							
	$license_id = intval($b_pr_data['copyright']['license_id']);
							
							
	
	//Combined Checks
	$show_project_details_wrapper = $show_project_title || $show_creative_fields || $show_views || $show_appreciations || $show_comments;
	$show_project_info_wrapper = $show_tools_used || $show_about_project || $show_project_by;
	

	//Adding CSS properties from behance
	$customCSS = ".bpm-sr-attribution{ background-image: url('".plugin_dir_url(__FILE__)."../images/by.svg'); } ";
	$customCSS .= ".bpm-sr-noncommercial{ background-image: url('".plugin_dir_url(__FILE__)."../images/nc.svg'); } ";
	$customCSS .= ".bpm-sr-noderivatives{ background-image: url('".plugin_dir_url(__FILE__)."../images/nd.svg'); } ";
	$customCSS .= ".bpm-sr-sharealike{ background-image: url('".plugin_dir_url(__FILE__)."../images/sa.svg'); } ";
	$customCSS .= ".bpm-sr-zero{ background-image: url('".plugin_dir_url(__FILE__)."../images/zero.svg'); } ";
	$customCSS .= "#bop-container{ background-color:". $config['project_background_color'] . ";	} ";
	
	if(isset($b_pr_data['canvas_width']) && !empty($b_pr_data['canvas_width'])){
		$customCSS .= "#bop-project-left { max-width:". $b_pr_data['canvas_width'] ."px; } ";
	}else{
		$customCSS .= "#bop-project-left { max-width:724px; } ";
	}
				
	$customCSS .= "#bop-project-left .bop-primary-project-content{";
	
	if(isset($b_pr_data['styles']['background']['color']))
		$customCSS .= "	background-color: #" . $b_pr_data['styles']['background']['color'] . ";";
		
	if(isset($b_pr_data['styles']['background']['image']['url']))
		$customCSS .= "background-image: url('" . $b_pr_data['styles']['background']['image']['url'] ."');";

	if(isset($b_pr_data['styles']['background']['image']['repeat'])){
		$customCSS .= "background-repeat: ". $b_pr_data['styles']['background']['image']['repeat'] . ";";
			
		if($b_pr_data['styles']['background']['image']['repeat'] == "repeat")
			$customCSS .= "background-size: auto";	
		else
			$customCSS .= "background-size: 100% auto;";
	}

	if(isset($b_pr_data['styles']['background']['image']['position']))
		$customCSS .= "background-position:". $b_pr_data['styles']['background']['image']['position'] .";";
			
	$customCSS .= " overflow-x: hidden; } .bop-primary-project-content a{";
	
	foreach($b_pr_data['styles']['text']['link'] as $p_name => $p_value){
		$p_name = str_replace('_','-' , $p_name);
		$customCSS .= $p_name.':'.$p_value.';';
	}
	
	$customCSS .= "} .bop-primary-project-content p{";
	
	foreach($b_pr_data['styles']['text']['paragraph'] as $p_name => $p_value){
		$p_name = str_replace('_','-' , $p_name);
		$customCSS .= $p_name.':'.$p_value.';';
	}
	$customCSS .= "} ";			
	
	if (isset($config['eds_bpm_custom_css']) && trim($config['eds_bpm_custom_css'])!=''){	
		$customCSS .= trim($config['eds_bpm_custom_css']);
	}
	
	if($customCSS != null && $customCSS !="")
		$customCSS = EDS_BPM_Config::trim_all($customCSS);
	
	wp_localize_script( 'eds-bpm-site-js', 'eds_bpm_custom_css', $customCSS );
	wp_localize_script( 'eds-bpm-site-js', 'eds_bpm_css_url',  plugin_dir_url(__FILE__).'../css/');
	
?>
<?php 
$bFields ='';
foreach ($b_pr_data['fields'] as $bField){ 
	$bFields = $bFields. ', ' . $bField;
}
$bToolsUsed = "";
foreach ($b_pr_data['tools'] as $tool){
	$bToolsUsed .= ", " . $tool['title'];
}
?>
<div id="bop-container">
	<div id="bop-waiting-popup" class="bop-waiting-popup" style="display:none;">
	    <div class="bop-popup-background"></div>
	    <div class="bop-loading-image">
	        <div class="flower-loader">
  				Loading…
			</div>
	    </div>	    
	</div>
	<div id="bop-project" style="opacity:0">	
		<div id="bop-all-wrapper">
			<?php if($show_project_details_wrapper || $show_project_details_wrapper):?>			
			<div id="bop-project-info-small">
				<?php if($show_project_details_wrapper):?>
				<div class="bpm-project-details-wrapper">
					<div class="bop-project-info-small-left">
						<div class="bop-project-info-small-header">
							<?php if($show_project_title):?>
							<div id="bop-left-project-title"><?php echo $b_pr_data['name']; ?></div>
							<?php endif;?>
							<?php if($show_creative_fields):?>
							<div id="bop-category"><?php echo substr($bFields, 2);?></div>
							<?php endif; ?>
							<div id="stats-top">
								<?php if($show_views):?>
								<span class="stats-top-project-views"><i class="zmdi zmdi-eye zmd-lg"></i> <?php echo $b_pr_data['stats']['views'] ; ?>  </span>
								<?php endif; ?>
								<?php if($show_appreciations):?>
								<span class="stats-top-project-appreciations"><i class="zmdi zmdi-thumb-up zmd-lg"></i> <?php echo $b_pr_data['stats']['appreciations'] ; ?>  </span>
								<?php endif; ?>
								<?php if($show_comments):?> 
								<span class="stats-top-project-comments"><i class="zmdi zmdi-comment-alt zmd-lg"></i> <?php echo $b_pr_data['stats']['comments'] ; ?>  </span>
								<?php endif; ?>	
							</div>
						</div>
					</div>			
				</div>
				<?php endif; ?>
				<?php if($show_project_info_wrapper): ?>
				<div class="bpm-top-info-menu-dropdown-wrapper">
						<nav class="bpm-top-info-nav">
							<ul>
								<?php if($show_tools_used):?>
								<li>
									<a href="#"><i class="zmdi zmdi-info-outline zmd-lg"></i>Tools Used</a>
									<ul class="fallback">
										<li><?php echo substr($bToolsUsed, 2);?></li>
									</ul>
								</li>
								<?php endif; ?>
								<?php if($show_about_project):?>		
								<li>
									<a href="#"><i class="zmdi zmdi-wrench zmd-lg"></i>About</a>
									<ul class="fallback">
										<li><?php echo $b_pr_data['description']; ?> </li>
									</ul>
								</li>
								<?php endif; ?>
								<?php if($show_project_by):?>
								<li>
									<a href="#"><i class="zmdi zmdi-account zmd-lg"></i>Project by</a>
									<ul class="fallback bpm-project-by-small">
										<div class="bpm-project-by-right-wrapper">
											<?php foreach($b_pr_data['owners'] as $owner) : ?>
												<div class="bpm-project-by-right">
													<div class="bpm-project-by-right-image">																							
														<?php
															$lastKey = 0; 
															$ownerImg = ""; 
															foreach($owner['images'] as $imKey => $imValue){
																if($lastKey < intval($imKey)){
																	$lastKey = $imKey;
																	$ownerImg = $imValue;	
																}														
															}
														?>												
														<img src="<?php echo $ownerImg; ?>" />												
													</div>
													<div class="bpm-project-by-right-author">
														<?php echo $owner['first_name'] . ' ' . $owner['last_name']; ?>
														<span class="bpm-project-by-place-right"> <i class="zmdi zmdi-pin"></i> <?php echo $owner['location']; ?></span>
													</div>
												</div>
											<?php endforeach;?>
										</div>
									</ul>
								</li>
								<?php endif; ?>
							</ul>
						</nav>
					</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="bop-project-area">
				<div id="bop-project-wrapper">
					<div id="bop-project-left">
						<?php if($show_project_details_wrapper): ?>
						<div id="bop-left-project-header">
							<?php if($show_project_title):?>
							<div id="bop-left-project-title"><?php echo $b_pr_data['name']; ?></div>
							<?php endif;?>
							<?php if($show_creative_fields):?>
							<div id="bop-category"><?php echo substr($bFields, 2);?></div>
							<?php endif; ?>
							<div id="stats-top">
								<?php if($show_views):?>
								<span class="stats-top-project-views"><i class="zmdi zmdi-eye zmd-lg"></i> <?php echo $b_pr_data['stats']['views'] ; ?>  </span>
								<?php endif; ?>
								<?php if($show_appreciations):?>
								<span class="stats-top-project-appreciations"><i class="zmdi zmdi-thumb-up zmd-lg"></i> <?php echo $b_pr_data['stats']['appreciations'] ; ?>  </span>
								<?php endif; ?>
								<?php if($show_comments):?> 
								<span class="stats-top-project-comments"><i class="zmdi zmdi-comment-alt zmd-lg"></i> <?php echo $b_pr_data['stats']['comments'] ; ?>  </span>
								<?php endif; ?>	
							</div>
						</div>
						<?php endif; ?>
						<div class="bop-primary-project-content">
							<div class="spacer" style="height: <?php echo $b_pr_data['styles']['spacing']['project']['top_margin'];?>px">
							</div>
							<?php foreach($b_pr_data['modules'] as $bModule):?>
								<?php if($bModule['full_bleed'] == '1'): ?>
								<div class="bop-text-center bop-image-full">
								<?php else: ?>
								<div class="bop-text-center">
								<?php endif; ?>
								<?php if($bModule['type'] == 'image'): ?>
									<img src="<?php echo $bModule['sizes']['original'];?>" />
								<?php elseif ($bModule['type'] == 'text'):?>
									<?php echo $bModule['text']; ?>						
								<?php elseif ($bModule['type'] == 'audio'):?>
									<?php echo $bModule['embed']; ?>
								<?php elseif ($bModule['type'] == 'embed'):?>
									<?php echo $bModule['embed']; ?>
								<?php elseif ($bModule['type'] == 'video'):?>
									<a href="<?php echo $bModule['src']; ?>" target="_blank" >Video</a>
								<?php endif;?>			
								</div>
								<div class="spacer" style="height: <?php echo $b_pr_data['styles']['spacing']['modules']['bottom_margin'];?>px">
									<?php $dividerStyle ='';?>
									<?php foreach($b_pr_data['styles']['dividers'] as $pName => $pValue):?>
									<?php $pName = str_replace('_','-' , $pName); ?>
									<?php $dividerStyle = $dividerStyle . ';'.$pName.':'.$pValue;?>
									<?php endforeach;?>
									<div class="divider" style="<?php echo substr($dividerStyle,1);?>">
									</div>
								</div>						
							<?php endforeach; ?>
						</div>
						<div id="bpm-project-footer-wrapper">
							<div id="bpm-inner-footer-wrapper">								
								<?php if($show_about_project || $show_publish_date):?>
								<div class="bpm-project-footer-block bpm-footer-basic-info">
									<?php if($show_about_project):?>		
									<div class="bpm-footer-block-heading">Basic Info</div>	
									<div class="bpm-footer-content-padding">						
									<?php echo $b_pr_data['description']; ?>
									</div>									
									<?php endif; ?>
									<?php if($show_publish_date):?> 
									<div class="bpm-footer-published-on">Published: <?php echo date('M d, Y', $b_pr_data['published_on']); ?></div>
									<?php endif;?>
								</div>
								<?php endif; ?>
								<?php if($show_project_by):?>
								<div class="bpm-project-footer-block bpm-footer-project-by">
									<div class="bpm-footer-block-heading">Project by</div>
									<div class="bpm-project-by-right-wrapper">
										<?php foreach($b_pr_data['owners'] as $owner) : ?>
											<div class="bpm-project-by-right">
												<div class="bpm-project-by-right-image">																							
													<?php
														$lastKey = 0; 
														$ownerImg = ""; 
														foreach($owner['images'] as $imKey => $imValue){
															if($lastKey < intval($imKey)){
																$lastKey = $imKey;
																$ownerImg = $imValue;	
															}														
														}
													?>												
													<img src="<?php echo $ownerImg; ?>" />												
												</div>
												<div class="bpm-project-by-right-author">
													<?php echo $owner['first_name'] . ' ' . $owner['last_name']; ?>
													<span class="bpm-project-by-place-right"> <i class="zmdi zmdi-pin"></i> <?php echo $owner['location']; ?></span>
												</div>
											</div>
										<?php endforeach;?>
									</div>
								</div>
								<?php endif; ?>
								<?php if($show_tags):?>	
								<div class="bpm-project-footer-block bpm-footer-basic-info">
									<div class="bpm-footer-block-heading">Tags</div>
									<div class="bpm-footer-content-padding">
									<?php foreach ($b_pr_data['tags'] as $bTag): ?>
									<span class="bpm-footer-tag"><?php echo $bTag; ?></span>
									<?php endforeach; ?>
									</div>
								</div>
								<?php endif; ?>
								<?php if($show_tools_used):?>
								<div class="bpm-project-footer-block bpm-footer-basic-info">
									<div class="bpm-footer-block-heading">Tools Used</div>
									<div class="bpm-footer-content-padding">
									<?php echo substr($bToolsUsed, 2);?> 
									</div>
								</div>
								<?php endif; ?>					
								<?php if($show_copyright_info):?>
								<div class="bpm-project-footer-block bpm-footer-basic-info">
									<div class="bpm-footer-block-heading">Copyright Info</div>
									<?php if($license_id != 7):?>
									<div class="bpm-sr-attribution">Attribution</div>
									<?php endif;?> 
									<?php if($license_id < 4 ):?> 
									<div class="bpm-sr-noncommercial">Non Commercial</div>
									<?php endif; ?>
									<?php if($license_id == 1 || $license_id ==4):?>
									<div class="bpm-sr-noderivatives">No Derivatives</div>
									<?php endif;?>
									<?php if($license_id == 2 || $license_id ==5):?>
									<div class="bpm-sr-sharealike">Share Alike</div>
									<?php endif;?>
									<?php if($license_id == 7):?>
									<div class="bpm-sr-zero">No Use</div>
									<?php endif; ?>									
								</div>
								<?php endif; ?>
								<div class="bpm-report-project">
								 	<a href="https://help.behance.net/hc/en-us/requests/new" target="_blank"><i class="zmdi zmdi-alert-triangle"></i> Report</a>
								</div>
							</div>					
						</div>
					</div>
					<div id="bop-project-right">
						<div class="sidebar-group">
							<?php if($show_project_by):?>
							<div id="bop-project-info-right" class="bop-project-right">
								<div class="bpm-title-right">Project By</div>
								<div class="bpm-project-by-right-wrapper">
									<?php foreach($b_pr_data['owners'] as $owner) : ?>
										<div class="bpm-project-by-right">
											<div class="bpm-project-by-right-image">																							
												<?php
													$lastKey = 0; 
													$ownerImg = ""; 
													foreach($owner['images'] as $imKey => $imValue){
														if($lastKey < intval($imKey)){
															$lastKey = $imKey;
															$ownerImg = $imValue;	
														}														
													}
												?>												
												<img src="<?php echo $ownerImg; ?>" />												
											</div>
											<div class="bpm-project-by-right-author">
												<?php echo $owner['first_name'] . ' ' . $owner['last_name']; ?>
												<span class="bpm-project-by-place-right"> <i class="zmdi zmdi-pin"></i> <?php echo $owner['location']; ?></span>
											</div>
										</div>
									<?php endforeach;?>
								</div>
							</div>
							<?php endif; ?>
							<?php if($show_about_project || $show_publish_date):?>
							<div id="bop-project-about-right" class="bop-project-right">
								<?php if($show_about_project):?>		
									<div class="bpm-title-right">About Project</div>
									<?php $append='&hellip;';
										$dText = $b_pr_data['description'];?>
									<?php if(strlen($dText)<=120):?>
										<p><?php echo $dText; ?></p>
									<?php else: ?>
										<div class="bop-short-desc">
											<?php $out = substr($dText,0,140);?>
					   						<?php if (strpos($dText,' ') === FALSE){
					   							echo  $out.$append;
					   						}else{ 
					   							echo  '<p>'.preg_replace('/\w+$/','',$out).$append.'</p>';
					   						}?>
											<a href="#" id="bop-read-more" >Read More</a>
										</div>
										<div class="bop-full-desc" style="display:none">
											<p><?php echo $dText; ?></p>
											<a href="#" id="bop-read-less" >Read Less</a>
										</div>
									<?php endif;?>							
								<?php endif; ?>
								<?php if($show_publish_date):?> 
								<div class="bpm-published-on">Published: <?php echo date('M d, Y', $b_pr_data['published_on']); ?></div>
								<?php endif;?>
							</div>
							<?php endif; ?>
							<?php if($show_tools_used):?>
							<div id="bop-project-info-right" class="bop-project-right">
								<div class="bpm-title-right">Tools Used</div>
								<p><?php echo substr($bToolsUsed, 2);?></p> 
							</div>
							<?php endif; ?>	
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>