<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'head.php';
session_start();
?>

<body>
    <?php
    include 'functions.php';
    ?>
    <img src="./images/bannière_fintosfinal_v2.jpg" alt="photo_header" id="photo_header">
    <!-- 
    Attribut pour thème noir navbar
    data-bs-theme=" dark" -->

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item mx-md-5 m-0">
                        <a class="nav-link" href="#dispo">Véhicules disponible à la vente</a>
                    </li>
                    <li class="nav-item mx-md-5 m-0">
                        <a class="nav-link " href="#forsell_section">Véhicules vendus</a>
                    </li>
                    <li class="nav-item mx-md-5 m-0">
                        <a class="nav-link" href="#my_contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <main>

        <!-- Section bloc d'informations : ACHAT & VENTE -->
        <div class="row justify-content-center bloc_achat_vente">
            <div class="card col-lg-4 m-5">
                <div class="card-header">
                    <h4 class="card-title"><b>VENTE DE VEHICULES TOUTES MARQUES</b></h4>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Tout nos véhicules sont vendus révisés avec un contrôle technique de moins de 6 mois et garantis.</p>
                </div>
                <div class="card-footer text-center">
                    <i class="fa-solid fa-sack-dollar fa-2x"></i>
                </div>
            </div>

            <div class="card col-lg-4 m-5">
                <div class="card-header">
                    <h4 class="card-title"><b>VENTE</b></h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                </div>
                <div class="card-footer text-center">
                    <i class="fa-solid fa-hand-holding-dollar fa-2x"></i>
                </div>
            </div>
        </div>

        <!-- Affiche les voitures disponibles à la vente -->
        <section id="dispo">
            <div class="col-md-6 mx-auto text-center title_dispo">
                <h1>Actuellement disponible à la vente</h1>
            </div>
            <div class="col-lg-10 mx-auto">
                <div class="row justify-content-center">
                    <?php
                    showPictureForSellIndex(getArticlesForSellIndex());
                    ?>
                </div>
            </div>
        </section>

        <div id="forsell_section" class="col-8 mx-auto mb-5" style="border-top: 2px solid white"></div>

        <section id="sold">
            <div class="col-md-6 bg-carousel mx-auto">
                <div class="row justify-content-around">

                    <div class="col-md-10 mx-auto text-center title_dispo">
                        <h1>Véhicules vendus récemment</h1>
                    </div>
                    <!-- Affiche le carousel des voitures vendues -->
                    <div class="typing col-md-8 my-auto justify-content-center">
                        <?php
                        showPictureSold(getArticlesSold());
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php
    if (isset($_POST['deconnect'])) {
        $_SESSION = [];
        // echo "<script> alert(\"Déconnexion réussie !\");</script>";
    }
    include 'footer.php'; ?>




    <!-- Scripts JS Bootstrap -->

    <!-- **************************************************************************** -->
    <!-- LE LIEN JS DE BOOTSTRAP SE TROUVE DANS LE FICHIER functions.php, BAS DE PAGE -->
    <!-- **************************************************************************** -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script> -->
</body>

</html>