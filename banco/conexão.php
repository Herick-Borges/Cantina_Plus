<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=cantinaplus_test", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Remova mensagens de depuração em produção
} catch (PDOException $erro) {
    error_log("Erro ao conectar ao banco de dados: " . $erro->getMessage());
    die("Erro ao conectar ao banco de dados.");
}
