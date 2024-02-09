<?php
include 'includes/navbar.inc.php';
include 'includes/footer.inc.php';
?>

<!doctype HTML>
<html lang="en">
<head>
    <title>Image upload</title>
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body>
   <div id="form">
       <form enctype="multipart/form-data" method="post">
           <label for="imageForm">Image</label>
           <input type="file" id="imageForm" name="image" accept="image/png, image/jpeg" required /><br>
           <label for="title">Title</label>
           <input type="text" id="title" name="title" placeholder="Title"><br>
           <label for="watermark">Watermark</label>
           <input type="text" id="watermark" name="watermark" placeholder="Watermark" required /><br>
           <label for="author">Author</label>
           <input type="text" id="author" name="author" placeholder="Author" <?php if (isset($model['user']) && $model['user']) echo "value='" . $model['username'] . "' readonly "?> required /><br>
           <label for="private">Private</label>
           <input type="checkbox" id="private" name="private" <?php if (!isset($model['user']) || !$model['user']) echo "disabled"; ?> /><br>
           <input type="submit" value="Upload" />
           <?php if (isset($model['error-message'])) : ?>
               <p id="message"><?= $model['error-message'] ?></p>
           <?php endif; ?>
       </form>
   </div>
</body>
</html>