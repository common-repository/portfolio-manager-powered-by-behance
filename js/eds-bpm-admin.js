
function edsToggleCheckBox(source,entry_name)
{
	checkboxes = document.getElementsByName(entry_name);
	for(var i in checkboxes) 
		checkboxes[i].checked = source.checked;
}
	
function edsIsInt(value) {
	  return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
}

(function($){
	
	

	$(document).ready(function(){
		
		//Settings related JS
		//Category Color Picker
		$('#project_background_color').wpColorPicker();
		
		$('.project_attribute_switches').bootstrapSwitch();	
		
		
		//Category page buttons
		
		//Category Image upload
		var custom_uploader;
		$('#cat-icon-upload').click(function(e) {			 
	        e.preventDefault();
	 
	        //If the uploader object has already been created, reopen the dialog
	        if (custom_uploader) {
	            custom_uploader.open();
	            return;
	        }
	 
	        //Extend the wp.media object
	        custom_uploader = wp.media.frames.file_frame = wp.media({
	            title: 'Choose Image',
	            button: {
	                text: 'Choose Image'
	            },
	            multiple: false
	        });
	 
	        //When a file is selected, grab the URL and set it as the text field's value
	        custom_uploader.on('select', function() {
	            attachment = custom_uploader.state().get('selection').first().toJSON();
	            $('#cat-icon').val(attachment.url);
	            $('#cat-icon-img').prop('src',attachment.url);
	        });
	 
	        //Open the uploader dialog
	        custom_uploader.open();
	 
	    });
		
		
		$("#search-category").click(function(event){
			event.preventDefault();			
			$("input[name=bpm-layout]").val("default");
			$("input[name=bpm-task]").val("search");
			$("#eds_bpm_category").submit();
			
		});
		
		$("#clear-category").click(function(event){
			$("input[name=bpm-layout]").val("default");
			$("input[name=bpm-task]").val("clear");
			$("#eds_bpm_category").submit();	
		});	
		
		$("#bpm-publish-cat").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{				
				$("input[name=bpm-task]").val("publish");
				$("#eds_bpm_category").submit();				
			}
			else{
				alert("Please select atleast one Category");
			}
			
		});
		
		$("#bpm-unpublish-cat").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{
				$("input[name=bpm-task]").val("unpublish");
				$("#eds_bpm_category").submit();
			}
			else{
				alert("Please select atleast one Category");
			}
			
		});
		
		$("#bpm-delete-cat").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{
				if(confirm("Are you sure, you wish to delete the selected category(s)?")){
					$("input[name=bpm-task]").val("delete");
					$("#eds_bpm_category").submit();
				}
			}
			else{
				alert("Please select atleast one Category");
			}
			
		});
		
		$("#bpm-trash-cat").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{
				if(confirm("Are you sure, you wish to permanently delete the selected category?")){
					$("input[name=bpm-task]").val("trash");
					$("#eds_bpm_category").submit();
				}
			}
			else{
				alert("Please select atleast one Category");
			}
			
		});	
	
		
		$("#bpm-save-cat").click(function(event){			
			$("input[name=bpm-task]").val("save");
			$("input[name=bpm-layout]").val("default");			
		});
		
		$(".bpm-category-status-link").click(function(event){
			event.preventDefault();
			$status = $(this).attr("current-status");
			
			if($status == 'published'){
				$("input[name=bpm-task]").val("unpublish");
			}else{
				$("input[name=bpm-task]").val("publish");
			}			
			$(this).parent().siblings('.bpm-checkbox-wrapper').find("input[name='entries[]']").prop('checked', true);			
			$("#eds_bpm_category").submit();
			
		});
		
		
		
		//Project related buttons 
		$("#search-projects").click(function(event){
			event.preventDefault();			
			$("input[name=bpm-layout]").val("default");
			$("input[name=bpm-task]").val("search");
			$("#eds_bpm_project").submit();
			
		});
		
		$("#clear-projects").click(function(event){
			$("input[name=bpm-layout]").val("default");
			$("input[name=bpm-task]").val("clear");
			$("#eds_bpm_project").submit();	
		});
	
		$("#bpm-publish-project").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{			
				$("input[name=bpm-task]").val("publish");
				$("#eds_bpm_project").submit();
			
			}
			else{
				alert("Please select atleast one Project");
			}
			
		});
		
		$("#bpm-unpublish-project").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{
				$("input[name=bpm-task]").val("unpublish");
				$("#eds_bpm_project").submit();				
			}
			else{
				alert("Please select atleast one Project");
			}
			
		});
		
		$("#bpm-set-featured-project").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{				
				$("input[name=bpm-task]").val("setfeatured");
				$("#eds_bpm_project").submit();				
			}
			else{
				alert("Please select atleast one Project");
			}
			
		});
		
		$("#bpm-delete-project").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{
				if(confirm("Are you sure, you wish to delete the selected project(s)?")){
					$("input[name=bpm-task]").val("delete");
					$("#eds_bpm_project").submit();
				}
			}
			else{
				alert("Please select atleast one Project");
			}
			
		});
		
		$("#bpm-trash-project").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{
				if(confirm("Are you sure, you wish to permanently delete the selected project(s)?")){
					$("input[name=bpm-task]").val("trash");
					$("#eds_bpm_project").submit();
				}
			}
			else{
				alert("Please select atleast one Project");
			}
			
		});
		
		
		$("#bpm-sync-project").click(function(event){
			event.preventDefault();
			if($("input[name='entries[]']:checked").length)
			{
				$("input[name=bpm-task]").val("sync");
				$("#eds_bpm_project").submit();
			}
			else{
				alert("Please select atleast one Project");
			}
			
		});
		
		$("#bpm-search-project-behance").click(function(event){
			event.preventDefault();
			var bp_id = $("#bp-search-id").val();
			
			if(bp_id == ''){
				alert("Please enter Behance Project ID");
			}else if(!edsIsInt(bp_id)){
				alert("Please enter a numeric value");				
			}
			else{
				$("input[name=bpm-sub-task]").val("b_project_search");
				$("#eds_bpm_project").attr("method","get");
				$("#eds_bpm_project").submit();
			}
			
		});
		
		$("#bpm-clear-project-behance").click(function(event){
			event.preventDefault();
			$("input[name=bpm-sub-task]").val("b_clear_search");
			$("#eds_bpm_project").submit();			
		});
		
		$("#bpm-save-behance-project").click(function(event){
			event.preventDefault();
			$("input[name=bpm-task]").val("save");
			$("input[name=bpm-layout]").val("default");
			$("#eds_bpm_project").submit();			
		});
		
		
		$(".bpm-project-status-link").click(function(event){
			event.preventDefault();
			$status = $(this).attr("current-status");
			
			if($status == 'published'){
				$("input[name=bpm-task]").val("unpublish");
			}else{
				$("input[name=bpm-task]").val("publish");
			}			
			$(this).parent().parent().siblings('.bpm-checkbox-wrapper').find("input[name='entries[]']").prop('checked', true);			
			$("#eds_bpm_project").submit();
			
		});
		
		$(".bpm-project-featured-link").click(function(event){
			event.preventDefault();
			$status = $(this).attr("current-status");
			
			if($status == '1'){
				$("input[name=bpm-task]").val("unsetfeatured");
			}else{
				$("input[name=bpm-task]").val("setfeatured");
			}			
			$(this).parent().parent().siblings('.bpm-checkbox-wrapper').find("input[name='entries[]']").prop('checked', true);			
			$("#eds_bpm_project").submit();
			
		});
		
		$('#bop-read-more').click(function(event){
			event.preventDefault();
			$(".bop-short-desc").hide();
			$(".bop-full-desc").show();
		});
		
		$('#bop-read-less').click(function(event){
			event.preventDefault();
			$(".bop-full-desc").hide();
			$(".bop-short-desc").show();
		});
		
		
		if(typeof eds_bpm_view !== 'undefined' && eds_bpm_view == "edit"){
			$('nav.bpm-top-info-nav li ul').hide().removeClass('fallback');
			$('nav.bpm-top-info-nav li').hover(function () {
				$('ul', this).stop().slideToggle(200);
			});

			if($('input[name="b_project_id"]').length && $('input[name="b_project_id"]').val().length){
				var url =  eds_bpm_css_url;			
				
				//Adding font awsome and material design iconic font
				//$('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300">').appendTo("head");
				$('<link rel="stylesheet" type="text/css" href="'+url+'material-design-iconic-font.min.css">').appendTo("head");	
				$('<link rel="stylesheet" type="text/css" href="'+url+'project_view.css">').appendTo("head");
				//Adding project view related CSS
				
				$("#bop-waiting-popup").show();
								 
				var cssFile = "";
				
				var width = $("#bop-all-wrapper").parent().innerWidth();
								
				if(width<=959 && width>625)
					cssFile = "bop-responsive-626.css";
				else if(width<=625 && width>479)					
					cssFile = "bop-responsive-480.css";						
				else if(width<=479)
					cssFile = "bop-responsive-320.css";			
				
				if(cssFile != "")
				{
					var cssURL = url + cssFile;
					$.ajax({
					  url: cssURL					  
					}).done(function( data ) {
						$('<style type="text/css">' + data + '</style>').appendTo("head");
						//Adding Custom CSS from behance
						$('<style type="text/css">' + eds_bpm_custom_css + '</style>').appendTo("head");			
						$("#bop-waiting-popup").hide();
						$("#bop-project").animate({opacity : 1},1000);																			    
					});
				}else{
					//Adding Custom CSS from behance
					$('<style type="text/css">' + eds_bpm_custom_css + '</style>').appendTo("head");					
					$("#bop-waiting-popup").hide();					
					$("#bop-project").animate({opacity : 1}, 1000);					
				}
				
				
			}					
		}
		
		
	});
})(jQuery);

