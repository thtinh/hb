<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

include_once 'templateconfig.php';

$document =& JFactory::getDocument();
$document->setTitle($webtitle." - ".$document->title);

//Framework CSS
$document->addStyleSheet("templates/vxg_hb/css/style.php",'text/css',"screen");
$document->addStyleSheet("templates/vxg_hb/blueprint/print.css",'text/css',"print");
$document->addScript("templates/vxg_hb/js/hb.js");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

    <head>
        
        <jdoc:include type="head" />


        <!--[if IE]><link rel="stylesheet" href="<?php echo $this->baseurl;?>/templates/vxg_hb/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->

        <!--[if lt IE 8]>

        <link href="<?php echo $this->baseurl;?>/templates/vxg_hb/css/ie.css" rel="stylesheet" type="text/css" media="screen" />

        <![endif]-->

        <!--[if lte IE 6]>

        <style type="text/css">

        #logo,.trans,#services a, #footer{ behavior: url(templates/vxg_hb/css/iepngfix.htc);}

        </style>

        <script type="text/javascript" src="<?php echo $this->baseurl;?>/templates/vxg_hb/js/iepngfix_tilebg.js"></script>

        <![endif]-->
        
        <!--[if IE]>
         <script type="text/javascript" src="<?php echo $this->baseurl;?>/templates/vxg_hb/js/border-radius-iefix.js"></script>
        <![endif]-->
       
        <script type="text/javascript" src="<?php echo $this->baseurl;?>/templates/vxg_hb/js/hb.js"></script>
        <script type="text/javascript" src="<?php echo $this->baseurl;?>/templates/vxg_hb/js/tooltips.js"></script>
      
    </head>

    <body>

        <div id="wrapper">

            <div class="curve container shadow">

                <div id="logo-wrapper" class="span-7"><a href="#"><img class="trans" id="logo" src="<?php echo $this->baseurl;?>/templates/vxg_hb/images/logo.png" alt="Hoa Binh Village" /></a></div>

                <div class="span-17 last">
                    <div id="navbar">
                        <jdoc:include type="modules" name="menu" style="none"/>
                    </div>
                    <div id="lang"><a id="en" class="lang-link" href="#"><img src="images/gb.gif"/></a></div>
                </div>

                <?php if ( $this->countModules('top')) : ?>
                <div id="top" class="span-24 last">
                    <jdoc:include type="modules" name="top" style="none" />
                </div>
                <?php endif; ?>

                <div id="content-wrap" class="span-24 last">

                    <div class="<?php echo ($this->countModules('right'))? "span-17" : "span-24 last"; ?>">
                        <div id="content">
                            <?php if ($this->countModules('breadcrumbs')&&($_REQUEST['view'] != 'frontpage')) : ?>
                            <div id="breadcrumbs">
                                <jdoc:include type="modules" name="breadcrumbs" />
                            </div>
                            <?php endif; ?>
                            <?php if ($this->countModules('left')) : ?>
                            <div id="left">
                                <jdoc:include type="modules" name="left" style="withtitle"/>
                            </div>
                            <?php endif; ?>
                            <jdoc:include type="message" />
                            <jdoc:include type="component" />
                        </div>
                    </div>

                    <?php if($this->countModules('right')) : ?>
                    <div id="right-modules" class="span-7 last">
                        <jdoc:include type="modules" name="right" style="withtitle"/>
                    </div>
                    <?php endif; ?>

                </div>

                <div id="wrapper-footer" class="span-24 last">
                    <div id="footer">
                        <div id="footer-l" class="span-14">
                            123
                        </div>
                        <div id="footer-r" class="span-10 last">
                            456
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <jdoc:include type="modules" name="debug" />
    </body>
</html>