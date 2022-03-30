<?php
    session_start();

    include('./Connection_BDD.php');
    $conn = getBdd('localhost', 'groupe3', 'sio2021');
    $NombreTotalDeFiche = "";
    $MontantTotalFiche = "";

    if(isset($_POST['formFiltre'])) {

        //CAS 1

        if($_POST['secteur'] == "Tous les secteurs" && $_POST['mois'] != "Tous les mois" && $_POST['annee'] != "Tous les ans") {

            // NOMBRE DE FICHES
            $reqRecupNombreFicheAuForfait = $conn->prepare( 'SELECT count(*) FROM gsb_frais.lignefraisforfait WHERE mois LIKE "%'.$_POST['mois'].'" AND mois LIKE "'.$_POST['annee'].'%"');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(*)'];
            }


            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT count(*) FROM gsb_frais.lignefraishorsforfait WHERE mois LIKE "%'.$_POST['mois'].'" AND mois LIKE "'.$_POST['annee'].'%"');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(*)'];
            }

            $AuForfait= $NombreFicheAuForfait/4;

            $NombreFiche = $NombreFicheHorsForfait + $AuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';


            // MONTANT TOTAL DE TOUTES CES FICHES
            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT montant FROM gsb_frais.lignefraishorsforfait WHERE mois LIKE "%'.$_POST['mois'].'" AND mois LIKE "'.$_POST['annee'].'%"');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }

            $reqRecupQuantiteKM = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='KM'");
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }

            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='KM' AND quantite='".$QuantiteKM."'");
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }



            $reqRecupQuantiteNUI = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='NUI'");
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='NUI' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='NUI' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='REP'");
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='REP' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND mois LIKE '".$_POST['annee']."%' AND idFraisForfait='REP' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%'> Montant total de ces fiches : ". $Montant . "€ </div>";

        }



        //CAS 2

        if($_POST['secteur'] == "Tous les secteurs" && $_POST['mois'] == "Tous les mois" && $_POST['annee'] == "Tous les ans") {

            // NOMBRE DE FICHES
            $reqRecupNombreFicheAuForfait = $conn->prepare( 'SELECT count(*) FROM gsb_frais.lignefraisforfait');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(*)'];
            }


            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT count(*) FROM gsb_frais.lignefraishorsforfait');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(*)'];
            }

            $AuForfait= $NombreFicheAuForfait/4;

            $NombreFiche = $NombreFicheHorsForfait + $AuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';

            // MONTANT TOTAL DE TOUTES CES FICHES

            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT montant FROM gsb_frais.lignefraishorsforfait');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }

            $reqRecupQuantiteKM = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='KM'");
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }

            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='KM' AND quantite='".$QuantiteKM."'");
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }



            $reqRecupQuantiteNUI = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='NUI'");
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='NUI' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='NUI' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='REP'");
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='REP' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE idFraisForfait='REP' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%''> Montant total de ces fiches : ". $Montant . "€ </div>";
        }


        //CAS 3

        if($_POST['secteur'] == "Tous les secteurs" && $_POST['mois'] != "Tous les mois" && $_POST['annee'] == "Tous les ans") {

            // NOMBRE DE FICHES
            $reqRecupNombreFicheAuForfait = $conn->prepare( 'SELECT count(*) FROM gsb_frais.lignefraisforfait WHERE mois LIKE "%'.$_POST['mois'].'"');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(*)'];
            }


            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT count(*) FROM gsb_frais.lignefraishorsforfait WHERE mois LIKE "%'.$_POST['mois'].'"');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(*)'];
            }

            $AuForfait= $NombreFicheAuForfait/4;

            $NombreFiche = $NombreFicheHorsForfait + $AuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';


            // MONTANT TOTAL DE TOUTES CES FICHES
            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT montant FROM gsb_frais.lignefraishorsforfait WHERE mois LIKE "%'.$_POST['mois'].'"');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }

            $reqRecupQuantiteKM = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='KM'");
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }

            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='KM' AND quantite='".$QuantiteKM."'");
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }



            $reqRecupQuantiteNUI = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='NUI'");
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='NUI' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='NUI' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='REP'");
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='REP' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE'%".$_POST['mois']."' AND idFraisForfait='REP' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%'> Montant total de ces fiches : ". $Montant . "€ </div>";

        }

        //CAS 4

        if($_POST['secteur'] == "Tous les secteurs" && $_POST['mois'] == "Tous les mois" && $_POST['annee'] != "Tous les ans") {

            // NOMBRE DE FICHES
            $reqRecupNombreFicheAuForfait = $conn->prepare( 'SELECT count(*) FROM gsb_frais.lignefraisforfait WHERE mois LIKE "'.$_POST['annee'].'%"');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(*)'];
            }


            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT count(*) FROM gsb_frais.lignefraishorsforfait WHERE mois LIKE "'.$_POST['annee'].'%"');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(*)'];
            }

            $AuForfait= $NombreFicheAuForfait/4;

            $NombreFiche = $NombreFicheHorsForfait + $AuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';


            // MONTANT TOTAL DE TOUTES CES FICHES
            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT montant FROM gsb_frais.lignefraishorsforfait WHERE mois LIKE "'.$_POST['annee'].'%"');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }

            $reqRecupQuantiteKM = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='KM'");
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }

            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='KM' AND quantite='".$QuantiteKM."'");
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }



            $reqRecupQuantiteNUI = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='NUI'");
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='NUI' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='NUI' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='REP'");
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare("SELECT idVisiteur FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='REP' AND quantite='".$QuantiteNUI."'");
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare("SELECT quantite FROM gsb_frais.lignefraisforfait WHERE mois LIKE '".$_POST['annee']."%' AND idFraisForfait='REP' AND idVisiteur='".$VisiteurQuantiteNUI."'");
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%'> Montant total de ces fiches : ". $Montant . "€ </div>";

        }


        // CAS 5
        if($_POST['secteur'] != "Tous les secteurs" && $_POST['mois'] == "Tous les mois" && $_POST['annee'] == "Tous les ans") {
            // NOMBRE DE FICHES
            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT  count(DISTINCT lignefraishorsforfait.id) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo WHERE geographie.nomGeo="'.$_POST['secteur'].'";');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(DISTINCT lignefraishorsforfait.id)'];
            }

            $reqRecupNombreFicheAuForfait = $conn->prepare('select  count(distinct lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'";');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(distinct lignefraisforfait.idVisiteur)'];
            }

            $NombreFiche = $NombreFicheHorsForfait + $NombreFicheAuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';


            // MONTANT TOTAL DE TOUTES CES FICHES
            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT distinct(montant) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'"');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }




            $reqRecupQuantiteKM = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM"');
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }


            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM"');
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }

            $reqRecupQuantiteNUI = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI"');
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI"');
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteNUI.'"');
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP"');
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND quantite="'.$QuantiteREP.'"');
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteREP.'"');
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%'> Montant total de ces fiches : ". $Montant . "€ </div>";
            
        }

        // CAS 6
        if($_POST['secteur'] != "Tous les secteurs" && $_POST['mois'] != "Tous les mois" && $_POST['annee'] == "Tous les ans") {
            // NOMBRE DE FICHES
            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT  count(DISTINCT lignefraishorsforfait.id) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo WHERE geographie.nomGeo="'.$_POST['secteur'].'" AND lignefraishorsforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(DISTINCT lignefraishorsforfait.id)'];
            }

            $reqRecupNombreFicheAuForfait = $conn->prepare('select  count(distinct lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(distinct lignefraisforfait.idVisiteur)'];
            }

            $NombreFiche = $NombreFicheHorsForfait + $NombreFicheAuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';


            // MONTANT TOTAL DE TOUTES CES FICHES
            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT distinct(montant) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND lignefraishorsforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }




            $reqRecupQuantiteKM = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }


            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }

            $reqRecupQuantiteNUI = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteNUI.'" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND quantite="'.$QuantiteREP.'" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteREP.'" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%'> Montant total de ces fiches : ". $Montant . "€ </div>";
        }

        // CAS 7

        if($_POST['secteur'] != "Tous les secteurs" && $_POST['mois'] == "Tous les mois" && $_POST['annee'] != "Tous les ans") {
            // NOMBRE DE FICHES
            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT  count(DISTINCT lignefraishorsforfait.id) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo WHERE geographie.nomGeo="'.$_POST['secteur'].'" AND lignefraishorsforfait.mois LIKE "'.$_POST['annee'].'%";');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(DISTINCT lignefraishorsforfait.id)'];
            }

            $reqRecupNombreFicheAuForfait = $conn->prepare('select  count(distinct lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(distinct lignefraisforfait.idVisiteur)'];
            }

            $NombreFiche = $NombreFicheHorsForfait + $NombreFicheAuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';


            // MONTANT TOTAL DE TOUTES CES FICHES
            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT distinct(montant) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND lignefraishorsforfait.mois LIKE "'.$_POST['annee'].'%";');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }




            $reqRecupQuantiteKM = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }


            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }

            $reqRecupQuantiteNUI = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteNUI.'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND quantite="'.$QuantiteREP.'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteREP.'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%";');
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%'> Montant total de ces fiches : ". $Montant . "€ </div>";
        }


        // CAS 8

        if($_POST['secteur'] != "Tous les secteurs" && $_POST['mois'] != "Tous les mois" && $_POST['annee'] != "Tous les ans") {
            // NOMBRE DE FICHES
            $reqRecupNombreFicheHorsForfait = $conn->prepare('SELECT  count(DISTINCT lignefraishorsforfait.id) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo WHERE geographie.nomGeo="'.$_POST['secteur'].'" AND lignefraishorsforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraishorsforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupNombreFicheHorsForfait->execute();
            while($RecupNombreFicheHorsForfait = $reqRecupNombreFicheHorsForfait->fetch()) {
                $NombreFicheHorsForfait = $RecupNombreFicheHorsForfait['count(DISTINCT lignefraishorsforfait.id)'];
            }

            $reqRecupNombreFicheAuForfait = $conn->prepare('select  count(distinct lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupNombreFicheAuForfait->execute();
            while($RecupNombreFicheAuForfait = $reqRecupNombreFicheAuForfait->fetch()) {
                $NombreFicheAuForfait = $RecupNombreFicheAuForfait['count(distinct lignefraisforfait.idVisiteur)'];
            }

            $NombreFiche = $NombreFicheHorsForfait + $NombreFicheAuForfait;

            $NombreTotalDeFiche = '<div class="notification is-success" style="width: 30%"> Nombre de fiches pour ce filtre : '.$NombreFiche.'</div>';


            // MONTANT TOTAL DE TOUTES CES FICHES
            $Montant = 0;
            $reqRecupMontantFicheHorsForfait = $conn->prepare('SELECT distinct(montant) FROM gsb_frais.lignefraishorsforfait INNER JOIN gsb_frais.fichefrais ON fichefrais.idVisiteur=lignefraishorsforfait.idVisiteur INNER JOIN gsb_frais.utilisateur ON fichefrais.idVisiteur=utilisateur.id INNER JOIN geographie ON geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND lignefraishorsforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraishorsforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupMontantFicheHorsForfait->execute();
            while($RecupMontantFicheHorsForfait = $reqRecupMontantFicheHorsForfait->fetch()) {
                $MontantFicheHorsForfait = $RecupMontantFicheHorsForfait['montant'];
                $Montant += $MontantFicheHorsForfait;
            }




            $reqRecupQuantiteKM = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupQuantiteKM->execute();
            while($RecupQuantiteKM = $reqRecupQuantiteKM->fetch()) {
                $AllQuantiteKM[] = $RecupQuantiteKM['quantite'];
            }


            foreach ($AllQuantiteKM as $QuantiteKM) {
                $reqQuelVisiteurQuantiteKM = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="KM" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqQuelVisiteurQuantiteKM->execute();
                while($QuelVisiteurQuantiteKM = $reqQuelVisiteurQuantiteKM->fetch()) {
                    $VisiteurQuantiteKM = $QuelVisiteurQuantiteKM['idVisiteur'];
                }

                $reqRecupCVQuelVisiteurQuantiteKM = $conn->prepare("SELECT chevauxFiscaux from gsb_frais.vehicule_utilisateur where idUser='".$VisiteurQuantiteKM."'");
                $reqRecupCVQuelVisiteurQuantiteKM->execute();
                while($RecupCVQuelVisiteurQuantiteKM = $reqRecupCVQuelVisiteurQuantiteKM->fetch()) {
                    $CVQuelVisiteurQuantiteKM = $RecupCVQuelVisiteurQuantiteKM['chevauxFiscaux'];
                }

                if($CVQuelVisiteurQuantiteKM <= 3) {
                    $Montant += $QuantiteKM * 0.456;
                }
                if($CVQuelVisiteurQuantiteKM == 4) {
                    $Montant += $QuantiteKM * 0.523;
                }
                if($CVQuelVisiteurQuantiteKM == 5) {
                    $Montant += $QuantiteKM * 0.548;
                }
                if($CVQuelVisiteurQuantiteKM == 6) {
                    $Montant += $QuantiteKM * 0.574;
                }
                if($CVQuelVisiteurQuantiteKM >= 7) {
                    $Montant += $QuantiteKM * 0.601;
                }
            }

            $reqRecupQuantiteNUI = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupQuantiteNUI->execute();
            while($RecupQuantiteNUI = $reqRecupQuantiteNUI->fetch()) {
                $AllQuantiteNUI[] = $RecupQuantiteNUI['quantite'];
            }

            foreach ($AllQuantiteNUI as $QuantiteNUI) {
                $reqQuelVisiteurQuantiteNUI = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqQuelVisiteurQuantiteNUI->execute();
                while($QuelVisiteurQuantiteNUI = $reqQuelVisiteurQuantiteNUI->fetch()) {
                    $VisiteurQuantiteNUI = $QuelVisiteurQuantiteNUI['idVisiteur'];
                }

                $reqRecupQuantiteNUIVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="NUI" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteNUI.'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqRecupQuantiteNUIVisiteur->execute();
                while($RecupQuantiteNUIVisiteur = $reqRecupQuantiteNUIVisiteur->fetch()) {
                    $QuantiteNUIVisiteur = $RecupQuantiteNUIVisiteur['quantite'];
                }

                $Montant += $QuantiteNUIVisiteur * 40;

            }


            $reqRecupQuantiteREP = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
            $reqRecupQuantiteREP->execute();
            while($RecupQuantiteREP = $reqRecupQuantiteREP->fetch()) {
                $AllQuantiteREP[] = $RecupQuantiteREP['quantite'];
            }

            foreach ($AllQuantiteREP as $QuantiteREP) {
                $reqQuelVisiteurQuantiteREP = $conn->prepare('select  distinct(lignefraisforfait.idVisiteur) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND quantite="'.$QuantiteREP.'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqQuelVisiteurQuantiteREP->execute();
                while($QuelVisiteurQuantiteREP = $reqQuelVisiteurQuantiteREP->fetch()) {
                    $VisiteurQuantiteREP = $QuelVisiteurQuantiteREP['idVisiteur'];
                }

                $reqRecupQuantiteREPVisiteur = $conn->prepare('select  distinct(quantite) from gsb_frais.lignefraisforfait inner join gsb_frais.fichefrais on fichefrais.idVisiteur=lignefraisforfait.idVisiteur inner join gsb_frais.utilisateur on fichefrais.idVisiteur=utilisateur.id inner join geographie on geographie.idGeo=utilisateur.idGeo where nomGeo="'.$_POST['secteur'].'" AND idFraisForfait="REP" AND lignefraisforfait.idVisiteur="'.$VisiteurQuantiteREP.'" AND lignefraisforfait.mois LIKE "'.$_POST['annee'].'%" AND lignefraisforfait.mois LIKE "%'.$_POST['mois'].'";');
                $reqRecupQuantiteREPVisiteur->execute();
                while($RecupQuantiteREPVisiteur = $reqRecupQuantiteREPVisiteur->fetch()) {
                    $QuantiteREPVisiteur = $RecupQuantiteREPVisiteur['quantite'];
                }

                $Montant += $QuantiteREPVisiteur * 15;

            }

            $MontantTotalFiche = "<div class='notification is-success' style='width: 30%'> Montant total de ces fiches : ". $Montant . "€ </div>";
        }

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
                    <a href="LogOut.php"><i class="fas fa-door-open"></i> Se déconnecter</a>
                </nav>
            </header>
        </div>



        <center>
            <form method="post">
                <div class="block animated fadeInDown slow decadeDiv">
                    <div class="select">
                        <select name="secteur">
                            <option>Tous les secteurs</option>
                            <option>NO</option>
                            <option>NE</option>
                            <option>SO</option>
                            <option>SE</option>
                        </select>
                    </div>




                    <div class="select">
                        <select name="annee">
                            <option>Tous les ans</option>
                            <option>2021</option>
                            <option>2022</option>
                        </select>
                    </div>



                    <div class="select">
                        <select name="mois">
                            <option>Tous les mois</option>
                            <option>01</option>
                            <option>02</option>
                            <option>03</option>
                            <option>04</option>
                            <option>05</option>
                            <option>06</option>
                            <option>07</option>
                            <option>08</option>
                            <option>09</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                        </select>
                    </div>
                    <br /><br />
                    <button class="button is-primary buttonConnexion" name="formFiltre">Filtrer</button>
                    <br /><br />

                    <?php echo $NombreTotalDeFiche.'<br /><br />'; echo $MontantTotalFiche;?>
                    </div>
                </div>
            </form>
        </center>


    </body>
</html>
