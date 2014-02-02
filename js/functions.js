$(document).ready(function () {
	$('button').button();
	$('.thumbImage').fancybox({
		openEffect: 'elastic',
		closeEffect: 'elastic',
		
		helpers: {
			title: {
				type: 'inside'
			}
		}
	});
	
	$('button').click(function () {
		var section = $(this).attr('id').split('-')[0];
		if (section != 'index') {
			window.location = section;
		} else {
			window.location = '../';
		}
	});
})