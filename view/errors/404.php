<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            padding-top: 50px;
        }

        .content {
            max-width: 505px;
            margin-left: auto;
            margin-right: auto;
        }

        .content .text {
            text-align: center;
            font-size: 100%;
            font-size: 4em;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="image">
        <img src="<?php echo $this->asset('image/error.png'); ?>"/>
    </div>
    <div class="text">
        404 Not Found
    </div>
</div>
</body>
</html>
