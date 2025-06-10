<?php

include("essentiel.php");
include("nav.php");

// Autoriser seulement l’admin (role_id = 2) ou le créateur du sort
$sortId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($sortId <= 0) {
    die("ID de sort invalide.");
}

// Charger les données du sort
$stmtSort = $bdd->prepare("SELECT * FROM codex WHERE id = ?");
$stmtSort->execute([$sortId]);
$sort = $stmtSort->fetch(PDO::FETCH_ASSOC);
if (!$sort) {
    die("Sort introuvable.");
}

// Vérifier le droit d’accès
if (
    !isset($_SESSION['user_id']) ||
    (
      $_SESSION['role_id'] != 2 &&
      $_SESSION['user_id']  != $sort['created_id']
    )
) {
    header('Location: codex.php');
    exit;
}

// Charger les éléments pour le <select>
$elStmt = $bdd->query("SELECT id, type FROM elements ORDER BY type");
$allElements = $elStmt->fetchAll(PDO::FETCH_ASSOC);

$messageErreur = '';
$messageSucces = '';

if (isset($_POST['enregistrerModifs'])) {
    $nom       = trim($_POST['nom'] ?? '');
    $elementId = isset($_POST['element_id']) ? (int)$_POST['element_id'] : 0;
    $nouvelleImage = null;

    if ($nom === '' || $elementId <= 0) {
        $messageErreur = "Les champs « Nom » et « Élément » sont obligatoires.";
    } else {
        // Upload image si fourni
        if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
            $info  = pathinfo($_FILES['upload']['name']);
            $ext   = strtolower($info['extension']);
            $autorises = ['png','jpg','jpeg','webp','bmp','svg'];
            if (in_array($ext, $autorises, true)) {
                $nouvelleImage = time() . '_' . rand(1000,9999) . '.' . $ext;
                $dest = __DIR__ . "/assets/img/" . $nouvelleImage;
                if (!move_uploaded_file($_FILES['upload']['tmp_name'], $dest)) {
                    $messageErreur = "Impossible de déplacer l’image.";
                } else {
                    // supprimer ancienne image
                    if (!empty($sort['image'])) {
                        $old = __DIR__ . "/assets/img/" . $sort['image'];
                        if (file_exists($old)) @unlink($old);
                    }
                }
            } else {
                $messageErreur = "Format non autorisé (png,jpg,etc.).";
            }
        }

        if ($messageErreur === '') {
            // Préparer la requête UPDATE
            if ($nouvelleImage !== null) {
                $sql = "
                  UPDATE codex
                  SET nom = :nom,
                      element_id = :eid,
                      image = :img
                  WHERE id = :id
                ";
                $params = [
                  ':nom' => $nom,
                  ':eid' => $elementId,
                  ':img' => $nouvelleImage,
                  ':id'  => $sortId
                ];
            } else {
                $sql = "
                  UPDATE codex
                  SET nom = :nom,
                      element_id = :eid
                  WHERE id = :id
                ";
                $params = [
                  ':nom' => $nom,
                  ':eid' => $elementId,
                  ':id'  => $sortId
                ];
            }
            $upd = $bdd->prepare($sql);
            if ($upd->execute($params)) {
                $messageSucces = "Le sort a été mis à jour.";
                // Recharger
                $stmtSort->execute([$sortId]);
                $sort = $stmtSort->fetch(PDO::FETCH_ASSOC);
            } else {
                $messageErreur = "Erreur lors de la mise à jour en base.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier le sort</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <div class="page-modification-monstre">
    <h3>Modifier le sort « <?= htmlspecialchars($sort['nom'], ENT_QUOTES) ?> »</h3>

    <?php if ($messageErreur): ?>
      <div class="message erreur"><?= htmlspecialchars($messageErreur, ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?php if ($messageSucces): ?>
      <div class="message succes"><?= htmlspecialchars($messageSucces, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form action="modifierCodex.php?id=<?= $sortId ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nom">Nom du sort :</label>
        <input type="text" id="nom" name="nom" required
               value="<?= htmlspecialchars($sort['nom'], ENT_QUOTES) ?>">
      </div>

      <div class="form-group">
        <label for="element_id">Élément :</label>
        <select id="element_id" name="element_id" required>
          <option value="">-- Sélectionner --</option>
          <?php foreach ($allElements as $el): ?>
            <option value="<?= $el['id'] ?>"
              <?= $el['id']==$sort['element_id']?'selected':'' ?>>
              <?= htmlspecialchars($el['type'], ENT_QUOTES) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="upload">Nouvelle image (optionnel) :</label>
        <input type="file" id="upload" name="upload" accept=".png,.jpg,.jpeg,.webp,.bmp,.svg">
      </div>

      <?php if (!empty($sort['image'])): ?>
        <div class="form-group">
          <p>Image actuelle :</p>
          <img src="assets/img/<?= htmlspecialchars($sort['image'], ENT_QUOTES) ?>"
               alt="Image de <?= htmlspecialchars($sort['nom'], ENT_QUOTES) ?>"
               style="max-width:200px;">
        </div>
      <?php endif; ?>

      <div class="form-actions">
        <button type="submit" name="enregistrerModifs" class="btn-enregistrer">Enregistrer</button>
      </div>
    </form>
  </div>
</body>
</html>
