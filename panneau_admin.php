<?php
include("essentiel.php");
include("nav.php");

// Vérifier le rôle admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header('Location: index.php');
    exit;
}

// Récupérer éléments pour les checkboxes
$elementsStmt = $bdd->query("SELECT id, type FROM elements ORDER BY type");
$allElements = $elementsStmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if (isset($_POST['updateUser'], $_POST['user_id_update'])) {
        $userIdToUpdate = (int) $_POST['user_id_update'];
        $selected = $_POST['elements_' . $userIdToUpdate] ?? [];
        if (empty($selected)) {
            $specialisation = null;
        } else {
            $safe = array_map('intval', $selected);
            $specialisation = implode(',', $safe);
        }
        $updStmt = $bdd->prepare("UPDATE users SET specialisation = :spec WHERE id = :uid");
        $updStmt->execute([
            ':spec' => $specialisation,
            ':uid'  => $userIdToUpdate
        ]);
        $message = "✔ Spécialisations mises à jour pour l’utilisateur #$userIdToUpdate.";
    }
}

// Récupérer tous les users
$usersStmt = $bdd->query("SELECT id, nom, role_id, specialisation FROM users ORDER BY nom");
$allUsers = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Panneau Administrateur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

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

    <?php foreach ($allUsers as $user): ?>
      <div class="admin-panel__grid-row">

        <div class="admin-panel__cell admin-panel__cell--name">
          <?= htmlspecialchars($user['nom'], ENT_QUOTES) ?>
        </div>

        <!-- Spécialisations -->
        <div class="admin-panel__cell admin-panel__cell--spec">
          <form method="post" action="panneau_admin.php" class="form-spec">
            <input type="hidden" name="user_id_update" value="<?= (int)$user['id'] ?>">
            <div class="form-spec__list">
              <?php
                $currentSpecs = [];
                if (!empty($user['specialisation'])) {
                  $currentSpecs = explode(',', $user['specialisation']);
                }
                foreach ($allElements as $el):
                  $checked = in_array($el['id'], $currentSpecs) ? 'checked' : '';
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
