<?php

include("essentiel.php");
include("nav.php");

echo '<p style="color:yellow; background:#333; padding:5px;">
        DEBUG rôle en session : ' . (isset($_SESSION['role_id']) ? $_SESSION['role_id'] : 'indéfini') . '
      </p>';