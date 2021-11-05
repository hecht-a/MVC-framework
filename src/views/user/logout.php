<?php
$this->title = "Déconnexion";

session_destroy();
session_start();
$_SESSION["success"] = "Déconnecté avec succès";
header("Location: /?success=1");