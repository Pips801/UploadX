<?php if($show){ ?>
<html prefix="og: http://ogp.me/ns#">

<head>
    <title>
        UploadX - a ShareX proxy
    </title>
    <meta property="og:image" content="<?php echo $src ; ?>" />
</head>

<body>
    <div>
        <center>
            <?php } else { ?>
        </center>

        <center style="font-family:Verdana;">
            <?php if($is_admin){ echo( "Uploader: $uploader<br>"); echo( "IP: $uploader_ip"); } ?>
        </center>

    </div>
</body>

</html>
<?php } ?>