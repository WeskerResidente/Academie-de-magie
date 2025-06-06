<?php
include("essentiel.php");
include("nav.php");

function e($str) {
    return htmlspecialchars($str);
}

$messageSucces = '';
$messageErreur = '';
$uniqueName = null;

if (isset($_POST['creerMonstre'])) {
    $monstre     = trim($_POST['nom'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $idUser      = $_SESSION['user_id'] ?? null;

    if ($monstre === '' || $type === '') {
        $messageErreur = "Les champs « nom » et « type » sont obligatoires.";
    } elseif (is_null($idUser)) {
        $messageErreur = "Vous devez être connecté pour ajouter un monstre.";
    } else {

        if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
            $imageInfo = pathinfo($_FILES['upload']['name']);
            $imageExt  = strtolower($imageInfo['extension']);
            $autorizedExt = ['png','jpeg','jpg','webp','bmp','svg'];

            if (in_array($imageExt, $autorizedExt)) {
                $uniqueName = time() . '_' . rand(1000, 9999) . '.' . $imageExt;
                $destination = __DIR__ . "/assets/img/" . $uniqueName;

                if (!move_uploaded_file($_FILES['upload']['tmp_name'], $destination)) {
                    $messageErreur = "Impossible de déplacer le fichier image.";
                }
            } else {
                $messageErreur = "Format de fichier non autorisé (png, jpg, webp, bmp, svg).";
            }
        }
        if ($messageErreur === '') {
            $sql = "
                INSERT INTO bestiaire 
                    (nom, type, description, created_id, image)
                VALUES 
                    (:nom, :type, :description, :uid, :image)
            ";
            $stmt = $bdd->prepare($sql);
            $stmt->bindValue(':nom',         $monstre);
            $stmt->bindValue(':type',        $type);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':uid',         $idUser);
            $stmt->bindValue(':image',       $uniqueName);

            if ($stmt->execute()) {
                $messageSucces = "Le monstre « " . e($monstre) . " » a bien été créé.";
            } else {
                $messageErreur = "Erreur lors de l’insertion en base. Veuillez réessayer.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un monstre</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>


  <div class="page-ajout-monstre">
    <h3>Ajouter un monstre</h3>

    <?php if ($messageErreur): ?>
      <div class="message erreur"><?= e($messageErreur) ?></div>
    <?php endif; ?>

    <?php if ($messageSucces): ?>
      <div class="message succes"><?= e($messageSucces) ?></div>
    <?php endif; ?>

    <form action="ajoutbestiaire.php" method="post" enctype="multipart/form-data">
      <div>
        <label for="nom">Nom du monstre :</label>
        <input type="text" id="nom" name="nom" required value="<?= e($_POST['nom'] ?? '') ?>">
      </div>

      <div>
        <label for="type">Type de monstre :</label>
        <input type="text" id="type" name="type" required value="<?= e($_POST['type'] ?? '') ?>">
      </div>

      <div>
        <label for="description">Description :</label>
        <textarea id="description" name="description" rows="4" placeholder="Décrivez votre monstre..."><?= e($_POST['description'] ?? '') ?></textarea>
      </div>

      <div>
        <label for="upload">Image (optionnel) :</label>
        <input type="file" id="upload" name="upload" accept=".png,.jpg,.jpeg,.webp,.bmp,.svg">
      </div>

      <div>
        <button type="submit" name="creerMonstre">Envoyer</button>
      </div>
    </form>
  </div>

</body>
</html>