<!DOCTYPE html>
<html>
    <head>
        <title>Blog</title>

        <?php
        echo $this->Html->css(array(
            '../backend/css/bootstrap.min.css',
            'prettyPhoto.css',
            'custom.css',
        ));
        ?> 
        <?php
        echo $this->Html->script(array(
            'jquery-1.6.1.min.js',
            'jquery.prettyPhoto.js',
            'custom.js',
        ));
        ?>
    </head>
    <body class='index'>
        <div id="outer-wrapper" class="index home">
            <div class='row' id='content-wrapper'>
                <div id='main-wrapper'>
                    <div class='main section' id='main'>
                        <div class='widget Blog' data-version='1' id='Blog1'>
                            <div class='blog-posts hfeed'>
                                <div class="date-outer">
                                    <div class="date-posts">
                                        <?php echo $this->fetch('content'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class='clear'></div>
                        </div>
                    </div>
                </div>
                <div id='sidebar-wrapper'>
                    <div class='sidebar section' id='sidebar'>
                        <?php echo $this->element('author'); ?>
                        <?php echo $this->element('popularposts', array('popularBlogs' => $popularBlogs)); ?>
                    </div>
                </div>
                <div style='clear: both;'></div>
            </div>
        </div>
    </body>
</html>

<style>
    a[rel="prettyPhoto"] div {
        background: #000 url("<?php echo PROJECT_URL; ?>img/zoom-in.png") no-repeat scroll 50% 54%;
        color: #fff;       
        left: 0;
        opacity: 0.5;
        top: 0;
        width: 100%;
    }    
</style>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $("a[rel^='prettyPhoto']").prettyPhoto();

        $(".prettyPhoto").mouseenter(function () {
            $(this).find('div.vlb_zoom').css('display', 'block');
        }).mouseleave(function () {
            $(this).find('div.vlb_zoom').css('display', 'none');
        });
    });
</script>