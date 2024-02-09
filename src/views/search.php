<?php
include 'includes/navbar.inc.php';
include 'includes/footer.inc.php';
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Search</title>
    <link rel="stylesheet" href="static/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<div id="search-container">
    <input id="search" type="text" placeholder="Image title">
</div>

<form method="post" id="gallery-form">
    <div id="gallery"></div>
</form>

</body>
<script>
    let query = false;
    search();

    $("#search").on( "keyup", function() {
        search();
    } );

    function search()
    {
        if (query)
            return;
        query = true;

        $("#gallery").empty();
        var phrase = $("#search").val();
        $.ajax({
            type: 'GET',
            url: "search-ajax?phrase=" + phrase,
            dataType: 'json',
            success: function(data){
                $.each(data, function(index, element) {
                    $("#gallery").append("<div>" +
                        "<img src='images/" + element._id.$oid + "-mini." + element.type + "'>" +
                        "<p>Title: " + element.title + "</p>" +
                        "<p>Author: " + element.author + "</p>" +
                        "<p>Visibility: " + ((element.public) ? "Public" : "Private") + "</p>" +
                        "</div>");
                })
                query = false;
            }});
    }
</script>
</html>