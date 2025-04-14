
<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=cantinaplus_test","root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "<p>Banco de dados conectado com sucesso!</p>";
} catch (PDOException $erro) {
    echo "<h2 style='color:red;'>Erro: " . $erro->getMessage() . 
        "</h2>";
}
?>