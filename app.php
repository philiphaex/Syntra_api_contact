<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact api</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <?php
    echo $_SESSION['user_id'];
    ?>
    <h2>Search Form</h2>
    <form class="form-inline well" id="form-search">
        <div class="form-group">
            <label for="search">Search</label>
            <input name="search" type="text" class="form-control" id="search">
        </div>
        <input id='btn-search' name="btn-search" type="submit" value="Search" class="btn btn-primary">
    </form>
    <h2>Add Contact</h2>
    <form class="form-inline well" id="form-add">
        <div class="form-group">
            <label for="name">Name</label>
            <input name="name" type="text" class="form-control" id="name">
        </div>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input name="email" type="text" class="form-control" id="email">
        </div>
        <input id='btn-add' name="btn-add" type="submit" value="Add" class="btn btn-primary">
    </form>
    <div class="overzicht">
        <table class='table table-hover'>
            <thead>
            <tr>
                <th>Nr</th>
                <th>Naam</th>
                <th>E-mail</th>
            </tr>

            </thead>
            <tbody>


            </tbody>
    </div>


</div>
<script type="text/javascript">
    $(document).ready(function(){
        $.getJSON('./api/contact.php',function(contacts){
            $.each(contacts,function(index,contact){
//                $('tbody').append('<tr><td>' + index + '</td><td>' + contact.firstname + '</td><td>' + contact.email+'</td></tr>');
//                $('tbody').append('<tr><td>' + index + '</td><td>' + contact.firstname + '</td><td>' + contact.email+'</td></tr>');
            })
        });


        })




    //contact zoek
    $('#form-search').submit(function (e) {
        e.preventDefault();
        var query = $('#search').val();
        $.getJSON('./api/contact.php', {keyword: query});


    })


    //contact toevoegen
    $('#form-add').submit(function (e) {
        e.preventDefault();

        var data = {
            firstname: $('#name').val(),
            email: $('#email').val()
        }
        console.log(data);
        $.ajax({
            type: 'POST',
            url: './api/contact.php',
            data: data,
            dataType: 'json'
        })
    })

</script>


</body>
</html>
