<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION["usuario_nome"])) {
    echo json_encode(["usuario" => $_SESSION["usuario_nome"]]);
} else {
    echo json_encode(["usuario" => null]);
}
