<?php
include("essentiel.php");
include("nav.php");

// Vérifier que l’utilisateur est administrateur (role_id = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header('Location: index.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteMonster'], $_POST['monster_id'])) {
    $monsterIdToDelete = (int) $_POST['monster_id'];
    // Supprimer l’image du serveur si elle existe !!!!!!!!
    $stmtImg = $bdd->prepare("SELECT image FROM bestiaire WHERE id = ?");
    $stmtImg->execute([$monsterIdToDelete]);
    $rowImg = $stmtImg->fetch(PDO::FETCH_ASSOC);
    if ($rowImg && !empty($rowImg['image'])) {
        $filePath = __DIR__ . "/assets/img/" . $rowImg['image'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }
    // Supprimer la fiche en Base de Données
    $delStmt = $bdd->prepare("DELETE FROM bestiaire WHERE id = ?");
    if ($delStmt->execute([$monsterIdToDelete])) {
        $message = "✔ Le monstre a bien été supprimé.";
    } else {
        $message = "❌ Erreur lors de la suppression du monstre.";
    }
}

// Récupérer tous les monstres pour affichage
try {
    $stmt = $bdd->query("
        SELECT 
          b.id, 
          b.nom   AS nom_monstre, 
          b.type  AS type_monstre
        FROM bestiaire AS b
        ORDER BY b.nom ASC
    ");
    $allMonstres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Supprimer des Monstres</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div class="page-ajout-monstre">
    <h2>Supprimer des Monstres</h2>

    <?php if ($message): ?>
      <div class="message <?php echo (strpos($message, '✔') === 0) ? 'succes' : 'erreur'; ?>">
        <?= htmlspecialchars($message, ENT_QUOTES) ?>
      </div>
    <?php endif; ?>

    <?php if (empty($allMonstres)): ?>
      <p>Aucun monstre présent dans la base pour l’instant.</p>
    <?php else: ?>
      <ul class="liste-bestiaires">
        <?php foreach ($allMonstres as $monstre): ?>
          <li class="item-bestiaire">
            <span>
              <strong><?= htmlspecialchars($monstre['nom_monstre']) ?></strong>
               (Type : <?= htmlspecialchars($monstre['type_monstre']) ?>)
            </span>
            <form method="post" action="supression_monstre.php" class="form-delete-monster" style="display:inline-block; margin-left:1rem;">
              <input type="hidden" name="monster_id" value="<?= (int)$monstre['id'] ?>">
              <button 
                type="submit" 
                name="deleteMonster" 
                class="btn btn--delete"
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer « <?= htmlspecialchars($monstre['nom_monstre'], ENT_QUOTES) ?> » ?');"
              >
                Supprimer
              </button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <div style="margin-top: 2rem;">
      <a href="bestiaire.php" class="btn-magique">Retour au Bestiaire</a>
    </div>
  </div>
</body>
</html>
