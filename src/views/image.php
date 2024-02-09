<?php
include 'includes/navbar.inc.php';
include 'includes/footer.inc.php';
?>

<!doctype HTML>
<html lang="en">
<head>
    <title>Image</title>
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body>
<div id="image">
    <?php if ($model['imageFound']) : ?>
        <?php if ($model['canView']) : ?>
            <img src="images/<?php echo $model['image']['_id']; echo "-watermark."; echo $model['image']['type'] ?>" alt="Image" />
        <?php else: ?>
            <h2>You don't have permission to view this image</h2>
        <?php endif; ?>
    <?php else: ?>
        <h2>Image not found</h2>
    <?php endif; ?>
</div>
</body>
</html>