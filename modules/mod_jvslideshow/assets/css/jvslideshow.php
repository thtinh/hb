<?php
header("Content-type: text/css; charset: UTF-8");
$id = $_GET['id'];
$slide = $_GET['slide'];
$height = $_GET['height'];
$width = $_GET['width'];
$background = $_GET['background'];
?>

#mySlideshow<?php echo $id; ?>,
#mySlideshowSet<?php echo $id; ?>,
#flickrSlideshow<?php echo $id; ?>
{
	width: <?php echo $width; ?>px;
	height: <?php echo $height; ?>px;
	z-index: 5;
}

#flickrSlideshow<?php echo $id; ?>
{
	width: 500px;
	height: 334px;
}

#mySlideshow<?php echo $id; ?> img.thumbnail, 
#mySlideshowSet<?php echo $id; ?> img.thumbnail
{
	display: none;
}

.jdSlideshow
{
	overflow: hidden;
	position: relative;
}

.jdSlideshow img
{
	border: 0;
	margin: 0;
}

.jdSlideshow .slideElement
{
	width: 100%;
	height: 100%;
	background-color: #<?php echo $background; ?>;
	background-repeat: no-repeat;
	background-position: center center;
	background-image: url('../images/loading.gif');
}

.jdSlideshow .loadingElement
{
	width: 100%;
	height: 100%;
	position: absolute;
	left: 0;
	top: 0;
	background-color: #<?php echo $background; ?>;
	background-repeat: no-repeat;
	background-position: center center;
	background-image: url('../images/loading.gif');
}

.jdSlideshow .slideInfoZone
{
	position: absolute;
	z-index: 10;
	width: 100%;
	margin: 0px;
	left: 0;
	bottom: 0;
	height: 70px;
	background: #333;
	color: #fff;
	text-indent: 0;
	overflow: hidden;
	display: block;
}

* html .jdSlideshow .slideInfoZone
{
	bottom: -1px;
}

.jdSlideshow .slideInfoZone h2
{
	padding: 0;
	font-family: Tahoma;
	font-size: 100%;
	margin: 0;
	margin: 2px 5px;
	font-weight: bold;
	color: #EFEFEF;
}

.jdSlideshow .slideInfoZone p
{
	padding: 0;
	font-size: 100%;
	margin: 2px 5px;
	color: #eee;
}

.jdSlideshow div.carouselContainer
{
	position: absolute;
	height: 135px;
	width: 100%;
	z-index: 10;
	margin: 0px;
	left: 0;
	top: 0;
}

.jdSlideshow a.carouselBtn
{
	position: absolute;
	bottom: 0;
	right: 0px;
	height: 20px;
	/*width: 100px; background: url('../images/carousel_btn.gif') no-repeat;*/
	text-align: center;
	padding: 0 10px;
	font-size: 13px;
	background: #333;
	color: #fff;
	cursor: pointer;
}

.jdSlideshow .carousel
{
	position: absolute;
	width: 100%;
	margin: 0px;
	left: 0;
	top: 0;
	height: 115px;
	background: #333;
	color: #fff;
	text-indent: 0;
	overflow: hidden;
}

.jdExtCarousel
{
	overflow: hidden;
	position: relative;
}

.jdSlideshow .carousel .carouselWrapper, .jdExtCarousel .carouselWrapper
{
	position: absolute;
	width: 100%;
	height: 78px;
	top: 10px;
	left: 0;
	overflow: hidden;
}

.jdSlideshow .carousel .carouselInner, .jdExtCarousel .carouselInner
{
	position: relative;
}

.jdSlideshow .carousel .carouselInner .thumbnail, .jdExtCarousel .carouselInner .thumbnail
{
	cursor: pointer;
	background: #000;
	background-position: center center;
	float: left;
	border: solid 1px #fff;
}

.jdSlideshow .wall .thumbnail, .jdExtCarousel .wall .thumbnail
{
	margin-bottom: 10px;
}

.jdSlideshow .carousel .label, .jdExtCarousel .label
{
	font-size: 13px;
	position: absolute;
	bottom: 5px;
	left: 10px;
	padding: 0;
	margin: 0;
}

.jdSlideshow .carousel .wallButton, .jdExtCarousel .wallButton
{
	font-size: 10px;
	position: absolute;
	bottom: 5px;
	right: 10px;
	padding: 1px 2px;
	margin: 0;
	background: #222;
	border: 1px solid #888;
	cursor: pointer;
}

.jdSlideshow .carousel .label .number, .jdExtCarousel .label .number
{
	color: #b5b5b5;
}

.jdSlideshow a
{
	font-size: 100%;
	text-decoration: none;
	color: inherit;
}

.jdSlideshow a.right, .jdSlideshow a.left
{
	position: absolute;
	height: 99%;
	width: 25%;
	cursor: pointer;
	z-index:10;
	filter:alpha(opacity=20);
	-moz-opacity:0.2;
	-khtml-opacity: 0.2;
	opacity: 0.2;
}

* html .jdSlideshow a.right, * html .jdSlideshow a.left
{
	filter:alpha(opacity=50);
}

.jdSlideshow a.right:hover, .jdSlideshow a.left:hover
{
	filter:alpha(opacity=80);
	-moz-opacity:0.8;
	-khtml-opacity: 0.8;
	opacity: 0.8;
}

.jdSlideshow a.left
{
	left: 0;
	top: 0;
	background: url('../images/fleche1.png') no-repeat center left;
}

* html .jdSlideshow a.left { background: url('../images/fleche1.gif') no-repeat center left; }

.jdSlideshow a.right
{
	right: 0;
	top: 0;
	background: url('../images/fleche2.png') no-repeat center right;
}

* html .jdSlideshow a.right { background: url('../images/fleche2.gif') no-repeat center right; }

.jdSlideshow a.open
{
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
}

.withArrows a.open
{
	position: absolute;
	top: 0;
	left: 25%;
	height: 99%;
	width: 50%;
	cursor: pointer;
	z-index: 10;
	background: none;
	-moz-opacity:0.8;
	-khtml-opacity: 0.8;
	opacity: 0.8;
}

.withArrows a.open:hover {}

/* Slideshow Sets */

.jdSlideshow a.SlideshowSelectorBtn
{
	z-index: 15;
	position: absolute;
	top: 0;
	left: 30px;
	height: 20px;
	/*width: 100px; background: url('../images/carousel_btn.gif') no-repeat;*/
	text-align: center;
	padding: 0 10px;
	font-size: 13px;
	background: #333;
	color: #fff;
	cursor: pointer;
	opacity: .4;
	-moz-opacity: .4;
	-khtml-opacity: 0.4;
	filter:alpha(opacity=40);
}

.jdSlideshow .SlideshowSelector
{
	z-index: 20;
	width: 100%;
	height: 100%;
	position: absolute;
	top: 0;
	left: 0;
	background: #000;
}

.jdSlideshow .SlideshowSelector h2
{
	margin: 0;
	padding: 10px 20px 10px 20px;
	font-size: 20px;
	line-height: 30px;
	color: #fff;
}

.jdSlideshow .SlideshowSelector .SlideshowSelectorWrapper
{
	overflow: hidden;
}

.jdSlideshow .SlideshowSelector .SlideshowSelectorInner div.SlideshowButton
{
	margin-left: 10px;
	margin-top: 10px;
	border: 1px solid #888;
	padding: 5px;
	height: 40px;
	color: #fff;
	cursor: pointer;
	float: left;
}

.jdSlideshow .SlideshowSelector .SlideshowSelectorInner div.hover
{
	background: #333;
}

.jdSlideshow .SlideshowSelector .SlideshowSelectorInner div.SlideshowButton div.preview
{
	background: #000;
	background-position: center center;
	float: left;
	border: none;
	width: 40px;
	height: 40px;
	margin-right: 5px;
}

.jdSlideshow .SlideshowSelector .SlideshowSelectorInner div.SlideshowButton h3
{
	margin: 0;
	padding: 0;
	font-size: 12px;
	font-weight: normal;
}

.jdSlideshow .SlideshowSelector .SlideshowSelectorInner div.SlideshowButton p.info
{
	margin: 0;
	padding: 0;
	font-size: 12px;
	font-weight: normal;
	color: #aaa;
}

/********* LAYOUT ************/

.content
{
	margin: 0;
	width: 100%;
}

.content a
{
	color: #fff;
}


.content p.linkage
{
	margin-top: 2em;
	text-align: right;
	font-size: 1.7em;
	color: #ddd;
}

.content p.linkage a { color: #fff; }

/*.content p.linkage a
{
	color: #fff;
	background: url('../images/bg/biglink_off.gif') center right no-repeat;
	padding: 10px 20px;
	text-decoration: none;
}

.content p.linkage a:hover
{
	background: url('../images/bg/biglink_on.gif') center right no-repeat;
	font-style: italic;
}*/

#mySlideshow
{
	text-align: left;
	margin: 0 auto;
}