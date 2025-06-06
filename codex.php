<?php
include("essentiel.php");  
include("nav.php");

function e($str) {
    return htmlspecialchars($str);
}

try {
    $sql = "
        SELECT
          c.id                  AS codex_id,
          c.nom                 AS nom_sort,
          c.image               AS image_sort,
          e.type                AS element_type,
          GROUP_CONCAT(u.nom SEPARATOR ', ') AS users_with_element
        FROM codex AS c
        JOIN elements AS e
          ON c.element_id = e.id
        LEFT JOIN users AS u
          ON FIND_IN_SET(e.id, u.specialisation)
        GROUP BY c.id
        ORDER BY c.id DESC
    ";

    $stmt = $bdd->query($sql);
    $allSorts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Codex des Sorts</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <div class="page-ajout-monstre">
    <h3>Codex des Sorts</h3>

    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
      <div style="margin-bottom: 1rem;">
        <a href="ajoutcodex.php" class="btn-magique">Ajouter un nouveau sort</a>
      </div>
    <?php endif; ?>

    <?php if (empty($allSorts)): ?>
      <p>Aucun sort enregistré pour l’instant.</p>
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
        <p><a href="ajoutcodex.php" class="btn-magique">Ajouter un premier sort</a></p>
      <?php endif; ?>
    <?php else: ?>
      <ul style="list-style: none; padding: 0;">
        <?php foreach ($allSorts as $sort): ?>
          <li style="margin-bottom: 2rem; border-bottom: 1px solid #ccc; padding-bottom: 1rem;">
            <h4 style="margin: 0;">
              <?= e($sort['nom_sort']) ?>
              <small style="font-size: 0.85rem; color: #aaa;">
                (Élément : <?= e($sort['element_type']) ?>)
              </small>
            </h4>

            <?php if (!empty($sort['image_sort'])): ?>
              <div style="margin-top: 0.5rem;">
                <img
                  src="assets/img/<?= e($sort['image_sort']) ?>"
                  alt="Image du sort <?= e($sort['nom_sort']) ?>"
                  style="max-width: 200px; border: 1px solid #666; border-radius: 0.4rem;"
                >
              </div>
            <?php endif; ?>

            <p>
              Utilisateurs possédant l’élément « <?= e($sort['element_type']) ?> » :
              <?php
                if (empty($sort['users_with_element'])) {
                  echo "<em>Aucun utilisateur</em>";
                } else {
                  echo e($sort['users_with_element']);
                }
              ?>
            </p>

            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
              <p style="margin-top: 0.5rem;">
                <a href="modifierCodex.php?id=<?= (int)$sort['codex_id'] ?>" class="btn-magique">Modifier</a>
              </p>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
      <div style="margin-top: 2rem;">
        <a href="ajoutcodex.php" class="btn-magique">Ajouter un nouveau sort</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
