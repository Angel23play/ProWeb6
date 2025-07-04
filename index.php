<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea 3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="./Styles/style.css">
</head>

<body>

    <?php include("./Views/Components/navbar.php"); ?>
    
   <div class="container mt-5">

       <?php
    // esto maneja las acciones del switch
    $vista  = $_GET['vista'] ?? 'home';




    switch ($vista) {
        case 'about':
            include('./Views/about.php');
            break;
        case 'home':
            include('./Views/home.php');
            break;

        }
        ?>

</div>

    <?php include("./Views/Components/footer.php"); ?>



</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>

</html>