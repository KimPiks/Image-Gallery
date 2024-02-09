<?php
include 'includes/navbar.inc.php';
include 'includes/footer.inc.php';
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Saved images</title>
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body>
<?php if (isset($model['saved_images'])) : ?>
<form method="post" id="gallery-form">
        <div id="gallery">
            <?php
            foreach ($model['saved_images'] as $image)
            {
                echo "<div>";
                echo "<a href='image?id=" . $image["_id"] . "'><img src='images/" . $image['_id'] . "-mini." . $image['type'] .  "'></a>";
                echo "<p>Title: " . $image['title'] . "</p>";
                echo "<p>Author: " . $image['author'] . "</p>";
                echo "<p>Visibility: " . ($image['public'] ? "Public" : "Private") . "</p>";
                echo "<input type='checkbox' id='check-" . $image['_id'] . "' name='check-" . $image['_id'] . "'>";
                echo "<label for='check-" . $image['_id'] . "'>Select</label>";
                echo "</div>";
            }
            ?>
        </div>
        <div id="save-selected-button">
            <button type='submit'>Remove from saved</button>
        </div>
</form>
<?php else: ?>
    <p id='message' style="text-align:center; padding-top: 10vh; margin: 0;">No images selected</p>
<?php endif; ?>
</body>
</html>