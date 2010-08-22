window.addEvent('domready', function() {
	$('frmquote').reset();
	$('loading').style.display = 'none';
	$('btnSubmit').style.display = '';
	$('result').style.display = 'none';
	
//	$('frmquote').addEvent('submit', function(e) {
//		/**
//		 * Prevent the submit event
//		 */
//		new Event(e).stop();
//	 
//	 	
//	});
	
	var myFormValidation = new Validate('frmquote',{
		errorClass: 'red',
		onSuccess: function(){
			var result = $('result');
		
		 	$('name').value 		= $('name').value.clean();
			$('email').value 		= $('email').value.clean();
                       			
			
			$('description').value 	= $('description').value.clean();
					
			$('loading').style.display = '';
			$('btnSubmit').style.display = 'none';
			$('result').style.display = 'none';
			
			$('frmquote').send({
				onComplete: function() {
					$('quoteform').style.display = 'none';
					$('loading').style.display = 'none';
					$('result').style.display = '';
				}
			});
		}
	});
});