<?php
include("essentiel.php");
include("nav.php");

// Vérifier le rôle admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header('Location: index.php');
    exit;
}

// Récupérer la liste des éléments pour afficher les cases à cocher
try {
    $elementsStmt = $bdd->query("SELECT id, type FROM elements ORDER BY type");
    $allElements = $elementsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erreur SQL (éléments) : " . $e->getMessage());
}

// Traitement des mises à jour et suppressions
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Suppression d’un utilisateur
    if (isset($_POST['deleteUser'], $_POST['user_id_delete'])) {
        $userIdToDelete = (int) $_POST['user_id_delete'];
        if ($userIdToDelete === (int) $_SESSION['user_id']) {
            $message = "⚠ Vous ne pouvez pas supprimer votre propre compte.";
        } else {
            $delStmt = $bdd->prepare("DELETE FROM users WHERE id = ?");
            $delStmt->execute([$userIdToDelete]);
            $message = "✔ Utilisateur supprimé avec succès.";
        }
    }
    // Mise à jour des spécialisations via user_elements
    if (isset($_POST['updateUser'], $_POST['user_id_update'])) {
        $userIdToUpdate = (int) $_POST['user_id_update'];
        $selected = $_POST['elements_' . $userIdToUpdate] ?? [];

        try {
            $bdd->beginTransaction();
            // Supprimer les anciennes liaisons
            $delUE = $bdd->prepare("DELETE FROM user_elements WHERE user_id = ?");
            $delUE->execute([$userIdToUpdate]);

            // Réinsérer les nouvelles
            if (!empty($selected)) {
                $insertUE = $bdd->prepare("INSERT INTO user_elements (user_id, element_id) VALUES (:uid, :eid)");
                foreach ($selected as $elementId) {
                    $eid = (int) $elementId;
                    // Vérifier que l’élément existe
                    foreach ($allElements as $e) {
                        if ((int)$e['id'] === $eid) {
                            $insertUE->execute([
                                ':uid' => $userIdToUpdate,
                                ':eid' => $eid
                            ]);
                            break;
                        }
                    }
                }
            }

            $bdd->commit();
            $message = "✔ Spécialisations mises à jour pour l’utilisateur #$userIdToUpdate.";
        } catch (\PDOException $e) {
            $bdd->rollBack();
            $message = "Erreur lors de la mise à jour des spécialisations : " . $e->getMessage();
        }
    }
}

// Charger tous les users et leurs spécialisations actuelles
try {
    $usersStmt = $bdd->query("
        SELECT
          u.id,
          u.nom,
          u.role_id,
          GROUP_CONCAT(ue.element_id) AS current_elems
        FROM users AS u
        LEFT JOIN user_elements AS ue
          ON ue.user_id = u.id
        GROUP BY u.id, u.nom, u.role_id
        ORDER BY u.nom
    ");
    $allUsers = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erreur SQL (utilisateurs) : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau Administrateur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <div class="admin-panel">
    <h1 class="admin-panel__title">Panneau Administrateur</h1>

    <?php if ($message): ?>
      <div class="admin-panel__message"><?= htmlspecialchars($message, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <div class="admin-panel__grid-header">
      <div class="admin-panel__cell admin-panel__cell--name"><strong>Nom</strong></div>
      <div class="admin-panel__cell admin-panel__cell--spec"><strong>Spécialisations</strong></div>
      <div class="admin-panel__cell admin-panel__cell--actions"><strong>Actions</strong></div>
    </div>

    <?php foreach ($allUsers as $user): 
        // Transformer la chaîne de element_id en tableau
        $currentSpecs = [];
        if (!empty($user['current_elems'])) {
            $currentSpecs = explode(',', $user['current_elems']);
        }
    ?>
      <div class="admin-panel__grid-row">
        <!-- Nom -->
        <div class="admin-panel__cell admin-panel__cell--name">
          <?= htmlspecialchars($user['nom'], ENT_QUOTES) ?>
        </div>

        <!-- Spécialisations via user_elements -->
        <div class="admin-panel__cell admin-panel__cell--spec">
          <form method="post" action="panneau_admin.php" class="form-spec">
            <input type="hidden" name="user_id_update" value="<?= (int)$user['id'] ?>">
            <div class="form-spec__list">
              <?php foreach ($allElements as $el):
                  $checked = in_array((string)$el['id'], $currentSpecs, true) ? 'checked' : '';
              ?>
                <label class="form-spec__label">
                  <input
                    type="checkbox"
                    name="elements_<?= (int)$user['id'] ?>[]"
                    value="<?= (int)$el['id'] ?>"
                    <?= $checked ?>
                  >
                  <?= htmlspecialchars($el['type'], ENT_QUOTES) ?>
                </label>
              <?php endforeach; ?>
            </div>
            <button type="submit" name="updateUser" class="btn btn--update">Mettre à jour</button>
          </form>
        </div>

        <!-- Actions : suppression -->
        <div class="admin-panel__cell admin-panel__cell--actions">
          <?php if ((int)$user['id'] !== (int)$_SESSION['user_id']): ?>
            <form method="post" action="panneau_admin.php" class="form-delete" onsubmit="return confirm('Supprimer cet utilisateur ?');">
              <input type="hidden" name="user_id_delete" value="<?= (int)$user['id'] ?>">
              <button type="submit" name="deleteUser" class="btn btn--delete">Supprimer</button>
            </form>
          <?php else: ?>
            <span class="admin-panel__you">(Vous)</span>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
