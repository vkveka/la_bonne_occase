<?php
include 'head.php';
include 'functions.php';
session_start();
?>

<body class="bg-light">
    <header>
        <img src="./images/bannière_fintosfinal_v2.jpg" alt="photo_header"">

        <!-- 
            Attribut pour thème noir navbar
            data-bs-theme=" dark" -->

        <nav class="navbar navbar-expand-lg sticky-top" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item mx-5">
                            <a class="nav-link" href="#carousel_for_sale">Véhicules vendus</a>
                        </li>
                        <li class="nav-item mx-5">
                            <a class="nav-link" href="#">Véhicules disponible à la vente</a>
                        </li>
                        <li class="nav-item mx-5">
                            <a class="nav-link" href="#my_contact">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>



    <main class="pt-5">
        <div class="row text-center justify-content-center">
            <div class="typing col-md-6 flex-column">
                <div class="typing-effect mb-5">
                    Véhicules vendus :
                </div>

                <?php
                getPicture(getArticles());
                ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 mt-5">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-sm-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">ACHAT</h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                                <i class="fa-solid fa-sack-dollar fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-sm-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">VENTE</h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                                <i class="fa-solid fa-hand-holding-dollar fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-sm-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">REPRISE</h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                                <i class="fa-solid fa-right-left fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    if (isset($_POST['deconnect'])) {
        $_SESSION = [];
        echo "<script> alert(\"Déconnexion réussie !\");</script>";
    }
    include 'footer.php'; ?>





    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</body>

</html>