<?php
    session_start();

    include('./Connection_BDD.php');
    $conn = getBdd('localhost', 'root', 'sio2021');




    if($_SESSION['idr'] == 1) {
?>

<html>
    <head>
        <title>Profil Comptable</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="block">
            <header class="header navBarHeader">

                <a href="#" class="header-logo image is-96x96"><img src="images/bonsoir.png"/></a>
                <nav class="header-menu">
                    <a href="Valider_Fiche.php"><i class="fas fa-check"></i> Valider une fiche de frais</a>
                    <a href="#"><i class="fas fa-money-bill-wave"></i> Suivre le paiement d'une fiche de frais</a>
                    <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>

        <center>
            <div class="block">
                <h2 class="subtitle heading-site texteBienvenue animated jackInTheBox slow">Bienvenue <?php echo $_SESSION['nom'] . " " . $_SESSION ['prenom']?> !</h2>
                <div style="font-size: 0;">
                    <hr style="display: inline-block;" class="animated fadeInLeft slow">
                    <hr style="display: inline-block;" class="animated fadeInRight slow">
                </div>
                <div class="container about">
                    <div class="columns">
                        <div class="column about-single-element">
                            <br />
                            <p class="texteConnecteComme animated fadeInRight slow"> Vous êtes connecté en tant que <?php echo strtolower($_SESSION['NomRole']); ?></p>
                            <br />
                            <p class="siteProDe animated fadeInLeft slow">Ce site est le site professionnel de Galaxy Swiss Bourdin</p>
                        </div>
                    </div>
                    <p id="Certifications"></p>
                </div>
            </div>
        </center>
        <script src="./js/disconnectFiveMinutes.js"></script>
    </body>
</html>









<?php
    }
    if($_SESSION['idr'] == 2) {
?>

<html>
    <head>
        <title>Profil Visiteur</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>

        <div class="block">
            <header class="header navBarHeader">

                <a href="#" class="header-logo image is-96x96"><img src="images/bonsoir.png"/></a>
                <nav class="header-menu">
                    <a href="Renseigner_Fiche.php"><i class="fas fa-pen"></i> Renseigner mes fiches de frais</a>
                    <a href="Consulter_Fiche.php"><i class="fas fa-folder-open"></i> Consulter mes fiches de frais</a>
                    <a href="Saisir_Vehicule.php"><i class="fas fa-car"></i> Saisir un véhicule</a>
                    <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>

        <center>
            <div class="block">
                <h2 class="subtitle heading-site texteBienvenue animated jackInTheBox slow">Bienvenue <?php echo $_SESSION['nom'] . " " . $_SESSION ['prenom']?> !</h2>
                <div style="font-size: 0;">
                    <hr style="display: inline-block;" class="animated fadeInLeft slow">
                    <hr style="display: inline-block;" class="animated fadeInRight slow">
                </div>
                <div class="container about">
                    <div class="columns">
                        <div class="column about-single-element">
                            <br />
                            <p class="texteConnecteComme animated fadeInRight slow"> Vous êtes connecté en tant que <?php echo strtolower($_SESSION['NomRole']); ?></p>
                            <br />
                            <p class="siteProDe animated fadeInLeft slow">Ce site est le site professionnel de Galaxy Swiss Bourdin</p>



                            <?php
                            $reqAdmissible = $conn->prepare("SELECT * FROM fichefrais WHERE idVisiteur='".$_SESSION['id']."' AND mois='".date('Ym')."'");
                            $reqAdmissible->execute();

                            $isAdmissible = $reqAdmissible->rowCount();

                            if($isAdmissible == 1) {

                                $reqFraisAuForfait = $conn->prepare("SELECT idVisiteur, mois, idFraisForfait FROM lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".date('Ym')."' AND idFraisForfait='ETP'");
                                $reqFraisAuForfait->execute();

                                $isExistantAuForfait = $reqFraisAuForfait->rowCount();

                                $reqFraisHorsForfait = $conn->prepare("SELECT * FROM lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".date('Ym')."'");
                                $reqFraisHorsForfait->execute();

                                $isExistantHorsForfait = $reqFraisHorsForfait->rowCount();



                                if($isExistantHorsForfait < 1 && $isExistantAuForfait != 1) {

                                    echo "<br />
                                      <br />

                                      <div class='notification is-success animated rotateInUpRight slow'>
                                      Vous êtes admissible à une nouvelle fiche de frais ce mois-ci.
                                      </div>";

                                }

                                elseif($isExistantAuForfait == 1 && $isExistantHorsForfait < 1) {

                                    echo "<br />
                                      <br />

                                      <div class='notification is-warning animated rotateInUpRight slow'>
                                      Vous ne pouvez plus créer de <strong>fiche de frais au forfait</strong> mais vous pouvez toujours créer des <strong>fiches de frais hors forfait</strong>
                                      </div>";

                                }

                                elseif($isExistantAuForfait != 1 && $isExistantHorsForfait >= 1) {

                                    echo "<br />
                                      <br />

                                      <div class='notification is-warning animated rotateInUpRight slow'>
                                      Vous pouvez créer une <strong>fiche de frais au forfait</strong> et vous pouvez toujours créer des <strong>fiches de frais hors forfait</strong>
                                      </div>";

                                }

                                elseif($isExistantHorsForfait >= 1 && $isExistantAuForfait == 1) {

                                    echo "<br />
                                      <br />

                                      <div class='notification is-warning animated rotateInUpRight slow'>
                                      Vous ne pouvez plus créer de <strong>fiche de frais au forfait</strong> mais vous pouvez toujours créer des <strong>fiches de frais hors forfait</strong>
                                      </div>";

                                }





                            }

                            else {
                                echo "<br />
                                      <br />

                                      <div class='notification is-danger animated rotateInUpRight slow'>
                                      Vous n'êtes pas admissible à une nouvelle fiche de frais ce mois-ci.
                                      </div>";
                            }

                            ?>
                        </div>
                    </div>
                    <p id="Certifications"></p>
                </div>
            </div>
        </center>
        <script src="./js/disconnectFiveMinutes.js"></script>
    </body>
</html>













<?php
    }
    if($_SESSION['idr'] == 3) {
?>

<html>
    <head>
        <title>Profil Administrateur</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="block">
            <header class="header">
                <nav class="header-menu">
                    <a href="#"><i class="fas fa-pen"></i> Renseigner mes fiches de frais</a>
                    <a href="#"><i class="fas fa-folder-open"></i> Consulter mes fiches de frais</a>
                    <a href="#"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>
    </body>
</html>

<?php
    }
    if($_SESSION['idr'] == 4) {
?>

<html>
    <head>
        <title>Profil Direction</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>

        <div class="block">
            <header class="header navBarHeader">
                <a href="#" class="header-logo image is-96x96"><img src="images/bonsoir.png"/></a>
                <nav class="header-menu">
                    <a href="FicheSecteur.php"><i class="fas fa-pen"></i> Fiche par secteur</a>
                    <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>

        <center>
            <div class="block">
            <h2 class="subtitle heading-site texteBienvenue animated jackInTheBox slow">Bienvenue <?php echo $_SESSION['nom'] . " " . $_SESSION ['prenom']?> !</h2>
            <div style="font-size: 0;">
                <hr style="display: inline-block;" class="animated fadeInLeft slow">
                <hr style="display: inline-block;" class="animated fadeInRight slow">
            </div>
            <div class="container about">
                <div class="columns">
                    <div class="column about-single-element">
                        <br />
                        <p class="texteConnecteComme animated fadeInRight slow"> Vous êtes connecté en tant que <?php echo strtolower($_SESSION['NomRole']); ?></p>
                        <br />
                        <p class="siteProDe animated fadeInLeft slow">Ce site est le site professionnel de Galaxy Swiss Bourdin</p>
                    </div>
                </div>
                <p id="Certifications"></p>
            </div>
        </div>
    </center>
    <script src="./js/disconnectFiveMinutes.js"></script>
    </body>
</html>


<?php
    }
?>
