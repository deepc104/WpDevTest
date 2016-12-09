( function( $ ) {
	//alert('hi'); 
	$( ".product-img img" ).click(function() {
		var imgsrc = $(this).attr('src');
		$('.popupcontent').html('');
		$('.popupcontent').html('<img src="'+imgsrc+'" width="100%" border="0" />');
		$('#popupmain').css('display', 'block');
	});	
} )( jQuery );
