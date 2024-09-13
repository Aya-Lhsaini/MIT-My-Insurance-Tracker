
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="4.css">
    <title>Mes Dossiers</title>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['utilisateur'])) {
        header('location: login.php');
        exit();
    }

    $utilisateur = $_SESSION['utilisateur'];
    $nomUtilisateur = isset($_SESSION['utilisateur']['nom']) ? $_SESSION['utilisateur']['nom'] : '';
    $prenomUtilisateur = isset($_SESSION['utilisateur']['prenom']) ? $_SESSION['utilisateur']['prenom'] : '';
    
    ?>
<style>
     .rembourse, .non-rembourse {
            background-color: white;
        }

.retard {
    background-color: #edd4d4; 
}
</style>
    <div class="container">
        <?php include 'navbar.php'; ?>
        <button class="button-24" role="button" id="btn">+ Créer un nouveau dossier</button>

        <div id="overlay" style="display:none;">
            <div id="form-container">
                <button id="close-btn">&times;</button>
                <form method="post" enctype="multipart/form-data">
                    <legend>Lien de parenté :</legend>
                    <div class="radio">
                        <input type="radio" id="moi" name="lienParente" value="moi">
                        <label for="moi">Moi-même</label>
                    </div>
                    <div class="radio">
                        <input type="radio" id="conjoint" name="lienParente" value="conjoint">
                        <label for="conjoint">Conjoint(e)</label>
                    </div>
                    <div class="radio">
                        <input type="radio" id="enfant" name="lienParente" value="enfant">
                        <label for="enfant">Enfant</label>
                    </div>
                    <label for="nom">Nom du malade :</label>
                    <input type="text" id="nom" name="nomMalade" required>
                    <label for="prenom">Prénom du malade :</label>
                    <input type="text" id="prenom" name="prenomMalade" required>
                    <label for="type-declaration">Type de déclaration :</label>
                    <select name="typeDeclaration" id="type-declaration" required>
                        <option value="Maladie">Maladie</option>
                        <option value="Dentaire">Dentaire</option>
                        <option value="Prise en charge">Prise en charge</option>
                    </select>
                    <label for="montant">Montant :</label>
                    <input type="number" id="montant" name="montant" required>
                    <div id="file-upload-container">
                        <label for="pieceJointe">Ajouter une pièce jointe :</label>
                        <input type="file" id="pieceJointe" name="pieceJointe">
                    </div>
                    <button type="submit" name="createFolder" class="button-25">Créer</button>
                </form>
            </div>
        </div>
        <div id="statut-overlay" style="display:none;">
            <div id="statut-form-container">
                <p>Êtes-vous sûr de vouloir changer le statut du dossier ?</p>
                <button id="confirm-btn">Oui</button>
                <button id="cancel-btn">Non</button>
            </div>
        </div>
    </div>

    <script>
        const nomUtilisateur = "<?php echo $nomUtilisateur; ?>";
        const prenomUtilisateur = "<?php echo $prenomUtilisateur; ?>";

        document.getElementById('moi').addEventListener('change', function () {
            if (this.checked) {
                document.getElementById('nom').value = nomUtilisateur;
                document.getElementById('prenom').value = prenomUtilisateur;
            }
        });

        document.querySelectorAll('input[name="lienParente"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (this.id !== 'moi') {
                    document.getElementById('nom').value = '';
                    document.getElementById('prenom').value = '';
                }
            });
        });

        document.getElementById('btn').addEventListener('click', function () {
            var overlay = document.getElementById('overlay');
            overlay.style.display = 'flex';
            setTimeout(function () {
                overlay.classList.add('show');
            }, 10);
        });

        document.getElementById('close-btn').addEventListener('click', function () {
            var overlay = document.getElementById('overlay');
            overlay.classList.remove('show');
            setTimeout(function () {
                overlay.style.display = 'none';
            }, 500);
        });

        function changeStatut(nouveauStatut, id) {
            var overlay = document.getElementById('statut-overlay');
            overlay.style.display = 'flex';
            setTimeout(function () {
                overlay.classList.add('show');
            }, 10);

            var confirmBtn = document.getElementById('confirm-btn');
            var cancelBtn = document.getElementById('cancel-btn');

            confirmBtn.onclick = function () {
                fetch('update_statut.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&statut=${nouveauStatut}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);

                    var row = document.getElementById(`dossier-${id}`);
                    var statutBtn = row.querySelector('.statut-btn');

                    if (nouveauStatut === 'Remboursé') {
                        row.classList.remove('non-rembourse');
                        row.classList.add('rembourse');
                        statutBtn.className = 'statut-btn button-3';
                        statutBtn.disabled = true;
                        statutBtn.textContent = 'Remboursé';
                    } else {
                        row.classList.remove('rembourse');
                        row.classList.add('non-rembourse');
                        statutBtn.className = 'statut-btn button-4';
                        statutBtn.disabled = false;
                        statutBtn.textContent = 'Non Remboursé';
                    }

                    updateBackgroundColor(row);

                    overlay.classList.remove('show');
                    setTimeout(function () {
                        overlay.style.display = 'none';
                    }, 500);
                })
                .catch(error => console.error('Erreur:', error));
            };


            cancelBtn.onclick = function () {
                overlay.classList.remove('show');
                setTimeout(function () {
                    overlay.style.display = 'none';
                }, 500);
            };
        }

    </script>

    <?php
    if (isset($_POST['createFolder'])) {
        $nom = $_POST['nomMalade'];
        $prenom = $_POST['prenomMalade'];
        $lien_parente = $_POST['lienParente'];
        $type_declaration = $_POST['typeDeclaration'];
        $montant = $_POST['montant'];

        if (!empty($nom) && !empty($prenom) && !empty($lien_parente) && !empty($type_declaration) && !empty($montant)) {
            require_once 'database.php';
            $date_depot = date('Y-m-d');
            $statut = 'Non remboursé'; // Valeur par défaut

            // Gestion de la pièce jointe
            $pieceJointe = null;
            if (isset($_FILES['pieceJointe']) && $_FILES['pieceJointe']['error'] == UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['pieceJointe']['tmp_name'];
                $fileName = $_FILES['pieceJointe']['name'];
                $uploadFileDir = 'uploads/';
                $dest_path = $uploadFileDir . $fileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $pieceJointe = $fileName;
                }
            }

            $sql = 'INSERT INTO dossier (nom, prenom, lien_parente, type_declaration, montant, date_depot, statut, piece_jointe, utilisateur_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $sqlstate = $pdo->prepare($sql);
            $sqlstate->execute([$nom, $prenom, $lien_parente, $type_declaration, $montant, $date_depot, $statut, $pieceJointe, $utilisateur['id']]);

            echo "<script>window.location.href='';</script>";
        }
   

    }
    ?>

    <div class="container2">
        <ul class="responsive-table">
            <li class="table-header">
                <div class="col col-1">DOSSIER</div>
                <div class="col col-2">DATE DEPOT</div>
                <div class="col col-3">NOM ET PRENOM</div>
                <div class="col col-4">LIEN DE PARENTÉ</div>
                <div class="col col-5">TYPE DE DECLARATION</div>
                <div class="col col-6">MONTANT</div>
                <div class="col col-7">PIECE JOINTE</div>
                <div class="col col-8">STATUT</div>
                <div class="col col-9"></div>
            </li>
            <?php
require_once 'database.php';
$sql = 'SELECT * FROM dossier WHERE utilisateur_id = :utilisateur_id ORDER BY date_depot DESC';
$sqlstate = $pdo->prepare($sql);
$sqlstate->execute(['utilisateur_id' => $utilisateur['id']]);


            while ($row = $sqlstate->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $nom = $row['nom'];
                $prenom = $row['prenom'];
                $lien_parente = $row['lien_parente'];
                $type_declaration = $row['type_declaration'];
                $montant = $row['montant'];
                $date_depot = $row['date_depot'];
                $statut = $row['statut'];
                $piece_jointe = $row['piece_jointe'];
                $row_class = $statut === 'Remboursé' ? 'rembourse' : 'non-rembourse';
                $button_class = $statut === 'Remboursé' ? 'button-3' : 'button-4';
                $button_disabled = $statut === 'Remboursé' ? 'disabled' : '';
                $button_text = $statut === 'Remboursé' ? 'Remboursé' : 'Non Remboursé';
                echo "
                <li class='table-row $row_class' id='dossier-$id' data-date-depot='$date_depot'>
                    <div class='col col-1' data-label='DOSSIER'>$id</div>
                    <div class='col col-2' data-label='DATE DEPOT'>$date_depot</div>
                    <div class='col col-3' data-label='NOM ET PRENOM'>$nom $prenom</div>
                    <div class='col col-4' data-label='LIEN DE PARENTÉ'>$lien_parente</div>
                    <div class='col col-5' data-label='TYPE DE DECLARATION'>$type_declaration</div>
                    <div class='col col-6' data-label='MONTANT'>$montant DH</div>
                    <div class='col col-7' data-label='PIECE JOINTE'>";
                if (!empty($piece_jointe)) {
                    echo "<a href='uploads/$piece_jointe' target='_blank'>Voir</a>";
                } else {
                    echo "Aucune";
                }
                echo "</div>
                    <div class='col col-8' data-label='STATUT'>
                        <button class='statut-btn $button_class' onclick='changeStatut(\"Remboursé\", $id)' $button_disabled>$button_text</button>
                    </div>
                    <div class='col col-9' data-label=''>
                        <form method='post'>
                            <input type='hidden' name='dossier_id' value='$id'>
                            <button type='submit' name='delete_dossier' class='delete-btn'>Supprimer</button>

                        </form>
                    </div>
                </li>";
            }


 
            ?>

        </ul>
    </div> 

    <script>
    function updateBackgroundColor(row) {
            const dateDepot = row.getAttribute('data-date-depot');
            const dateDepotObj = new Date(dateDepot);
            const today = new Date();
            const diffDays = Math.ceil((today - dateDepotObj) / (1000 * 60 * 60 * 24));

            const statutBtn = row.querySelector('.statut-btn');
            const statut = statutBtn ? statutBtn.textContent.trim() : '';

            if (diffDays > 1 && statut === 'Non Remboursé') {
                row.classList.add('retard'); 
                const dossierId = row.querySelector('.col-1').textContent.trim();
                const nom = row.querySelector('.col-3').textContent.split(' ')[0];
                const prenom = row.querySelector('.col-3').textContent.split(' ')[1];

                fetch('send_email2.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${dossierId}&nom=${nom}&prenom=${prenom}`
                }).then(response => {
                    console.log('Email de retard envoyé');
                }).catch(error => {
                    console.error('Erreur lors de l\'envoi de l\'email:', error);
                });
            } else {
                row.classList.remove('retard'); 
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('.table-row');
            rows.forEach(updateBackgroundColor);
        });


    </script>
         <?php  if (isset($_POST['delete_dossier'])) {
                $dossier_id = $_POST['dossier_id'];
                $sql = 'DELETE FROM dossier WHERE id = ?';
                $sqlstate = $pdo->prepare($sql);
                $sqlstate->execute([$dossier_id]);
                exit();
            } ?>
   
</body>

</html>