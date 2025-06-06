<?php
include("essentiel.php");
include("nav.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header('Location: index.php');
    exit;
}

$monsterId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($monsterId <= 0) {
    die("ID de monstre invalide.");
}

$stmt = $bdd->prepare("SELECT * FROM bestiaire WHERE id = ?");
$stmt->execute([$monsterId]);
$monstre = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$monstre) {
    die("Monstre introuvable.");
}

$messageErreur = '';
$messageSucces = '';

if (isset($_POST['enregistrerModifs'])) {
    $nom         = trim($_POST['nom'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $nouveauNomImage = null;

    if ($nom === '' || $type === '') {
        $messageErreur = "Les champs « nom » et « type » sont obligatoires.";
    } else {
        if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
            $imageInfo = pathinfo($_FILES['upload']['name']);
            $imageExt  = strtolower($imageInfo['extension']);
            $authorizedExt = ['png','jpeg','jpg','webp','bmp','svg'];

            if (in_array($imageExt, $authorizedExt, true)) {
                $nouveauNomImage = time() . '_' . rand(1000, 9999) . '.' . $imageExt;
                $destination = __DIR__ . "/assets/img/" . $nouveauNomImage;

                if (!move_uploaded_file($_FILES['upload']['tmp_name'], $destination)) {
                    $messageErreur = "Impossible de déplacer le fichier image.";
                } else {
                    if (!empty($monstre['image'])) {
                        $ancienFichier = __DIR__ . "/assets/img/" . $monstre['image'];
                        if (file_exists($ancienFichier)) {
                            @unlink($ancienFichier);
                        }
                    }
                }
            } else {
                $messageErreur = "Format d’image non autorisé. Seuls : png, jpg, jpeg, webp, bmp, svg.";
            }
        }

        if ($messageErreur === '') {
            if ($nouveauNomImage !== null) {
                $sql = "
                    UPDATE bestiaire
                    SET nom = :nom, type = :type, description = :description, image = :image
                    WHERE id = :id
                ";
                $upd = $bdd->prepare($sql);
                $params = [
                    ':nom'         => $nom,
                    ':type'        => $type,
                    ':description' => $description,
                    ':image'       => $nouveauNomImage,
                    ':id'          => $monsterId
                ];
            } else {
                $sql = "
                    UPDATE bestiaire
                    SET nom = :nom, type = :type, description = :description
                    WHERE id = :id
                ";
                $upd = $bdd->prepare($sql);
                $params = [
                    ':nom'         => $nom,
                    ':type'        => $type,
                    ':description' => $description,
                    ':id'          => $monsterId
                ];
            }

            $success = $upd->execute($params);
            if ($success) {
                $messageSucces = "La fiche a bien été mise à jour.";
                $stmt->execute([$monsterId]);
                $monstre = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $messageErreur = "Erreur lors de la mise à jour. Réessayez.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier le monstre</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <div class="page-ajout-monstre">
    <h3>Modifier le monstre « <?= htmlspecialchars($monstre['nom'], ENT_QUOTES) ?> »</h3>

    <?php if ($messageErreur): ?>
      <div class="message erreur"><?= htmlspecialchars($messageErreur, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <?php if ($messageSucces): ?>
      <div class="message succes"><?= htmlspecialchars($messageSucces, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form action="modifierMonstre.php?id=<?= $monsterId ?>" method="post" enctype="multipart/form-data">
      <div>
        <label for="nom">Nom :</label><br>
        <input
          type="text"
          id="nom"
          name="nom"
          required
          value="<?= htmlspecialchars($monstre['nom'], ENT_QUOTES) ?>"
        >
      </div>

      <div>
        <label for="type">Type :</label><br>
        <input
          type="text"
          id="type"
          name="type"
          required
          value="<?= htmlspecialchars($monstre['type'], ENT_QUOTES) ?>"
        >
      </div>

      <div>
        <label for="description">Description :</label><br>
        <textarea
          id="description"
          name="description"
          rows="4"
          cols="50"
        ><?= htmlspecialchars($monstre['description'], ENT_QUOTES) ?></textarea>
      </div>

      <div>
        <label for="upload">Changer la photo (optionnel) :</label><br>
        <input
          type="file"
          id="upload"
          name="upload"
          accept=".png,.jpg,.jpeg,.webp,.bmp,.svg"
        >
      </div>

      <?php if (!empty($monstre['image'])): ?>
        <div style="margin-top:0.5rem;">
          <p>Photo actuelle :</p>
          <img
            src="assets/img/<?= htmlspecialchars($monstre['image'], ENT_QUOTES) ?>"
            alt="Photo de <?= htmlspecialchars($monstre['nom'], ENT_QUOTES) ?>"
            style="max-width:150px; border:1px solid #666; border-radius:0.4rem;"
          >
        </div>
      <?php endif; ?>

      <div style="margin-top: 10px;">
        <button type="submit" name="enregistrerModifs">Enregistrer les modifications</button>
      </div>
    </form>
  </div>
</body>
</html>
