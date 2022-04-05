<?php
    session_start();
    include('./Connection_BDD.php');
    $conn = getBdd('localhost', 'root', 'sio2021');

    // CHEVAUX FISCAUX UTILISATEUR RECUPERATION DONNEES EXISTANTES :

    $reqRecupChevauxFiscauxUser = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur");
    $reqRecupChevauxFiscauxUser->execute();
    while($RecupChevauxFiscauxUser = $reqRecupChevauxFiscauxUser->fetch()) {
        $ChevauxFiscauxUser[] = $RecupChevauxFiscauxUser['chevauxFiscaux'];
    }

    // AU FORFAIT RECUPERATION DES DONNEES EXISTANTES :

    $reqNombreLigneDonneesAuForfait = $conn->prepare("SELECT count(*) from gsb_frais.lignefraisforfait");
    $reqNombreLigneDonneesAuForfait->execute();

    while($RecupNombreLigneDonneesAuForfait = $reqNombreLigneDonneesAuForfait->fetch()) {
        $NombreLigneDonneesAuForfait[] = $RecupNombreLigneDonneesAuForfait['count(*)'];
    }

    $reqRecupMoisFicheAuForfait = $conn->prepare("SELECT distinct(mois) from gsb_frais.lignefraisforfait");
    $reqRecupMoisFicheAuForfait->execute();
    while($RecupMoisFicheAuForfait = $reqRecupMoisFicheAuForfait->fetch()) {
        $MoisFicheAuForfait[] = $RecupMoisFicheAuForfait['mois'];
    }

    $reqRecupMoisETPFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idFraisForfait='ETP'");
    $reqRecupMoisETPFicheAuForfait->execute();
    while($RecupMoisETPFicheAuForfait = $reqRecupMoisETPFicheAuForfait->fetch()) {
        $MoisETPFicheAuForfait[] = $RecupMoisETPFicheAuForfait['mois'];
    }

    $reqRecupMoisKMFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idFraisForfait='KM'");
    $reqRecupMoisKMFicheAuForfait->execute();
    while($RecupMoisKMFicheAuForfait = $reqRecupMoisKMFicheAuForfait->fetch()) {
        $MoisKMFicheAuForfait[] = $RecupMoisKMFicheAuForfait['mois'];
    }

    $reqRecupMoisNUIFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idFraisForfait='NUI'");
    $reqRecupMoisNUIFicheAuForfait->execute();
    while($RecupMoisNUIFicheAuForfait = $reqRecupMoisNUIFicheAuForfait->fetch()) {
        $MoisNUIFicheAuForfait[] = $RecupMoisNUIFicheAuForfait['mois'];
    }

    $reqRecupMoisREPFicheAuForfait = $conn->prepare("SELECT mois from gsb_frais.lignefraisforfait WHERE idFraisForfait='REP'");
    $reqRecupMoisREPFicheAuForfait->execute();
    while($RecupMoisREPFicheAuForfait = $reqRecupMoisREPFicheAuForfait->fetch()) {
        $MoisREPFicheAuForfait[] = $RecupMoisREPFicheAuForfait['mois'];
    }

    for($i = 0; $i < count($MoisETPFicheAuForfait); $i++) {
        $reqRecupEtatETPFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE mois='".$MoisETPFicheAuForfait[$i]."' AND idFraisForfait='ETP'");
        $reqRecupEtatETPFicheAuForfait->execute();
        while($RecupEtatETPFicheAuForfait = $reqRecupEtatETPFicheAuForfait->fetch()) {
            $EtatETPFicheAuForfait[] = $RecupEtatETPFicheAuForfait['idEtat'];
        }
    }

    for($i = 0; $i < count($MoisKMFicheAuForfait); $i++) {
        $reqRecupEtatKMFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE mois='".$MoisKMFicheAuForfait[$i]."' AND idFraisForfait='KM'");
        $reqRecupEtatKMFicheAuForfait->execute();
        while($RecupEtatKMFicheAuForfait = $reqRecupEtatKMFicheAuForfait->fetch()) {
            $EtatKMFicheAuForfait[] = $RecupEtatKMFicheAuForfait['idEtat'];
        }
    }

    for($i = 0; $i < count($MoisNUIFicheAuForfait); $i++) {
        $reqRecupEtatNUIFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE mois='".$MoisNUIFicheAuForfait[$i]."' AND idFraisForfait='NUI'");
        $reqRecupEtatNUIFicheAuForfait->execute();
        while($RecupEtatNUIFicheAuForfait = $reqRecupEtatNUIFicheAuForfait->fetch()) {
            $EtatNUIFicheAuForfait[] = $RecupEtatNUIFicheAuForfait['idEtat'];
        }
    }

    for($i = 0; $i < count($MoisREPFicheAuForfait); $i++) {
        $reqRecupEtatREPFicheAuForfait = $conn->prepare("SELECT idEtat from gsb_frais.lignefraisforfait WHERE mois='".$MoisREPFicheAuForfait[$i]."' AND idFraisForfait='REP'");
        $reqRecupEtatREPFicheAuForfait->execute();
        while($RecupEtatREPFicheAuForfait = $reqRecupEtatREPFicheAuForfait->fetch()) {
            $EtatREPFicheAuForfait[] = $RecupEtatREPFicheAuForfait['idEtat'];
        }
    }


    $reqRecupQuantiteETP = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='ETP'");
    $reqRecupQuantiteETP->execute();
    while($RecupQuantiteETP = $reqRecupQuantiteETP->fetch()) {
        $QuantiteETP[] = $RecupQuantiteETP['quantite'];
    }

    $reqRecupQuantiteKM = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='KM'");
    $reqRecupQuantiteKM->execute();
    while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
        $QuantiteKM[] = $RecupQuantiteKM['quantite'];
    }

    $reqRecupQuantiteNUI = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='NUI'");
    $reqRecupQuantiteNUI->execute();
    while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
        $QuantiteNUI[] = $RecupQuantiteNUI['quantite'];
    }

    $reqRecupQuantiteREP = $conn->prepare("SELECT quantite from gsb_frais.lignefraisforfait WHERE idFraisForfait='REP'");
    $reqRecupQuantiteREP->execute();
    while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
        $QuantiteREP[] = $RecupQuantiteREP['quantite'];
    }



    // HORS FORFAIT RECUPERATION DES DONNEES EXISTANTES :

    $reqRecupAllIdHorsForfait = $conn->prepare("SELECT id FROM gsb_frais.lignefraishorsforfait");
    $reqRecupAllIdHorsForfait->execute();
    while($RecupAllIdHorsForfait = $reqRecupAllIdHorsForfait->fetch()) {
        $AllIdHorsForfait[] = $RecupAllIdHorsForfait['id'];
    }

    // ACTIONS AU CLIC DES BOUTONS AU FORFAIT :

    for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {
        if(isset($_POST['supprimerAuForfait'.$MoisFicheAuForfait[$i]])) {

            $reqRecupIdCorrespondanteAuForfaitSupprimer = $conn->query("SELECT idVisiteur from gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."'");

            while($RecupIdCorrespondanteAuForfaitSupprimer = $reqRecupIdCorrespondanteAuForfaitSupprimer->fetch()) {
                $IdCorrespondanteAuForfaitSupprimer = $RecupIdCorrespondanteAuForfaitSupprimer['idVisiteur'];
            }

            $reqSupprimerFicheAuForfaitETP = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."' AND idVisiteur = '".$IdCorrespondanteAuForfaitSupprimer."' AND idFraisForfait='ETP'");
            $reqSupprimerFicheAuForfaitETP->execute();

            $reqSupprimerFicheAuForfaitKM = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."' AND idVisiteur = '".$IdCorrespondanteAuForfaitSupprimer."' AND idFraisForfait='KM'");
            $reqSupprimerFicheAuForfaitKM->execute();

            $reqSupprimerFicheAuForfaitNUI = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."' AND idVisiteur = '".$IdCorrespondanteAuForfaitSupprimer."' AND idFraisForfait='NUI'");
            $reqSupprimerFicheAuForfaitNUI->execute();

            $reqSupprimerFicheAuForfaitREP = $conn->prepare("DELETE FROM gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."' AND idVisiteur = '".$IdCorrespondanteAuForfaitSupprimer."' AND idFraisForfait='REP'");
            $reqSupprimerFicheAuForfaitREP->execute();

            header("Location: Valider_Fiche.php");
        }
    }

    for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {
        if(isset($_POST['modifierAuForfait'.$MoisFicheAuForfait[$i]])) {

            $reqRecupIdCorrespondanteAuForfaitModifier = $conn->query("SELECT idVisiteur from gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."'");

            while($RecupIdCorrespondanteAuForfaitModifier = $reqRecupIdCorrespondanteAuForfaitModifier->fetch()) {
                $IdCorrespondanteAuForfaitModifier = $RecupIdCorrespondanteAuForfaitModifier['idVisiteur'];

            }

            $reqModifierFicheAuForfaitETP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['ETP'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$IdCorrespondanteAuForfaitModifier."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='ETP';");
            $reqModifierFicheAuForfaitETP->execute();

            $reqModifierFicheAuForfaitKM = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['KM'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$IdCorrespondanteAuForfaitModifier."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='KM';");
            $reqModifierFicheAuForfaitKM->execute();

            $reqModifierFicheAuForfaitNUI = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['NUI'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$IdCorrespondanteAuForfaitModifier."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='NUI';");
            $reqModifierFicheAuForfaitNUI->execute();

            $reqModifierFicheAuForfaitREP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET quantite='".$_POST['REP'.$MoisFicheAuForfait[$i]]."' WHERE idVisiteur='".$IdCorrespondanteAuForfaitModifier."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='REP';");
            $reqModifierFicheAuForfaitREP->execute();

            header("Location: Valider_Fiche.php");
        }
    }

    for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {
        if(isset($_POST['validerAuForfait'.$MoisFicheAuForfait[$i]])) {

            $reqRecupIdCorrespondanteAuForfaitValider = $conn->query("SELECT idVisiteur from gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."'");

            while($RecupIdCorrespondanteAuForfaitValider = $reqRecupIdCorrespondanteAuForfaitValider->fetch()) {
                $IdCorrespondanteAuForfaitValider = $RecupIdCorrespondanteAuForfaitValider['idVisiteur'];

            }

            $reqValiderFicheAuForfaitETP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='VA' WHERE idVisiteur='".$IdCorrespondanteAuForfaitValider."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='ETP';");
            $reqValiderFicheAuForfaitETP->execute();

            $reqValiderFicheAuForfaitKM = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='VA' WHERE idVisiteur='".$IdCorrespondanteAuForfaitValider."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='KM';");
            $reqValiderFicheAuForfaitKM->execute();

            $reqValiderFicheAuForfaitNUI = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='VA' WHERE idVisiteur='".$IdCorrespondanteAuForfaitValider."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='NUI';");
            $reqValiderFicheAuForfaitNUI->execute();

            $reqValiderFicheAuForfaitREP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='VA' WHERE idVisiteur='".$IdCorrespondanteAuForfaitValider."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='REP';");
            $reqValiderFicheAuForfaitREP->execute();

            header("Location: Valider_Fiche.php");
        }
    }

    for($i = 0; $i < $NombreLigneDonneesAuForfait[0]/4; $i++) {
        if(isset($_POST['rejeterAuForfait'.$MoisFicheAuForfait[$i]])) {

            $reqRecupIdCorrespondanteAuForfaitRejeter = $conn->query("SELECT idVisiteur from gsb_frais.lignefraisforfait WHERE mois='".$MoisFicheAuForfait[$i]."'");

            while($RecupIdCorrespondanteAuForfaitRejeter = $reqRecupIdCorrespondanteAuForfaitRejeter->fetch()) {
                $IdCorrespondanteAuForfaitRejeter = $RecupIdCorrespondanteAuForfaitRejeter['idVisiteur'];

            }

            $reqRejeterFicheAuForfaitETP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='RE' WHERE idVisiteur='".$IdCorrespondanteAuForfaitRejeter."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='ETP';");
            $reqRejeterFicheAuForfaitETP->execute();

            $reqRejeterFicheAuForfaitKM = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='RE' WHERE idVisiteur='".$IdCorrespondanteAuForfaitRejeter."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='KM';");
            $reqRejeterFicheAuForfaitKM->execute();

            $reqRejeterFicheAuForfaitNUI = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='RE' WHERE idVisiteur='".$IdCorrespondanteAuForfaitRejeter."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='NUI';");
            $reqRejeterFicheAuForfaitNUI->execute();

            $reqRejeterFicheAuForfaitREP = $conn->prepare("UPDATE gsb_frais.lignefraisforfait SET idEtat='RE' WHERE idVisiteur='".$IdCorrespondanteAuForfaitRejeter."' AND mois='".$MoisFicheAuForfait[$i]."' AND idFraisForfait='REP';");
            $reqRejeterFicheAuForfaitREP->execute();

            header("Location: Valider_Fiche.php");
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

            $reqRecupIdDuProprietaireFicheAuForfait = $conn->query("select idVisiteur from gsb_frais.lignefraisforfait where idFraisForfait='ETP' AND quantite='".$QuantiteETP[$i]."' AND mois='".$MoisFicheAuForfait[$i]."'");

            while($RecupIdDuProprietaireFicheAuForfait = $reqRecupIdDuProprietaireFicheAuForfait->fetch()) {
                $IdDuProprietaireFicheAuForfait = $RecupIdDuProprietaireFicheAuForfait['idVisiteur'];
            }

            $reqRecupNomPrenomDuProprietaireFicheAuForfait = $conn->query("select nom, prenom from gsb_frais.utilisateur where id='".$IdDuProprietaireFicheAuForfait."'");
            while($RecupNomPrenomDuProprietaireFicheAuForfait = $reqRecupNomPrenomDuProprietaireFicheAuForfait->fetch()) {
                $NomDuProprietaireFicheAuForfait = $RecupNomPrenomDuProprietaireFicheAuForfait['nom'];
                $PrenomDuProprietaireFicheAuForfait = $RecupNomPrenomDuProprietaireFicheAuForfait['prenom'];

            }
            $reqRecupNomRoleDuProprietaireFicheAuForfait = $conn->query("select NomRole from role inner join utilisateur on role.idr = utilisateur.idr where id='".$IdDuProprietaireFicheAuForfait."';");
            while($RecupNomRoleDuProprietaireFicheAuForfait = $reqRecupNomRoleDuProprietaireFicheAuForfait->fetch()){
                $NomRoleDuProprietaireFicheAuForfait = $RecupNomRoleDuProprietaireFicheAuForfait['NomRole'];
            }
            $reqRecupAdresseVilleCpDuProprietaireFicheAuForfait = $conn->query("select adresse,ville,cp from utilisateur where id='".$IdDuProprietaireFicheAuForfait."';");
            while($RecupAdresseVilleCpDuProprietaireFicheAuForfait = $reqRecupAdresseVilleCpDuProprietaireFicheAuForfait->fetch()){
                $RecupAdresseDuProprietaireFicheAuForfait = $RecupAdresseVilleCpDuProprietaireFicheAuForfait['adresse'];
                $RecupVilleDuProprietaireFicheAuForfait = $RecupAdresseVilleCpDuProprietaireFicheAuForfait['ville'];
                $RecupCPeDuProprietaireFicheAuForfait = $RecupAdresseVilleCpDuProprietaireFicheAuForfait['cp'];
            }
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
            $pdf->Cell(210, 10, 'Nom du proprietaire de la fiche : '.$NomDuProprietaireFicheAuForfait.' '.$PrenomDuProprietaireFicheAuForfait.'', 0, 1, 'L', 1);
            $pdf->SetXY(0, 72);
            $pdf->Cell(210, 10, 'Matricule du proprietaire de la fiche : '.$IdDuProprietaireFicheAuForfait.'', 0, 1, 'L', 1);
            $pdf->SetXY(0, 84);
            $pdf->Cell(210, 10, 'Role du proprietaire de la fiche : '. $NomRoleDuProprietaireFicheAuForfait.'', 0, 1, 'L', 1);
            $pdf->SetXY(0, 96);
            $pdf->Cell(210, 10, 'Adresse du proprietaire de la fiche : '.$RecupAdresseDuProprietaireFicheAuForfait. ", ". $RecupCPeDuProprietaireFicheAuForfait ." ".  $RecupVilleDuProprietaireFicheAuForfait.'', 0, 1, 'L', 1);
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

    // ACTIONS AU CLIC DES BOUTONS HORS FORFAIT :

    foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
        if(isset($_POST['supprimerHorsForfait'.$IdFicheHorsForfait])) {
            $reqSupprimerHorsForfait = $conn->prepare("DELETE FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."';");
            $reqSupprimerHorsForfait->execute();

            header("Location: Valider_Fiche.php");
        }
    }

    foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
        if(isset($_POST['modifierHorsForfait'.$IdFicheHorsForfait])) {
            $reqModifierHorsForfait = $conn->prepare("UPDATE gsb_frais.lignefraishorsforfait SET libelle='".$_POST['libelle'.$IdFicheHorsForfait]."', `date`='".$_POST['date'.$IdFicheHorsForfait]."', montant='".$_POST['montant'.$IdFicheHorsForfait]."' WHERE id='".$IdFicheHorsForfait."';");
            $reqModifierHorsForfait->execute();

            header("Location: Valider_Fiche.php");
        }
    }

    foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
        if(isset($_POST['validerHorsForfait'.$IdFicheHorsForfait])) {
            $reqValiderHorsForfait = $conn->prepare("UPDATE gsb_frais.lignefraishorsforfait SET idEtat='VA' WHERE libelle='".$_POST['libelle'.$IdFicheHorsForfait]."' AND date='".$_POST['date'.$IdFicheHorsForfait]."' AND montant='".$_POST['montant'.$IdFicheHorsForfait]."' AND id='".$IdFicheHorsForfait."'");
            $reqValiderHorsForfait->execute();

            header("Location: Valider_Fiche.php");
        }
    }

    foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
        if(isset($_POST['rejeterHorsForfait'.$IdFicheHorsForfait])) {
            $reqRejeterHorsForfait = $conn->prepare("UPDATE gsb_frais.lignefraishorsforfait SET idEtat='RE' WHERE libelle='".$_POST['libelle'.$IdFicheHorsForfait]."' AND date='".$_POST['date'.$IdFicheHorsForfait]."' AND montant='".$_POST['montant'.$IdFicheHorsForfait]."' AND id='".$IdFicheHorsForfait."'");
            $reqRejeterHorsForfait->execute();

            header("Location: Valider_Fiche.php");
        }
    }


    $CompteurNav = 0;
    foreach($AllIdHorsForfait as $IdFicheHorsForfait) {
        $reqRecupMoisHorsForfait = $conn->prepare("SELECT mois FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."'");
        $reqRecupMoisHorsForfait->execute();
        while($RecupMoisHorsForfait = $reqRecupMoisHorsForfait->fetch()) {
            $MoisHorsForfait[] = $RecupMoisHorsForfait['mois'];
        }
        if(isset($_POST['pdfHorsForfait'.$IdFicheHorsForfait])) {

            $reqRecupIdDuProprietaireFicheHorsForfait = $conn->query("SELECT idVisiteur FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."'");
            while($RecupIdDuProprietaireFicheHorsForfait = $reqRecupIdDuProprietaireFicheHorsForfait->fetch()) {
                $IdDuProprietaireFicheHorsForfait = $RecupIdDuProprietaireFicheHorsForfait['idVisiteur'];
            }

            $reqRecupNomPrenomDuProprietaireFicheHorsForfait = $conn->query("SELECT nom, prenom FROM gsb_frais.utilisateur WHERE id='".$IdDuProprietaireFicheHorsForfait."'");
            while($RecupNomPrenomDuProprietaireFicheHorsForfait = $reqRecupNomPrenomDuProprietaireFicheHorsForfait->fetch()) {
                $NomDuProprietaireFicheHorsForfait = $RecupNomPrenomDuProprietaireFicheHorsForfait['nom'];
                $PrenomDuProprietaireFicheHorsForfait = $RecupNomPrenomDuProprietaireFicheHorsForfait['prenom'];
            }
            $reqRecupNomRoleDuProprietaireFicheHorsForfait = $conn->query("select NomRole from role inner join utilisateur on role.idr = utilisateur.idr where id='".$IdDuProprietaireFicheHorsForfait."'");
            while($RecupNomRoleDuProprietaireFicheHorsForfait = $reqRecupNomRoleDuProprietaireFicheHorsForfait->fetch()){
                $NomRoleDuProprietaireFicheHorsForfait = $RecupNomRoleDuProprietaireFicheHorsForfait['NomRole'];
            }
            $reqRecupAdresseVilleCpDuProprietaireFicheHorsForfait = $conn->query("select adresse,ville,cp from utilisateur where id='".$IdDuProprietaireFicheHorsForfait."';");
            while($RecupAdresseVilleCpDuProprietaireFicheHorsForfait = $reqRecupAdresseVilleCpDuProprietaireFicheHorsForfait->fetch()){
                $RecupAdresseDuProprietaireFicheHorsForfait = $RecupAdresseVilleCpDuProprietaireFicheHorsForfait['adresse'];
                $RecupVilleDuProprietaireFicheHorsForfait = $RecupAdresseVilleCpDuProprietaireFicheHorsForfait['ville'];
                $RecupCPeDuProprietaireFicheHorsForfait = $RecupAdresseVilleCpDuProprietaireFicheHorsForfait['cp'];
            }


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
            $pdf->Cell(210, 10, 'Nom du proprietaire de la fiche : '.$NomDuProprietaireFicheHorsForfait.' '.$PrenomDuProprietaireFicheHorsForfait.'', 0, 1, 'L', 1);
            $pdf->SetXY(0, 72);
            $pdf->Cell(210, 10, 'Matricule du proprietaire de la fiche : '.$IdDuProprietaireFicheHorsForfait.'', 0, 1, 'L', 1);
            $pdf->SetXY(150, 78);
            $pdf->Cell(60, 8, 'Numero de la fiche de frais : '.$IdFicheHorsForfait.'', 0, 1, 'C', 1);
            $pdf->SetXY(0, 84);
            $pdf->Cell(210, 10, 'Role du proprietaire de la fiche : '. $NomRoleDuProprietaireFicheHorsForfait.'', 0, 1, 'L', 1);
            $pdf->SetXY(0, 96);
            $pdf->Cell(210, 10, 'Adresse du proprietaire de la fiche : '.$RecupAdresseDuProprietaireFicheHorsForfait. ", ". $RecupCPeDuProprietaireFicheHorsForfait ." ".  $RecupVilleDuProprietaireFicheHorsForfait.'', 0, 1, 'L', 1);
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
        <title>Valider une fiche de frais</title>
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
                    <a href="profil.php"><i class="fas fa-home"></i> Accueil</a>
                    <a href="#"><i class="fas fa-money-bill-wave"></i> Suivre le paiement d'une fiche de frais</a>
                    <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>

        <center>

        <!-- FRAIS AU FORFAIT-->

        <div class="block animated fadeInDown slow decadeDiv">
            <div class="field">
                <h1>Toutes les fiches de frais au forfait</h1>
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
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-danger" name="supprimerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-trash"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-success" name="validerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-check"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-warning" name="rejeterAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-times"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
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
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-danger" name="supprimerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-trash"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-success" name="validerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-check"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-warning" name="rejeterAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-times"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-link" formtarget="_blank" name="pdfAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-file-pdf"></i>';
                        echo '</button>';
                        echo '</td>';
                    }
                    elseif($EtatETPFicheAuForfait[$i] == "VA" AND $EtatKMFicheAuForfait[$i] == "VA" AND $EtatNUIFicheAuForfait[$i] == "VA" AND $EtatREPFicheAuForfait[$i] == "VA") {
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
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-danger" name="supprimerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-trash"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-success" name="validerAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-check"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-warning" name="rejeterAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-times"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-link" formtarget="_blank" name="pdfAuForfait'.$MoisFicheAuForfait[$i].'" type="submit">';
                        echo '<i class="fas fa-file-pdf"></i>';
                        echo '</button>';
                        echo '</td>';
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
                    <h1>Toutes les fiches de frais hors forfait</h1>
                </div>
            </div>

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

                    $reqEtatFicheHorsForfait = $conn->query("SELECT idEtat FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."'");
                    while($EtatFicheHorsForfait = $reqEtatFicheHorsForfait->fetch()) {
                        $EtatDeLaFicheHorsForfait[] = $EtatFicheHorsForfait['idEtat'];
                    }

                    $reqRecupMoisHorsForfait = $conn->prepare("SELECT mois FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."'");
                    $reqRecupMoisHorsForfait->execute();
                    while($RecupMoisHorsForfait = $reqRecupMoisHorsForfait->fetch()) {
                        $MoisHorsForfait[] = $RecupMoisHorsForfait['mois'];
                    }

                    $reqRecupLibelleHorsForfait = $conn->prepare("SELECT libelle FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."'");
                    $reqRecupLibelleHorsForfait->execute();
                    while($RecupLibelleHorsForfait = $reqRecupLibelleHorsForfait->fetch()) {
                        $LibelleHorsForfait[] = $RecupLibelleHorsForfait['libelle'];
                    }


                    $reqRecupDateHorsForfait = $conn->prepare("SELECT date FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."'");
                    $reqRecupDateHorsForfait->execute();
                    while($RecupDateHorsForfait = $reqRecupDateHorsForfait->fetch()) {
                        $DateHorsForfait[] = $RecupDateHorsForfait['date'];
                    }


                    $reqRecupMontantHorsForfait = $conn->prepare("SELECT montant FROM gsb_frais.lignefraishorsforfait WHERE id='".$IdFicheHorsForfait."'");
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
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-danger" name="supprimerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-trash"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-success" name="validerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-check"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-warning" name="rejeterHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-times"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
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
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-danger" name="supprimerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-trash"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-success" name="validerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-check"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-warning" name="rejeterHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-times"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-link" formtarget="_blank" name="pdfHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-file-pdf"></i>';
                        echo '</button>';
                        echo '<td><input class="input" type="hidden" name="date'.$IdFicheHorsForfait.'" value="'.$DateHorsForfait[$CompteurNaviguerListe].'" /></td>';
                        echo '</td>';
                    }
                    elseif($EtatDeLaFicheHorsForfait[$CompteurNaviguerListe] == "VA") {
                        echo '<td><input class="input" type="text" name="libelle'.$IdFicheHorsForfait.'" value="'.$LibelleHorsForfait[$CompteurNaviguerListe].'" /></td>';
                        echo '<td><input class="input" type="text" disabled="disabled" name="mois'.$IdFicheHorsForfait.'" value="'.$MoisHorsForfait[$CompteurNaviguerListe].'" /></td>';
                        echo '<td><input class="input" type="text" name="montant'.$IdFicheHorsForfait.'" value="'.$MontantHorsForfait[$CompteurNaviguerListe].'" /></td>';
                        echo '<td>';
                        echo '<button class="button is-info" name="modifierHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-pen"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-danger" name="supprimerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-trash"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-success" name="validerHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-check"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-warning" name="rejeterHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-times"></i>';
                        echo '</button>';
                        echo '&nbsp&nbsp&nbsp';
                        echo '<button class="button is-link" formtarget="_blank" name="pdfHorsForfait'.$IdFicheHorsForfait.'" type="submit">';
                        echo '<i class="fas fa-file-pdf"></i>';
                        echo '</button>';
                        echo '<td><input class="input" type="hidden" name="date'.$IdFicheHorsForfait.'" value="'.$DateHorsForfait[$CompteurNaviguerListe].'" /></td>';
                        echo '</td>';
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
