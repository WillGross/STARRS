jQuery(window).load(function(){
	jQuery('#rs_private_departments_reader,#rs_private_post_reader,#rs_private_page_reader').parent().prepend("<p class='label' style='border-bottom:1px solid #eee;padding-bottom:4px;'><strong>Page Permissions</strong></p>");
	jQuery('#rs_private_departments_reader,#rs_private_post_reader,#rs_departments_contributor,#rs_departments_editor,#rs_post_contributor,#rs_post_editor,#rs_private_page_reader,#rs_page_contributor,#rs_page_editor,#rs_page_associate').addClass('closed');	


});	

