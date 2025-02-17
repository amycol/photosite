<?php include 'php/viewer.php';?>
<!DOCTYPE html>

<head>
    <title>Viewer | photosite</title>
    <link rel="stylesheet" href="css/viewer.css">
</head>

<body>
    <div class="top"><a href="<?php echo $exitUrl ?>">x</a></div>
    <div class="viewer">
        <div class="img-flexcontainer">
            <div class="img-flexchild sidebuttonscontainer">
                <?php echo $leftButton?>
            </div>
            <div class="img-flexchild image">
                <img src="http://img.pstest.local:5503/full/<?php echo $id ?>f.webp">
            </div>
            <div class="img-flexchild sidebuttonscontainer">
                <?php echo $rightButton?>
            </div>
        </div>
        <div class="imginfo">
            <div class="flexcontainer">
                <div class="flexchild">
                    <p class="data"><?php echo $shutter ?></p>
                    <p class="label">Shutter</p>
                </div>
                <div class="flexchild">
                    <p class="data"><?php echo $aperture ?></p>
                    <p class="label">Aperture</p>
                </div>
                <div class="flexchild">
                    <p class="data"><?php echo $iso ?></p>
                    <p class="label">ISO</p>
                </div>
            </div>
            <div class="bottom-flexcontainer left">
                <div class="bottom-flexchild">
                    <p class="ex-label">Camera</p>
                    <p class="ex-data"><?php echo $camera ?></p>
                </div>
                <div class="bottom-flexchild right">
                    <p class="ex-label">Lens</p>
                    <p class="ex-data"><?php echo $lens ?></p>
                </div>
                <div class="bottom-flexchild left">
                    <p class="ex-label">Category</p>
                    <p class="ex-data"><?php echo $category ?></p>
                </div>
                <div class="bottom-flexchild right">
                    <p class="ex-label">Location</p>
                    <p class="ex-data"><?php echo $location ?></p>
                </div>
                <div class="bottom-flexchild left">
                    <p class="ex-label">Focal Length</p>
                    <p class="ex-data"><?php echo $focallength ?></p>
                </div>
                <div class="bottom-flexchild right">
                    <p class="ex-label">Time Description</p>
                    <p class="ex-data"><?php echo $timedesc ?></p>
                </div>
                <div class="bottom-flexchild left">
                    <p class="ex-label">Time</p>
                    <p class="ex-data"><?php echo $time ?></p>
                </div>
                <div class="bottom-flexchild right">
                    <p class="ex-label">Date</p>
                    <p class="ex-data"><?php echo $date ?></p>
                </div>
                <div class="bottom-flexchild left">
                    <p class="ex-label">Resolution</p>
                    <p class="ex-data"><?php echo $resolution ?></p>
                </div>
                <div class="bottom-flexchild right">
                    <p class="ex-label">ID</p>
                    <p class="ex-data"><?php echo $id ?></p>
                </div>
            </div>
            <?php echo $extrainfotag ?>
        </div>
    </div>
</body>