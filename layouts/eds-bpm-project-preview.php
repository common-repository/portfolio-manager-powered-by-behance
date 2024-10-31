<?php
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	
	$b_tools_used = "";
	foreach ($b_pr_data['tools'] as $tool){
		$b_tools_used .= ", " . $tool['title'];
	}
	
	$license_id = intval($b_pr_data['copyright']['license_id']);

	//	Localizing admin javascript
	wp_localize_script( 'eds-bpm-admin-js', 'eds_bpm_view', 'edit' );
	wp_localize_script( 'eds-bpm-admin-js', 'eds_bpm_custom_css', $customCSS );
	wp_localize_script( 'eds-bpm-admin-js', 'eds_bpm_css_url',  plugin_dir_url(__FILE__).'../css/');

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
			<div id="bop-project-info-small">
				<div class="bpm-project-details-wrapper">
					<div class="bop-project-info-small-left">
						<div class="bop-project-info-small-header">							
							<div id="bop-left-project-title"><?php echo $b_pr_data['name']; ?></div>							
							<div id="bop-category"><?php echo substr($b_fields, 2);?></div>							
							<div id="stats-top">							
								<span class="stats-top-project-views"><i class="zmdi zmdi-eye zmd-lg"></i> <?php echo $b_pr_data['stats']['views'] ; ?>  </span>						
								<span class="stats-top-project-appreciations"><i class="zmdi zmdi-thumb-up zmd-lg"></i> <?php echo $b_pr_data['stats']['appreciations'] ; ?>  </span>						 
								<span class="stats-top-project-comments"><i class="zmdi zmdi-comment-alt zmd-lg"></i> <?php echo $b_pr_data['stats']['comments'] ; ?>  </span>								
							</div>
						</div>
					</div>			
				</div>
				<div class="bpm-top-info-menu-dropdown-wrapper">
						<nav class="bpm-top-info-nav">
							<ul>							
								<li>
									<a href="#"><i class="zmdi zmdi-info-outline zmd-lg"></i>Tools Used</a>
									<ul class="fallback">
										<li><?php echo substr($b_tools_used, 2);?></li>
									</ul>
								</li>							
								<li>
									<a href="#"><i class="zmdi zmdi-wrench zmd-lg"></i>About</a>
									<ul class="fallback">
										<li><?php echo $b_pr_data['description']; ?> </li>
									</ul>
								</li>								
								<li>
									<a href="#"><i class="zmdi zmdi-account zmd-lg"></i>Project by</a>
									<ul class="fallback bpm-project-by-small">
										<div class="bpm-project-by-right-wrapper">
											<?php foreach($b_pr_data['owners'] as $owner) : ?>
												<div class="bpm-project-by-right">
													<div class="bpm-project-by-right-image">																							
														<?php
															$last_key = 0; 
															$owner_img = ""; 
															foreach($owner['images'] as $im_key => $im_value){
																if($last_key < intval($im_key)){
																	$last_key = $im_key;
																	$owner_img = $im_value;	
																}														
															}
														?>												
														<img src="<?php echo $owner_img; ?>" />												
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
							</ul>
						</nav>
					</div>
				
			</div>
			<div class="bop-project-area">
				<div id="bop-project-wrapper">
					<div id="bop-project-left">
						<div id="bop-left-project-header">							
							<div id="bop-left-project-title"><?php echo $b_pr_data['name']; ?></div>							
							<div id="bop-category"><?php echo substr($b_fields, 2);?></div>						
							<div id="stats-top">							
								<span class="stats-top-project-views"><i class="zmdi zmdi-eye zmd-lg"></i> <?php echo $b_pr_data['stats']['views'] ; ?>  </span>						
								<span class="stats-top-project-appreciations"><i class="zmdi zmdi-thumb-up zmd-lg"></i> <?php echo $b_pr_data['stats']['appreciations'] ; ?>  </span>						 
								<span class="stats-top-project-comments"><i class="zmdi zmdi-comment-alt zmd-lg"></i> <?php echo $b_pr_data['stats']['comments'] ; ?>  </span>							
							</div>
						</div>
						<div class="bop-primary-project-content">
							<div class="spacer" style="height: <?php echo $b_pr_data['styles']['spacing']['project']['top_margin'];?>px">
							</div>
							<?php foreach($b_pr_data['modules'] as $b_module):?>
								<?php if($b_module['full_bleed'] == '1'): ?>
								<div class="bop-text-center bop-image-full">
								<?php else: ?>
								<div class="bop-text-center">
								<?php endif; ?>
								<?php if($b_module['type'] == 'image'): ?>
									<img src="<?php echo $b_module['sizes']['original'];?>" />
								<?php elseif ($b_module['type'] == 'text'):?>
									<?php echo $b_module['text']; ?>						
								<?php elseif ($b_module['type'] == 'audio'):?>
									<?php echo $b_module['embed']; ?>
								<?php elseif ($b_module['type'] == 'embed'):?>
									<?php echo $b_module['embed']; ?>
								<?php elseif ($b_module['type'] == 'video'):?>
									<a href="<?php echo $b_module['src']; ?>" target="_blank" >Video</a>
								<?php endif;?>			
								</div>
								<div class="spacer" style="height: <?php echo $b_pr_data['styles']['spacing']['modules']['bottom_margin'];?>px">
									<?php $dividerStyle ='';?>
									<?php foreach($b_pr_data['styles']['dividers'] as $p_name => $p_value):?>
									<?php $p_name = str_replace('_','-' , $p_name); ?>
									<?php $dividerStyle = $dividerStyle . ';'.$p_name.':'.$p_value;?>
									<?php endforeach;?>
									<div class="divider" style="<?php echo substr($dividerStyle,1);?>">
									</div>
								</div>						
							<?php endforeach; ?>
						</div>
						<div id="bpm-project-footer-wrapper">
							<div id="bpm-inner-footer-wrapper">						
								<div class="bpm-project-footer-block bpm-footer-basic-info">							
									<div class="bpm-footer-block-heading">Basic Info</div>
									<div class="bpm-footer-content-padding">							
										<?php echo $b_pr_data['description']; ?>
									</div>									 
									<div class="bpm-footer-published-on">Published: <?php echo date('M d, Y', $b_pr_data['published_on']); ?></div>									
								</div>								
								<div class="bpm-project-footer-block bpm-footer-project-by">
									<div class="bpm-footer-block-heading">Project by</div>
									<div class="bpm-project-by-right-wrapper">
										<?php foreach($b_pr_data['owners'] as $owner) : ?>
											<div class="bpm-project-by-right">
												<div class="bpm-project-by-right-image">																							
													<?php
														$last_key = 0; 
														$owner_img = ""; 
														foreach($owner['images'] as $im_key => $im_value){
															if($last_key < intval($im_key)){
																$last_key = $im_key;
																$owner_img = $im_value;	
															}														
														}
													?>												
													<img src="<?php echo $owner_img; ?>" />												
												</div>
												<div class="bpm-project-by-right-author">
													<?php echo $owner['first_name'] . ' ' . $owner['last_name']; ?>
													<span class="bpm-project-by-place-right"> <i class="zmdi zmdi-pin"></i> <?php echo $owner['location']; ?></span>
												</div>
											</div>
										<?php endforeach;?>
									</div>
								</div>						
								<div class="bpm-project-footer-block bpm-footer-basic-info">
									<div class="bpm-footer-block-heading">Tags</div>
									<div class="bpm-footer-content-padding">
									<?php foreach ($b_pr_data['tags'] as $b_tag): ?>
									<span class="bpm-footer-tag"><?php echo $b_tag; ?></span>
									<?php endforeach; ?>
									</div>
								</div>								
								<div class="bpm-project-footer-block bpm-footer-basic-info">
									<div class="bpm-footer-block-heading">Tools Used</div>
									<div class="bpm-footer-content-padding">
										<?php echo substr($b_tools_used, 2);?> 
									</div>
								</div>
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
								<div class="bpm-report-project">
								 	<a href="https://help.behance.net/hc/en-us/requests/new" target="_blank"><i class="zmdi zmdi-alert-triangle"></i> Report</a>
								</div>						
							</div>					
						</div>
					</div>
					<div id="bop-project-right">
						<div class="sidebar-group">							
							<div id="bop-project-info-right" class="bop-project-right">
								<div class="bpm-title-right">Project By</div>
								<div class="bpm-project-by-right-wrapper">
									<?php foreach($b_pr_data['owners'] as $owner) : ?>
										<div class="bpm-project-by-right">
											<div class="bpm-project-by-right-image">																							
												<?php
													$last_key = 0; 
													$owner_img = ""; 
													foreach($owner['images'] as $im_key => $im_value){
														if($last_key < intval($im_key)){
															$last_key = $im_key;
															$owner_img = $im_value;	
														}														
													}
												?>												
												<img src="<?php echo $owner_img; ?>" />												
											</div>
											<div class="bpm-project-by-right-author">
												<?php echo $owner['first_name'] . ' ' . $owner['last_name']; ?>
												<span class="bpm-project-by-place-right"> <i class="zmdi zmdi-pin"></i> <?php echo $owner['location']; ?></span>
											</div>
										</div>
									<?php endforeach;?>
								</div>
							</div>							
							<div id="bop-project-about-right" class="bop-project-right">								
									<div class="bpm-title-right">About Project</div>
									<?php $append='&hellip;';
										$d_text = $b_pr_data['description'];?>
									<?php if(strlen($d_text)<=120):?>
										<p><?php echo $d_text; ?></p>
									<?php else: ?>
										<div class="bop-short-desc">
											<?php $out = substr($d_text,0,140);?>
					   						<?php if (strpos($d_text,' ') === FALSE){
					   							echo  $out.$append;
					   						}else{ 
					   							echo  '<p>'.preg_replace('/\w+$/','',$out).$append.'</p>';
					   						}?>
											<a href="#" id="bop-read-more" >Read More</a>
										</div>
										<div class="bop-full-desc" style="display:none">
											<p><?php echo $d_text; ?></p>
											<a href="#" id="bop-read-less" >Read Less</a>
										</div>
									<?php endif;?>														 
								<div class="bpm-published-on">Published: <?php echo date('M d, Y', $b_pr_data['published_on']); ?></div>								
							</div>						
							<div id="bop-project-info-right" class="bop-project-right">
								<div class="bpm-title-right">Tools Used</div>
								<p><?php echo substr($b_tools_used, 2);?></p> 
							</div>								
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>