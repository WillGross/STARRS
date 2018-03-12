jQuery(document).ready(function() {
	
	if(typeof jQuery.fancybox == 'function') {
		
		  jQuery("#upcoming-events a.fancylink").fancybox({
			maxWidth	: '98%',
			maxHeight	: '95%',
			fitToView	: true,
			arrows 		: false,
			width		: '80%',
			height		: '80%',
			autoSize	: true,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none',
			mouseWheel	: 'false',
			beforeShow : function() {
				//this.title = '<div id="shareBottom" class="addthis_toolbox addthis_default_style" addthis:url="' + this.href.replace('&print=1&ajax=1','') + '" ><a onclick="printIt(jQuery(\'#eventArea\').html())" href="javascript:void:0" class="btn btn-info" style="float:right;font-size:1.2em;"><i class="icon-print icon-white"></i> Print</a><a class="addthis_button_facebook_like" fb:like:layout="button_count" addthis:url="http://www.colby.edu' + this.href.replace('&print=1&ajax=1','') + '"></a><a class="addthis_button_tweet" fb:like:layout="button_count" addthis:url="http://www.colby.edu' + this.href + '"></a><a href="http://www.addthis.com/bookmark.php" class="addthis_button addthis_plus_custom" style="text-decoration:none;" addthis:url="http://www.colby.edu' + this.href.replace('&print=1&ajax=1','') + '"><img src="/wp-content/themes/colbycollege/images/sm-plus-custom.png" width="16" height="16" border="0" alt="Share" /> Share</a></div>' + (this.title ? ' ' + this.title : '');
		    },
		    beforeLoad : function(){
				  var url= jQuery(this).attr("href");
				  if( url.indexOf('?') == -1 ) {
					  url += "?";
				  }
				  else {
					  url +="&";
				  }
				  url += 'print=1&ajax=1';
				  this.href = url
			},
		    afterShow : function() {
				if(typeof addthis == "undefined")
				    jQuery.getScript( "https://s7.addthis.com/js/300/addthis_widget.js#domready=1",function(data, textStatus, jqxhr) {
				       addthis.init();
					   if(addthis)
					   	addthis.toolbox(jQuery(".addthis_toolbox").get());
					});
				else{
						addthis.toolbox(jQuery(".addthis_toolbox").get());
						addthis.button(jQuery(".addthis_button").get());
				}	    
		    },
		    helpers : {
		        title : {
		            type : 'inside'
		        },
		        overlay: {
			      locked: false
			    }   
		        
		    }
		});	
	}
});