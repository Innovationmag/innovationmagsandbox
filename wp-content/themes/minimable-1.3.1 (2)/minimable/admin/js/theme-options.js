jQuery(document).ready(function($) {
	$('.changecolor').each(function() {
		var divPicker = $(this).find('.colorpicker');
		var inputPicker = $(this).find('input[type=text]');
		divPicker.farbtastic(inputPicker);
		divPicker.hide();
		
        $('.pickcolor', this).click(function(){
           divPicker.slideToggle('fast'); 
        });
	});
	$('.sec-one h3').click(function() {   		$('.options-sec-one').slideToggle('slow');	});
	$('.sec-two h3').click(function() {  		$('.options-sec-two').slideToggle('slow');	});
  	$('.sec-three h3').click(function() {
  		$('.options-sec-three').slideToggle('slow');
	});
	$('.sec-four h3').click(function() {
  		$('.options-sec-four').slideToggle('slow');
	});
  	jQuery('.fw_upload input[type="button"]').click(function(){
	    var upField = $(this).parent().find('input[type="text"]');
	    tb_show('', 'media-upload.php?type=image&TB_iframe=true');
	   
	    window.send_to_editor = function(html) {
			imgurl = $('img', html).attr('src');
			upField.val(imgurl);
	 		tb_remove();
		}          	
		return false;
	});
});   

  