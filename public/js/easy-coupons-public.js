jQuery(function ($) {

	// Helper Function to process form data & make array
	$.fn.form = function() {
		var formData = {};
		this.find('[name]').each(function() {
			formData[this.name] = this.value;  
		})
		return formData;
	};

	// Handle unlock button click. Show popup on click.
	$('[data-easyvid]').click(function(){
		var vidID = $(this).data('easyvid-id');

		//Add Form Markup;
		var form = '<div class="easyvid-coupon-input"><form><input type="hidden" value="'+vidID+'" name="vid_id"/> <input type="text" maxlength="4" name="coupon_code"/> <input type="submit" value="Unlock"/></form></div>';

		$( form ).insertAfter( $(this));
	});

	// Handle unlock form submittion.
	$('.easyvid').on('submit','form',function(e){
		e.preventDefault();
		var formData = $(this).form();
		
		$.ajax({
			url: extra.ajaxurl,
			type: 'post',
			data: {
				'action':'unlock_a_vid',
				'vid_id': formData["vid_id"],
				'coupon' : formData["coupon_code"]
			},
			success: function( response ) {
				if(response == "code_used"){
					alert("Coupon Alredy Used");
				}else if(response == "code_invalid"){
					alert("Coupon Invalid");
				}else{
					//Replace to iframe
					var iframe = "<iframe class='responsive-iframe' src='"+response+"'></iframe>";
					$("#easyvid-"+formData["vid_id"]+" .vidcontainer").html(iframe);
					alert("Video Unlocked!");
				}
				console.log(response);
			},
		});
	});
});