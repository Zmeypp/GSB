<?php
session_start();
include('./Connection_BDD.php');
$conn = getBdd('localhost', 'root', 'sio2021');

// CHEVAUX FISCAUX UTILISATEUR RECUPERATION DONNEES EXISTANTES :

$reqRecupChevauxFiscauxUser = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$_SESSION['id']."'");
$reqRecupChevauxFiscauxUser->execute();
while($RecupChevauxFiscauxUser = $reqRecupChevauxFiscauxUser->fetch()) {
    $ChevauxFiscauxUser[] = $RecupChevauxFiscauxUser['chevauxFiscaux'];
}

// AU FORFAIT RECUPERATION DES DONNEES EXISTANTES :

$reqNombreLigneDonneesAuForfait = $conn->prepare("SELECT count(*) from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."'");
$reqNombreLigneDonneesAuForfait->execute();

while($RecupNombreLigneDonneesAuForfait = $reqNombreLigneDonneesAuForfait->fetch()) {
    $NombreLigneDonneesAuForfait[] = $RecupNombreLigneDonneesAuForfait['count(*)'];
}

$reqRecupMoisFicheAuForfait = $conn->prepare("SELECT distinct(mois) from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."'");
$reqRecupMoisFicheAuForfait->execute();
while($RecupMoisFicheAuForfait = $reqRecupMoisFicheAuForfait->fetch()) {
    $MoisFicheAuForfait[] = $RecupMoisFicheAuForfait['mois'];
}

$reqRecupMoisETPFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND idFraisForfait='ETP'");
$reqRecupMoisETPFicheAuForfait->execute();
while($RecupMoisETPFicheAuForfait = $reqRecupMoisETPFicheAuForfait->fetch()) {
    $MoisETPFicheAuForfait[] = $RecupMoisETPFicheAuForfait['mois'];
}

$reqRecupMoisKMFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND idFraisForfait='KM'");
$reqRecupMoisKMFicheAuForfait->execute();
while($RecupMoisKMFicheAuForfait = $reqRecupMoisKMFicheAuForfait->fetch()) {
    $MoisKMFicheAuForfait[] = $RecupMoisKMFicheAuForfait['mois'];
}

$reqRecupMoisNUIFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND idFraisForfait='NUI'");
$reqRecupMoisNUIFicheAuForfait->execute();
while($RecupMoisNUIFicheAuForfait = $reqRecupMoisNUIFicheAuForfait->fetch()) {
    $MoisNUIFicheAuForfait[] = $RecupMoisNUIFicheAuForfait['mois'];
}

$reqRecupMoisREPFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND idFraisForfait='REP'");
$reqRecupMoisREPFicheAuForfait->execute();
while($RecupMoisREPFicheAuForfait = $reqRecupMoisREPFicheAuForfait->fetch()) {
    $MoisREPFicheAuForfait[] = $RecupMoisREPFicheAuForfait['mois'];
}

for($i = 0; $i < count($MoisETPFicheAuForfait); $i++) {
    $reqRecupEtatETPFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisETPFicheAuForfait[$i]."' AND idFraisForfait='ETP'");
    $reqRecupEtatETPFicheAuForfait->execute();
    while($RecupEtatETPFicheAuForfait = $reqRecupEtatETPFicheAuForfait->fetch()) {
        $EtatETPFicheAuForfait[] = $RecupEtatETPFicheAuForfait['idEtat'];
    }
}

for($i = 0; $i < count($MoisKMFicheAuForfait); $i++) {
    $reqRecupEtatKMFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisKMFicheAuForfait[$i]."' AND idFraisForfait='KM'");
    $reqRecupEtatKMFicheAuForfait->execute();
    while($RecupEtatKMFicheAuForfait = $reqRecupEtatKMFicheAuForfait->fetch()) {
        $EtatKMFicheAuForfait[] = $RecupEtatKMFicheAuForfait['idEtat'];
    }
}

for($i = 0; $i < count($MoisNUIFicheAuForfait); $i++) {
    $reqRecupEtatNUIFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisNUIFicheAuForfait[$i]."' AND idFraisForfait='NUI'");
    $reqRecupEtatNUIFicheAuForfait->execute();
    while($RecupEtatNUIFicheAuForfait = $reqRecupEtatNUIFicheAuForfait->fetch()) {
        $EtatNUIFicheAuForfait[] = $RecupEtatNUIFicheAuForfait['idEtat'];
    }
}

for($i = 0; $i < count($MoisREPFicheAuForfait); $i++) {
    $reqRecupEtatREPFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisREPFicheAuForfait[$i]."' AND idFraisForfait='REP'");
    $reqRecupEtatREPFicheAuForfait->execute();
    while($RecupEtatREPFicheAuForfait = $reqRecupEtatREPFicheAuForfait->fetch()) {
        $EtatREPFicheAuForfait[] = $RecupEtatREPFicheAuForfait['idEtat'];
    }
}


$reqRecupQuantiteETP = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='ETP' AND idVisiteur='".$_SESSION['id']."'");
$reqRecupQuantiteETP->execute();
while($RecupQuantiteETP = $reqRecupQuantiteETP->fetch()) {
    $QuantiteETP[] = $RecupQuantiteETP['quantite'];
}

$reqRecupQuantiteKM = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='KM' AND idVisiteur='".$_SESSION['id']."'");
$reqRecupQuantiteKM->execute();
while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
    $QuantiteKM[] = $RecupQuantiteKM['quantite'];
}

$reqRecupQuantiteNUI = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='NUI' AND idVisiteur='".$_SESSION['id']."'");
$reqRecupQuantiteNUI->execute();
while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
    $QuantiteNUI[] = $RecupQuantiteNUI['quantite'];
}

$reqRecupQuantiteREP = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='REP' AND idVisiteur='".$_SESSION['id']."'");
$reqRecupQuantiteREP->execute();
while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
    $QuantiteREP[] = $RecupQuantiteREP['quantite'];
}



// HORS FORFAIT RECUPERATION DES DONNEES EXISTANTES :

$reqRecupAllIdHorsForfait = $conn->prepare("SELECT id FROM gsb_frais.lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."'");
$reqRecupAllIdHorsForfait->execute();
while($RecupAllIdHorsForfait = $reqRecupAllIdHorsForfait->fetch()) {
    $AllIdHorsForfait[] = $RecupAllIdHorsForfait['id'];
}

// ACTIONS AU CLIC DES DIFFERENTS BOUTONS


for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {

    if(isset($_POST['supprimerAuForfait'.$MoisFicheAuForfait[$i]])) {
        $reqSupprimerAuForfaitETP = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='ETP';");
        $reqSupprimerAuForfaitETP->execute();

        $reqSupprimerAuForfaitKM = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='KM';");
        $reqSupprimerAuForfaitKM->execute();

        $reqSupprimerAuForfaitNUI = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='NUI';");
        $reqSupprimerAuForfaitNUI->execute();

        $reqSupprimerAuForfaitREP = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='REP';");
        $reqSupprimerAuForfaitREP->execute();

        header("Location: Consulter_Fiche.php");
    }

}

for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {

    if(isset($_POST['modifierAuForfait'.$MoisFicheAuForfait[$i]])) {

        $reqModifierAuForfaitETP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['ETP'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='ETP';");
        $reqModifierAuForfaitETP->execute();

        $reqModifierAuForfaitKM = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['KM'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='KM';");
        $reqModifierAuForfaitKM->execute();

        $reqModifierAuForfaitNUI = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['NUI'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='NUI';");
        $reqModifierAuForfaitNUI->execute();

        $reqModifierAuForfaitREP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['REP'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$_SESSION['id']."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='REP';");
        $reqModifierAuForfaitREP->execute();


        header("Location: Consulter_Fiche.php");
    }

}

for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {

    if(isset($_POST['pdfAuForfait'.$MoisFicheAuForfait[$i]])) {

        if($ChevauxFiscauxUser[0] <= 3) {
            $RembourseKM = $QuantiteKM[$i] * 0.456;
        }
        if($ChevauxFiscauxUser[0] == 4) {
            $RembourseKM = $QuantiteKM[$i] * 0.523;
        }
        if($ChevauxFiscauxUser[0] == 5) {
            $RembourseKM = $QuantiteKM[$i] * 0.548;
        }
        if($ChevauxFiscauxUser[0] == 6) {
            $RembourseKM = $QuantiteKM[$i] * 0.574;
        }
        if($ChevauxFiscauxUser[0] >= 7) {
            $RembourseKM = $QuantiteKM[$i] * 0.601;
        }
        $RembourseREP = $QuantiteREP[$i] * 15;
        $RembourseNUI = $QuantiteNUI[$i] * 40;
        $RembourseTotal = $RembourseKM + $RembourseNUI + $RembourseREP;



        ob_start();
        require('./fpdf/fpdf.php');

        global $pdf;
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image('./images/bonsoir.png', 85, 2, 50, 30);
        $pdf->Ln(20);


        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetFillColor(38, 196, 236);
        $pdf->SetXY(0, 35);
        $pdf->Cell(210, 20, 'Fiche de frais au forfait', 0, 1, 'L', 1);
        $pdf->SetXY(50, 35);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(110, 10, 'Entreprise Galaxy Swiss Bourdin', 0, 1, 'C', 1);
        $pdf->SetXY(50, 45);
        $pdf->Cell(110, 10, 'Adresse de Galaxy Swiss Bourdin', 0, 1, 'C', 1);
        $pdf->SetXY(0, 58);
        $pdf->Cell(210, 50, '', 0, 1, 'C', 1);
        $pdf->SetXY(0, 60);
        $pdf->Cell(210, 10, 'Nom du proprietaire de la fiche : '.$_SESSION["nom"].' '.$_SESSION["prenom"].'', 0, 1, 'L', 1);
        $pdf->SetXY(0, 72);
        $pdf->Cell(210, 10, 'Matricule du proprietaire de la fiche : '.$_SESSION["id"].'', 0, 1, 'L', 1);
        $pdf->SetXY(0, 84);
        $pdf->Cell(210, 10, 'Role du proprietaire de la fiche : '.$_SESSION['NomRole'].'', 0, 1, 'L', 1);
        $pdf->SetXY(0, 96);
        $pdf->Cell(210, 10, 'Adresse du proprietaire de la fiche : '.$_SESSION['adresse'].', '.$_SESSION['cp'].' '.$_SESSION['ville'].'', 0, 1, 'L', 1);
        $pdf->SetXY(78, 138);
        $pdf->Cell(50, 8, 'Montant rembourse de cette fiche', 1, 0, 'C', 1);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetTextColor(0);
        $pdf->SetFillColor(38, 196, 236);
        $pdf->SetXY(10, 110);
        $pdf->Cell(35,8,'Nombre d\'etape',1,0,'C',1);
        $pdf->SetXY(45, 110);
        $pdf->Cell(35,8,'Nombre de kilometre',1,0,'C',1);
        $pdf->SetXY(80, 110);
        $pdf->Cell(35,8,'Nombre de nuitees',1,0,'C',1);
        $pdf->SetXY(115, 110);
        $pdf->Cell(37,8,'Nombre de repas au midi',1,0,'C',1);
        $pdf->SetXY(152, 110);
        $pdf->Cell(35,8,'Mois concerne',1,0,'C',1);
        $pdf->SetFillColor(206, 206, 206);
        $pdf->SetXY(10, 118);
        $pdf->Cell(35, 8, ''.$_POST["ETP".$MoisFicheAuForfait[$i]].'', 1, 0, 'C', 1);
        $pdf->SetXY(45, 118);
        $pdf->Cell(35, 8, ''.$_POST["KM".$MoisFicheAuForfait[$i]].'', 1, 0, 'C', 1);
        $pdf->SetXY(80, 118);
        $pdf->Cell(35, 8, ''.$_POST["NUI".$MoisFicheAuForfait[$i]].'', 1, 0, 'C', 1);
        $pdf->SetXY(115, 118);
        $pdf->Cell(37, 8, ''.$_POST["REP".$MoisFicheAuForfait[$i]].'', 1, 0, 'C', 1);
        $pdf->SetXY(152, 118);
        $pdf->Cell(35, 8, ''.$MoisFicheAuForfait[$i].'', 1, 0, 'C', 1);
        $pdf->SetXY(78, 146);
        $pdf->Cell(50, 8, ''.$RembourseTotal.chr(128).'', 1, 0, 'C', 1);

        $pdf->Output();
        ob_end_flush();
    }

}

foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
    if(isset($_POST['supprimerHorsForfait'.$IdFicheHorsForfait])) {
        $reqSupprimerHorsForfait = $conn->prepare("DELETE FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."';");
        $reqSupprimerHorsForfait->execute();

        header("Location: Consulter_Fiche.php");
    }
}

foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
    if(isset($_POST['modifierHorsForfait'.$IdFicheHorsForfait])) {
        $reqModifierHorsForfait = $conn->prepare("UPDATE gsb_frais.lignefraishorsforfait SET libelle='".$_POST['libelle'.$IdFicheHorsForfait]."', `date`='".$_POST['date'.$IdFicheHorsForfait]."', montant='".$_POST['montant'.$IdFicheHorsForfait]."' WHERE id='".$IdFicheHorsForfait."';");
        $reqModifierHorsForfait->execute();

        header("Location: Consulter_Fiche.php");
    }
}
$CompteurNav = 0;
foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
    $reqRecupMoisHorsForfait = $conn->prepare("SELECT mois FROM gsb_frais.lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."' AND id='".$IdFicheHorsForfait."'");
    $reqRecupMoisHorsForfait->execute();
    while($RecupMoisHorsForfait = $reqRecupMoisHorsForfait->fetch()) {
        $MoisHorsForfait[] = $RecupMoisHorsForfait['mois'];
    }
    if(isset($_POST['pdfHorsForfait'.$IdFicheHorsForfait])) {



        ob_start();
        require('./fpdf/fpdf.php');
        global $pdf;
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image('./images/bonsoir.png', 85, 2, 50, 30);
        $pdf->Ln(20);


        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetFillColor(38, 196, 236);
        $pdf->SetXY(0, 35);
        $pdf->Cell(210, 20, 'Fiche de frais hors forfait', 0, 1, 'L', 1);
        $pdf->SetXY(50, 35);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(110, 10, 'Entreprise Galaxy Swiss Bourdin', 0, 1, 'C', 1);
        $pdf->SetXY(50, 45);
        $pdf->Cell(110, 10, 'Adresse de Galaxy Swiss Bourdin', 0, 1, 'C', 1);
        $pdf->SetXY(0, 58);
        $pdf->Cell(210, 50, '', 0, 1, 'C', 1);
        $pdf->SetXY(0, 60);
        $pdf->Cell(210, 10, 'Nom du proprietaire de la fiche : '.$_SESSION["nom"].' '.$_SESSION["prenom"].'', 0, 1, 'L', 1);
        $pdf->SetXY(0, 72);
        $pdf->Cell(210, 10, 'Matricule du proprietaire de la fiche : '.$_SESSION['id'].'', 0, 1, 'L', 1);
        $pdf->SetXY(150, 78);
        $pdf->Cell(60, 8, 'Numero de la fiche de frais : '.$IdFicheHorsForfait.'', 0, 1, 'C', 1);
        $pdf->SetXY(0, 84);
        $pdf->Cell(210, 10, 'Role du proprietaire de la fiche : '.$_SESSION['NomRole'].'', 0, 1, 'L', 1);
        $pdf->SetXY(0, 96);
        $pdf->Cell(210, 10, 'Adresse du proprietaire de la fiche : '.$_SESSION['adresse'].', '.$_SESSION['cp'].' '.$_SESSION['ville'].'', 0, 1, 'L', 1);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetTextColor(0);
        $pdf->SetFillColor(38, 196, 236);
        $pdf->SetXY(15, 110);
        $pdf->Cell(60,8,'Libelle',1,0,'C',1);
        $pdf->SetXY(75, 110);
        $pdf->Cell(60,8,'Mois concerne (YYYYMM)',1,0,'C',1);
        $pdf->SetXY(135, 110);
        $pdf->Cell(60,8,'Montant en euro',1,0,'C',1);
        $pdf->SetFillColor(206, 206, 206);
        $pdf->SetXY(15, 118);
        $pdf->Cell(60, 8, ''.$_POST["libelle".$IdFicheHorsForfait].'', 1, 0, 'C', 1);
        $pdf->SetXY(75, 118);
        $pdf->Cell(60, 8, ''.$MoisHorsForfait[$CompteurNav].'', 1, 0, 'C', 1);
        $pdf->SetXY(135, 118);
        $pdf->Cell(60, 8, ''.$_POST["montant".$IdFicheHorsForfait].chr(128).'', 1, 0, 'C', 1);

        $pdf->Output();
        ob_end_flush();
    }
    $CompteurNav += 1;
}

?>

<html>
    <head>
        <title>Consulter</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="./lib/animate/animate.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap">
    </head>

    <body>

    <div class="block">
        <header class="header navBarHeader">
            <a href="#" class="header-logo image is-96x96"><img src="images/bonsoir.png"/></a>
            <nav class="header-menu">
                <a href="profil.php"><i class="fas fa-home"></i> Accueil</a>
                <a href="Renseigner_Fiche.php"><i class="fas fa-pen"></i> Renseigner mes fiches de frais</a>
                <a href="Saisir_Vehicule.php"><i class="fas fa-car"></i> Saisir un véhicule</a>
                <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
            </nav>
        </header>
    </div>

    <center>

        <!-- FRAIS AU FORFAIT-->

        <div class="block animated fadeInDown slow decadeDiv">
            <div class="field">
                <h1>Vos fiches de frais au forfait</h1>
            </div>
        </div>


        <!-- AFFICHAGE DES DONNEES -->


        <?php

        if($NombreLigneDonneesAuForfait[0] > 1) {
            echo '<table class="table animated fadeInDown slow">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Nombre d\'étapes</th>';
            echo '<th>Nombre de kilomètres</th>';
            echo '<th>Nombre de nuitées</th>';
            echo '<th>Nombre de repas au midi</th>';
            echo '<th>Mois concerné (YYYYMM)</th>';
            echo '<th>Montant remboursé</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {
                echo '<tr>';
                echo '<form method="post">';
                if($ChevauxFiscauxUser[0] <= 3) {
                    $RembourseKM = $QuantiteKM[$i] * 0.456;
                }
                if($ChevauxFiscauxUser[0] == 4) {
                    $RembourseKM = $QuantiteKM[$i] * 0.523;
                }
                if($ChevauxFiscauxUser[0] == 5) {
                    $RembourseKM = $QuantiteKM[$i] * 0.548;
                }
                if($ChevauxFiscauxUser[0] == 6) {
                    $RembourseKM = $QuantiteKM[$i] * 0.574;
                }
                if($ChevauxFiscauxUser[0] >= 7) {
                    $RembourseKM = $QuantiteKM[$i] * 0.601;
                }
                $RembourseREP = $QuantiteREP[$i] * 15;
                $RembourseNUI = $QuantiteNUI[$i] * 40;
                $RembourseTotal = $RembourseKM + $RembourseNUI + $RembourseREP;
                if($EtatETPFicheAuForfait[$i] == "DE" AND $EtatKMFicheAuForfait[$i] == "DE" AND $EtatNUIFicheAuForfait[$i] == "DE" AND $EtatREPFicheAuForfait[$i] == "DE") {
                    echo '<td><input class="input" type="text" name="ETP'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteETP[$i].'" /></td>';
                    echo '<td><input class="input" type="text" name="KM'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteKM[$i].'" /></td>';
                    echo '<td><input class="input" type="text" name="NUI'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteNUI[$i].'" /></td>';
                    echo '<td><input class="input" type="text" name="REP'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteREP[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="moisConcerne'.$MoisFicheAuForfait[$i].'" value="'.$MoisFicheAuForfait[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="rembourseTotal'.$MoisFicheAuForfait[$i].'" value="'.$RembourseTotal.'" /></td>';
                    echo '<td>';
                    echo '<button class="button is-info" name="modifierAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                    echo '<i class="fas fa-pen"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-danger" name="supprimerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                    echo '<i class="fas fa-trash"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-link" formtarget="_blank" name="pdfAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                    echo '<i class="fas fa-file-pdf"></i>';
                    echo '</button>';
                    echo '</td>';
                }
                elseif($EtatETPFicheAuForfait[$i] == "RE" AND $EtatKMFicheAuForfait[$i] == "RE" AND $EtatNUIFicheAuForfait[$i] == "RE" AND $EtatREPFicheAuForfait[$i] == "RE") {
                    echo '<td><input class="input" type="text" name="ETP'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteETP[$i].'" /></td>';
                    echo '<td><input class="input" type="text" name="KM'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteKM[$i].'" /></td>';
                    echo '<td><input class="input" type="text" name="NUI'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteNUI[$i].'" /></td>';
                    echo '<td><input class="input" type="text" name="REP'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteREP[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="moisConcerne'.$MoisFicheAuForfait[$i].'" value="'.$MoisFicheAuForfait[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="rembourseTotal'.$MoisFicheAuForfait[$i].'" value="'.$RembourseTotal.'" /></td>';
                    echo '<td>';
                    echo '<button class="button is-info" name="modifierAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                    echo '<i class="fas fa-pen"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-danger" name="supprimerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                    echo '<i class="fas fa-trash"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-link" formtarget="_blank" name="pdfAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                    echo '<i class="fas fa-file-pdf"></i>';
                    echo '</button>';
                    echo '</td>';
                }
                else {
                    echo '<td><input class="input" type="text" disabled="disabled" name="ETP'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteETP[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="KM'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteKM[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="NUI'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteNUI[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="REP'.$MoisFicheAuForfait[$i].'" value="'.$QuantiteREP[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="moisConcerne'.$MoisFicheAuForfait[$i].'" value="'.$MoisFicheAuForfait[$i].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" value="'.$RembourseTotal.'" /></td>';
                    echo "<td>";
                    echo "<div class='notification is-success'>";
                    echo "Cette fiche de frais a été validée.";
                    echo "</div>";
                    echo "</td>";
                }
                echo '</form>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else {
            echo "<div style='width: 50vh;'class='notification is-danger'>";
            echo "Aucune fiche de frais au forfait.";
            echo "</div>";
        }

        ?>


        <!-- FRAIS HORS FORFAIT-->

        <div class="block animated fadeInDown slow decadeDiv">
            <div class="field">
                <h1>Vos fiches de frais hors forfait</h1>
            </div>
        </div>


        <!-- AFFICHAGE DES DONNEES -->

        <?php

        if(count($AllIdHorsForfait) >= 1) {
            echo '<table class="table animated fadeInDown slow" style="margin-bottom: 5vh;">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Libelle</th>';
            echo '<th>Mois concerné(YYYYMM)</th>';
            echo '<th>Montant en €</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $CompteurNaviguerListe = 0;
            foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
                echo '<tr>';
                echo '<form method="post">';


                $reqEtatFicheHorsForfait = $conn->query("SELECT idEtat FROM gsb_frais.lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."' AND id='".$IdFicheHorsForfait."'");
                    while($EtatFicheHorsForfait = $reqEtatFicheHorsForfait->fetch()) {
                        $EtatDeLaFicheHorsForfait[] = $EtatFicheHorsForfait['idEtat'];
                    }

                $reqRecupMoisHorsForfait = $conn->prepare("SELECT mois FROM gsb_frais.lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."' AND id='".$IdFicheHorsForfait."'");
                $reqRecupMoisHorsForfait->execute();
                while($RecupMoisHorsForfait = $reqRecupMoisHorsForfait->fetch()) {
                    $MoisHorsForfait[] = $RecupMoisHorsForfait['mois'];
                }

                $reqRecupLibelleHorsForfait = $conn->prepare("SELECT libelle FROM gsb_frais.lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."' AND id='".$IdFicheHorsForfait."'");
                $reqRecupLibelleHorsForfait->execute();
                while($RecupLibelleHorsForfait = $reqRecupLibelleHorsForfait->fetch()) {
                    $LibelleHorsForfait[] = $RecupLibelleHorsForfait['libelle'];
                }


                $reqRecupDateHorsForfait = $conn->prepare("SELECT date FROM gsb_frais.lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."' AND id='".$IdFicheHorsForfait."'");
                $reqRecupDateHorsForfait->execute();
                while($RecupDateHorsForfait = $reqRecupDateHorsForfait->fetch()) {
                    $DateHorsForfait[] = $RecupDateHorsForfait['date'];
                }


                $reqRecupMontantHorsForfait = $conn->prepare("SELECT montant FROM gsb_frais.lignefraishorsforfait WHERE idVisiteur='".$_SESSION['id']."' AND id='".$IdFicheHorsForfait."'");
                $reqRecupMontantHorsForfait->execute();
                while($RecupMontantHorsForfait = $reqRecupMontantHorsForfait->fetch()) {
                    $MontantHorsForfait[] = $RecupMontantHorsForfait['montant'];
                }

                if($EtatDeLaFicheHorsForfait[$CompteurNaviguerListe] == "DE") {
                    echo '<td><input class="input" type="text" name="libelle'.$IdFicheHorsForfait.'" value="'.$LibelleHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="mois'.$IdFicheHorsForfait.'" value="'.$MoisHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td><input class="input" type="text" name="montant'.$IdFicheHorsForfait.'" value="'.$MontantHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td>';
                    echo '<button class="button is-info" name="modifierHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                    echo '<i class="fas fa-pen"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-danger" name="supprimerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                    echo '<i class="fas fa-trash"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-link" formtarget="_blank" name="pdfHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                    echo '<i class="fas fa-file-pdf"></i>';
                    echo '</button>';
                    echo '<td><input class="input" type="hidden" name="date'.$IdFicheHorsForfait.'" value="'.$DateHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '</td>';
                }
                elseif($EtatDeLaFicheHorsForfait[$CompteurNaviguerListe] == "RE") {
                    echo '<td><input class="input" type="text" name="libelle'.$IdFicheHorsForfait.'" value="'.$LibelleHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="mois'.$IdFicheHorsForfait.'" value="'.$MoisHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td><input class="input" type="text" name="montant'.$IdFicheHorsForfait.'" value="'.$MontantHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td>';
                    echo '<button class="button is-info" name="modifierHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                    echo '<i class="fas fa-pen"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-danger" name="supprimerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                    echo '<i class="fas fa-trash"></i>';
                    echo '</button>';
                    echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                    echo '<button class="button is-link" formtarget="_blank" name="pdfHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                    echo '<i class="fas fa-file-pdf"></i>';
                    echo '</button>';
                    echo '<td><input class="input" type="hidden" name="date'.$IdFicheHorsForfait.'" value="'.$DateHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '</td>';
                }
                else {
                    echo '<td><input class="input" type="text" disabled="disabled" name="libelle'.$IdFicheHorsForfait.'" value="'.$LibelleHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="mois'.$IdFicheHorsForfait.'" value="'.$MoisHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo '<td><input class="input" type="text" disabled="disabled" name="montant'.$IdFicheHorsForfait.'" value="'.$MontantHorsForfait[$CompteurNaviguerListe].'" /></td>';
                    echo "<td>";
                    echo "<div class='notification is-success'>";
                    echo "Cette fiche de frais a été validée.";
                    echo "</div>";
                    echo '<td><input class="input" type="hidden" disabled="disabled" name="date'.$IdFicheHorsForfait.'" value="'.date('Y-m-d').'" /></td>';
                    echo "</td>";
                }
                $CompteurNaviguerListe += 1;
                echo '</form>';
                echo '</tr>';
            }
            echo '</tbody>';
        }
        else {
            echo "<div style='width: 50vh;'class='notification is-danger'>";
            echo "Aucune fiche de frais hors forfait.";
            echo "</div>";
        }

        ?>

    </center>

    <script src="./js/disconnectFiveMinutes.js"></script>
    </body>
</html>
