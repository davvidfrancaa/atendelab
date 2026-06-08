<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // irá redireciona para a página pública
exit();
?>