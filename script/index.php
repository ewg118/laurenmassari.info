<?php 
	header ('Content-type: text/html; charset=utf-8');
	$id = $_GET['id'];
	if ($id=='index.html'){
		$id = 'index';
	}
	
	if (!isset($_GET['id'])){
		$display_path = '';
	} else { 
		$display_path = '../';
	}

	/* LOAD XML Document */
	$xmlDoc = new DOMDocument();
	$xmlDoc->load("../site.xml") or die('<html><head><title>DOM Error</title></head><body><h1>XML File Missing or Unparseable</h1></body></html>');
	$xpath = new DOMXpath($xmlDoc);
	$xpathquery = "//page[@id='" . $id . "']";
	$pages = $xpath->query($xpathquery);
	$links = $xmlDoc->getElementsByTagName('link');

	//write HTML
	$html = '<html>
	<head>
		<title>laurenmassari.info: ' . $pages->item(0)->getAttribute('title') . '</title>
		<!-- yui grids -->
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.12.0/build/cssgrids/cssgrids-min.css"/>

		<!-- jquery css dependencies -->
		<link href="' . $display_path .'css/jquery.fancybox-1.3.4.css" rel="stylesheet"/>
		<link href="' . $display_path .'css/jquery-ui-1.8.10.custom.css" rel="stylesheet"/>
		<link href="' . $display_path .'css/vader.css" rel="stylesheet"/>
		<link href="http://fonts.googleapis.com/css?family=Questrial" rel="stylesheet" type="text/css"/>

		<!-- local styling -->
		<link href="css/style.css" rel="stylesheet"/>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript" src="' . $display_path .'js/jquery-ui-1.8.15.custom.min.js"> //</script>
		<script type="text/javascript" src="' . $display_path .'js/jquery.fancybox-1.3.4.min.js"> //</script>
		<script type="text/javascript" src="' . $display_path .'js/functions.js"> //</script>';
		//google analytics
		$html .= "<script type=\"text/javascript\">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-5343246-10']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
	</script>";
	$html .= '</head>
	<body>
		<div class="yui3-g">
			<div class="yui3-u-1">
				<div id="hd">
					<div class="banner">LAUREN MARI MASSARI</div>
					<div class="sub-banner">
						<span><a href="mailto:lauren.massari@gmail.com">lauren.massari@gmail.com</a></span>
						<span class="divider">|</span>
						<span><a href="http://www.linkedin.com/in/laurenmassari"><img src="images/linkedin.png" alt="LinkedIn"/></a></span>
						<span><a href="http://www.flickr.com/photos/laurenmassari/"><img src="images/flickr.png" alt="flickr"/></a></span>
						<span><a href="http://twitter.com/lauren_massari"><img src="images/twitter.png" alt="twitter"/></a></span>
					</div>
				</div>
			</div>';
	//sidebar
	$allpages = $xmlDoc->getElementsByTagName('page');
	$html .= '<div class="yui3-u-1-3"><ul class="menu">';
	foreach ($allpages as $page){
		$html .= '<li><button id="' . $page->getAttribute('id') . '-button">' . $page->getAttribute('title') . '</button></li>';
	}
	//links
	foreach ($links as $link){
		$html .= '<li><a href="' . $link->getAttribute('href') . '"><button>' . $link->getAttribute('title') . '</button></a></li>';
	}
	$html .= '</ul></div>';
	
	//main content
	$html .= '<div class="yui3-u-2-3">
				<div class="content ui-corner-all">';
	//display current page
	foreach ($pages as $page){
		$serverName = "http://".$_SERVER['SERVER_NAME'];
		$html .= '<div id="' . $page->getAttribute('id') . '-content" class="project">';
		$html .= '<h1>' . $page->getAttribute('title') . '</h1>';
		$html .= '<h2>' . $page->getAttribute('subtitle') . '</h2>';
		
		//display description
		$description = $page->getElementsByTagName('p');
		foreach ($description as $p){
			$html .= '<p>' . $xmlDoc->saveXML($p) . '</p>';
		}
		
		//create links for portfolio
		$portfolios = $page->getElementsByTagName('portfolio');
		foreach ($portfolios as $portfolio){
			$html .= '<p><a href="' . $portfolio->getAttribute('href') . '">' . $portfolio->nodeValue . '</a></p>';
		}
		
		$html .= '<div class="images">';

		//display sections
		$sections = $page->getElementsByTagName('section');
		foreach($sections as $section){
			$html .= '<div class="section"><h3>' . $section->getAttribute('title') . '</h3>';
			
			//display images
			$images = $section->getElementsByTagName('image');
			foreach($images as $image){
				$type = $image->getAttribute('type');
				$href = $image->getAttribute('href');
				$imgPath = 'images/' . $id . '/' . $image->getAttribute('id');
				if (strlen($href) > 0){
					$html .= '<a href="' . $href . '" class="thumbLink" title="' . $image->getAttribute('title') . '" target="_blank">';
					$html .= '<img src="' . $imgPath . '-thumb.jpg" alt="image"/></a>';
				} else {
					if ($type != 'jp2'){
						$html .= '<a href="' . $imgPath . '.jpg" class="thumbImage" title="' . $image->getAttribute('title') . '" rel="gallery">';
						$html .= '<img src="' . $imgPath . '-thumb.jpg" alt="image"/></a>';
					} else {
						$html .= '<a href="' . $serverName . ':8080/adore-djatoka/viewer.html?rft_id=' . $serverName . '/images/' . $id . '/jp2/' . $image->getAttribute('id') . '.jp2" title="' . $image->getAttribute('title') . '" target="_blank" class="jp2Thumb">';
						$html .= '<img src="' . $imgPath . '-thumb.jpg" alt="image"/></a>';
					}
				}		
			}			
			$html .= '</div>';
		}
		$html .= '</div></div>';
	}
	//close main content
	$html .= '</div></div>';
	
	//footer
	$html .= '<div class="yui3-u-1" id="ft">';
	$html .= '<p>All images copyright ©2013 Lauren Massari</p>';
	$html .= '</div></div></body></html>';
	
	$doc = new DOMDocument();
	$doc->loadXML($html);
	//echo $doc->saveXML();
	echo $html;
?>