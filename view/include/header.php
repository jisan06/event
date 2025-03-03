<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Event Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="d-flex flex-column min-vh-100 wrapper">
    <nav class="bg-light navbar navbar-expand-lg navbar-light px-5">
        <a class="navbar-brand" href="<?= BASE_URL ?>">Event Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
                    if( !isset($_SESSION['user']) ){
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>register">Register</a>
                    </li>
                <?php }else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>events">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>logout">Logout</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
    <div class="main-content pb-5 mt-5">
