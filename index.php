<?php

include("essentiel.php");
include("nav.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil – Académie des Mystères</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <main class="home-page">
    <section class="hero-section">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h1>Bienvenue à l’Académie des Mystères</h1>
        <p>Entrez dans un univers mystique : découvrez les créatures légendaires, les arts du codex élémentaire et plus encore.</p>
        <div class="hero-buttons">
          <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="inscription.php" class="btn-hero">S’inscrire</a>
            <a href="connexion.php" class="btn-hero btn-hero--outline">Se connecter</a>
          <?php else: ?>
            <a href="bestiaire.php" class="btn-hero">Voir le Bestiaire</a>
            <a href="codex.php" class="btn-hero btn-hero--outline">Voir le Codex</a>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <section class="features-section">
      <div class="feature-card">
        <img src="assets\img\bestiaire.png" alt="Illustration Bestiaire">
        <div class="feature-card__content">
          <h2>Bestiaire</h2>
          <p>Explorez notre collection de créatures mystérieuses. Connectez-vous pour ajouter vos propres découvertes.</p>
          <a href="bestiaire.php" class="btn-magique">Accéder au Bestiaire</a>
        </div>
      </div>

      <div class="feature-card">
        <img src="assets\img\codex.png" alt="Illustration Codex">
        <div class="feature-card__content">
          <h2>Codex de Sorts</h2>
          <p>Découvrez et enrichissez le recueil des sorts élémentaires. Les initiés peuvent ajouter ou modifier des entrées.</p>
          <a href="codex.php" class="btn-magique">Accéder au Codex</a>
        </div>
      </div>

      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
        <div class="feature-card">
          <img src="assets\img\admin.png" alt="Illustration Panneau Admin">
          <div class="feature-card__content">
            <h2>Panneau Admin</h2>
            <p>Gérez les utilisateurs, validez les spécialisations et maintenez l’ordre dans l’Académie.</p>
            <a href="panneau_admin.php" class="btn-magique">Accéder au Panneau</a>
          </div>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
