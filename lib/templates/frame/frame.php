<?php if($show){ ?>
<html prefix="og: http://ogp.me/ns#">

<head>
    <title>
        UploadX - a ShareX proxy
    </title>
    <meta property="og:image" content="<?php echo $src ; ?>" />
</head>

<body>
    <div id='display_div' class="center center_text center_content">
            <?php } else { ?>

        <center style="font-family:Verdana;">
            <?php include __DIR__.'/../display/info_panel.php' ?>
        </center>

    </div>
</body>

</html>

<?php } ?>