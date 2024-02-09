<?php
use repositories\UserRepository;

require_once ("../repositories/UserRepository.php");

$userRepository = new UserRepository();
if (isset($_SESSION["user_id"]))
{
    $user = $userRepository->getById($_SESSION["user_id"]);
}
else
{
    $user = null;
}
?>

<nav>
    <p>
        <a href="gallery">Gallery</a>
        <a href="add-image">Add-Image</a>
        <a href="saved-images">Saved-Images</a>
        <a href="search">Search</a>
    </p>
    <p>
        <?php
        if ($user)
        {
            echo "User:" . $user["login"];
            echo " <a href='logout'>Logout</a>";
        }
        else
        {
            echo "<a href='login'>Login</a> <a href='register'>Register</a>";
        }
        ?></p>
</nav>