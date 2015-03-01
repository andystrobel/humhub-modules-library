/* js for the library module site */
$(document).ready(function() {
    // set niceScroll to library
    $(".panel-library-widget .library-body .scrollable-content-container").niceScroll({
        cursorwidth: "7",
        cursorborder:"",
        cursorcolor:"#555",
        cursoropacitymax:"0.2",
        railpadding:{top:0,right:3,left:0,bottom:0}
    });
    $(".panel-library-widget .library-body .scrollable-content-container").getNiceScroll().resize();
    
    $(".toggle-view-mode a").on("click", function(e) {
    	e.preventDefault();
    	console.log(jQuery(this));
    	if(jQuery(this).data('enabled')) {
    		jQuery(this).data('enabled', false);
    		$(".library-editable").hide();
    		$(".library-categories").sortable('disable');
    		$(".library-documents").sortable('disable');
    	}
    	else {
    		jQuery(this).data('enabled', true);
    		$(".library-editable").show();
    		$(".library-categories").sortable('enable');
    		$(".library-documents").sortable('enable');
    	}
    });
    
});