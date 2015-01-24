$(document).ready(function () {
	$('.thumbImage').fancybox({
		openEffect: 'elastic',
		closeEffect: 'elastic',
		
		helpers: {
			title: {
				type: 'inside'
			}
		}
	});
	
	var options = {
		$FillMode: 2,
		$AutoPlay: true,
		$AutoPlayInterval: 5000,
		$ArrowNavigatorOptions: {
			$Class: $JssorArrowNavigator$,
			$ChanceToShow: 2,
			$Scale: false
		}
	};
	var jssor_slider1 = new $JssorSlider$('slider1_container', options);
	
	
	//responsive code begin
	//you can remove responsive code if you don't want the slider scales
	//while window resizes
	function ScaleSlider() {
		var parentWidth = $('#slider1_container').parent().width();
		if (parentWidth < 600) {
			jssor_slider1.$ScaleWidth(parentWidth);
		} else
		window.setTimeout(ScaleSlider, 30);
	}
	//Scale slider after document ready
	ScaleSlider();
	
	//Scale slider while window load/resize/orientationchange.
	$(window).bind("load", ScaleSlider);
	$(window).bind("resize", ScaleSlider);
	$(window).bind("orientationchange", ScaleSlider);
	//responsive code end
})