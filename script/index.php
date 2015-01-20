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
	$links = $xmlDoc->getElementsByTagName('link');
        $page = $xpath->query("//page[@id='" . $id . "']")->item(0);

?>
<DOCTYPE html>
<html lang="en">
	<head>
		<title>laurenmassari.info: <?php $page->getAttribute('title') ?></title>
		
		

		<!-- jquery css dependencies -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js">//</script>
		<script type="text/javascript" src="<?php echo $display_path . 'js/jquery.fancybox.pack.js?v=2.1.5'?>"> //</script>
		<script type="text/javascript" src="<?php echo $display_path . 'js/functions.js'?>"> //</script>
		
		<!-- css -->
		<link href="<?php echo $display_path . 'css/jquery.fancybox.css?v=2.1.5' ?>" rel="stylesheet"/>
		<link href="<?php echo $display_path . 'ccss/jquery-ui-1.8.10.custom.css' ?>" rel="stylesheet"/>
		<link href="http://fonts.googleapis.com/css?family=Spinnaker|Noto+Sans" rel="stylesheet" type="text/css"/>
		
		<!-- bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js">//</script>

		<!-- local styling -->
		<link href="<?php echo $display_path . 'css/style.css' ?>" rel="stylesheet"/>
		
		
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-5343246-10']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
	</head>
	<body>
		<?php 
			$navbar = render_navbar($id, $display_path, $xpath);
			echo $navbar;
		?>
               
                
            
		<div class="container">
                     <?php /* //jumbotron
                    if ($id == 'index'){ ?>
                        <div class="jumbotron">
                            <h1>Hello, world!</h1>
                            <p>scrolling images heres</p>
                        </div>
                    <?php } */
                ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sub-banner text-right">
                                    <span><a href="mailto:lauren.massari@gmail.com">lauren.massari@gmail.com</a></span>
                                    <span class="divider">|</span>
                                    <span><a href="http://www.linkedin.com/in/laurenmassari"><img src="images/linkedin.png" alt="LinkedIn"/></a></span>
                                    <span><a href="http://www.flickr.com/photos/laurenmassari/"><img src="images/flickr.png" alt="flickr"/></a></span>
                                    <span><a href="http://twitter.com/lauren_massari"><img src="images/twitter.png" alt="twitter"/></a></span>
                            </div>
                        </div>
                    </div>
                    
                    <?php $sections = $page->getElementsByTagName('section'); ?>
                    <div class="row"> 
                       <div class="col-md-<?php echo ($sections->length == 0 && $id != 'index') ? '12' : '6'; ?>">
                            <h1><?php echo $page->getAttribute('title'); ?></h1>
                            <h2><?php echo $page->getAttribute('subtitle'); ?></h2>
                            <?php $description = $page->getElementsByTagName('p');
                                   foreach ($description as $p){ ?>
                            <p><?php echo $xmlDoc->saveXML($p) ?></p>
                                   <?php } ?>

                            <?php $portfolios = $page->getElementsByTagName('portfolio');
                                foreach ($portfolios as $portfolio){ ?>
                                <p>
                                    <a href="<?php echo $portfolio->getAttribute('href'); ?>">
                                        <?php echo $portfolio->nodeValue?>
                                    </a>
                                </p>
                                <?php } ?>
                        </div>
                        
                        <?php if ($sections->length > 0) { ?>
                            <div class="col-md-6">
                                <?php foreach($sections as $section){ ?>
                                <div class="row section">
                                     <?php if (strlen($section->getAttribute('title')) > 0) { ?>
                                    <div class="col-md-12 text-right">
                                        <h3>
                                            <?php echo $section->getAttribute('title'); ?>
                                        </h3>
                                    </div>
                                     <?php } ?>
                                    <?php $images = $section->getElementsByTagName('image');
                                        foreach($images as $image){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 text-center pull-right">
                                            <?php $type = $image->getAttribute('type');
                                            $href = $image->getAttribute('href');
                                            $imgPath = 'images/' . $id . '/' . $image->getAttribute('id');
                                            if (strlen($href) > 0){ ?>
                                                <a href="<?php echo $href; ?>" class="thumbLink" title="<?php echo $image->getAttribute('title'); ?>" target="_blank">
                                                    <img src="<?php echo $imgPath . '-thumb.jpg'; ?>" alt="image"/>
                                                </a>
                                            <?php } else {
                                                if ($type != 'jp2'){ ?>
                                                <a href="<?php echo $imgPath . '.jpg'; ?>" class="thumbImage" title="<?php echo $image->getAttribute('title'); ?>" rel="gallery">
                                                    <img src="<?php echo $imgPath . '-thumb.jpg'; ?>" alt="image"/>
                                                </a>
                                                <?php } else { ?>
                                                <a href="<?php echo $serverName . ':8080/adore-djatoka/viewer.html?rft_id=' . $serverName . '/images/' . $id . '/jp2/' . $image->getAttribute('id') . '.jp2'; ?>" title="<?php echo $image->getAttribute('title'); ?>" target="_blank" class="jp2Thumb">
                                                    <img src="<?php echo $imgPath . '-thumb.jpg'; ?>" alt="image"/>
                                                </a>
                                                <?php }
                                            } ?>
                                            </div>
                                        <?php } ?>
                                </div>
                            <?php } ?> 
                        </div>
                    <?php } else if ($id == 'index'){ ?>
                        <div class="col-md-6 text-center"><img src="images/portrait.jpg" style="max-width:100%;margin-top:60px;"/></div>
                    <?php } ?>
		</div>
            </div>
	</body>
</html>

<?php 

//FUNCTIONS
function render_navbar($id, $display_path, $xpath){
	$navbar = '<nav class="navbar navbar-default">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="' . $display_path . '">laurenmassari.info</a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">';

        $navbar .= '<li class="dropdown">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Professional <span class="caret"></span></a>
             <ul class="dropdown-menu" role="menu">';

        //professional projects
        $professionalProjects = $xpath->query("descendant::page[@type='professional']");
        foreach ($professionalProjects as $page) {
                $navbar .= '<li><a href="' . $display_path . $page->getAttribute('id') . '">' . $page->getAttribute('title') . '</a></li>';
        }         
        $navbar .= '</ul></li>';
        
        //academic
        $navbar .= '<li class="dropdown">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Academic <span class="caret"></span></a>
             <ul class="dropdown-menu" role="menu">';

        $academicProjects = $xpath->query("descendant::page[@type='academic']");
        foreach ($academicProjects as $page) {
                $navbar .= '<li><a href="' . $display_path . $page->getAttribute('id') . '">' . $page->getAttribute('title') . '</a></li>';
        }         
        $navbar .= '</ul></li>';

        //cv
        $navbar .= '<li><a href="' . $display_path . 'cv.pdf">Curriculum Vitae</a></li>';
        $navbar .= '</ul></div></div></nav>';

	return $navbar;       
}


?>
<?php /*
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
	
	//$doc = new DOMDocument();
	//$doc->loadXML($html);
	//echo $doc->saveXML();
	echo $html;
	*/
?>