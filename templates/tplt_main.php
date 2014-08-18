<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <meta name="keywords" content="<?= $keywords ?>" />
    <meta name="description" content="<?= $description ?>" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" value="notranslate" />
    
    <link rel="stylesheet" type="text/css" href="css/basic_01.css?<?php echo date("c"); ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?= ROOT_PATH ?><?= $extra ?>" media="screen" />
    <link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
</head>
<body>
    <div id="header" class="clearfix">
        <div id="logo">
            <h2><a href="index.php?pge=dashboard">Gym Manager</a></h2>
        </div>
        <div id="mnu_header">
            <?= $mnu_header; ?>
        </div>
    </div>
    <div id="main">
        <?= $content; ?>
    </div>
    <div id="bottom">
        <div id="footer">
            <?= $footer; ?>
        </div>
    </div>

<!-- Config data for all JS scripts -->
<div style="display:none;" id="JSconfig"><?= $JSconfig; ?></div>

<!-- Javascript files
Grab Google CDN's jQuery. Fall back to local if necessary 
-->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>!window.jQuery && document.write('<script src="<?= $pathRoot ?>js/jquery-1.11.1.min.js"><\/script>')</script>
<script src="js/jquery-ui-1.10.4.custom.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/gymmngr.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>