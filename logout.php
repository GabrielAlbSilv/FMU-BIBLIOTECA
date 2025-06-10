<?php
session_start();
session_unset();    // Limpa as variáveis de sessão
session_destroy();  // Destroi a sessão
header("Location: index.html"); // Redireciona para a página de login
exit;
