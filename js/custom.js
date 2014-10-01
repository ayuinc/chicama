$(document).ready(function(){

	// $('#book_button').click(function(e){
	// 	e.preventDefault();
	// 	$('#form_box').slideToggle("slow");
	// });
});
$(document).foundation();

$('.reveal-modal').on('opened', function(){
  $(window).trigger('resize');
});