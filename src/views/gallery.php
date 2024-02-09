<?php
include ("includes/navbar.inc.php");
include ("includes/footer.inc.php");
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Gallery</title>
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body>
<form method="post" id="gallery-form">
    <?php if ($model['images']) : ?>
        <div id="gallery">
            <?php
            foreach ($model['images'] as $image)
            {
                echo "<div>";
                echo "<a href='image?id=" . $image["_id"] . "'><img src='images/" . $image['_id'] . "-mini." . $image['type'] .  "'></a>";
                echo "<p>Title: " . $image['title'] . "</p>";
                echo "<p>Author: " . $image['author'] . "</p>";
                echo "<p>Visibility: " . ($image['public'] ? "Public" : "Private") . "</p>";
                echo "<input type='checkbox' id='check-" . $image['_id'] . "' name='check-" . $image['_id'] . "'" . ((isset($_SESSION['saved_images']) && in_array($image['_id'], $_SESSION['saved_images'])) ? "checked disabled" : "") . ">";
                echo "<label for='check-" . $image['_id'] . "'>Select</label>";
                echo "</div>";
            }
            ?>
        </div>
        <div id="save-selected-button">
            <button type='submit'>Save selected</button>
        </div>
    <?php else : ?>
        <p>No images found.</p>
    <?php endif; ?>
</form>

<div id="pagination">
    <p>
        <?php if ($model['page'] > 1) : ?>
            <a href="gallery?page=<?php echo $model['page']-1; ?>">Previous</a>
        <?php endif; ?>

        Page: <?php echo $model['page']; ?>

        <?php if ($model['nextPageImages'] != null) : ?>
            <a href="gallery?page=<?php echo $model['page']+1; ?>">Next</a>
        <?php endif; ?>
    </p>
</div>
</body>
</html>