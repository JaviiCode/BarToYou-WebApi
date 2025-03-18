<?php
use Dotenv\Dotenv;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Cargar variables de entorno de Laravel
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Conectar a la base de datos usando las credenciales de Laravel
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

// Verificar si hay un token en la cabecera Authorization
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(["error" => "Acceso no autorizado. Token no proporcionado."]);
    http_response_code(401);
    exit;
}

$token = str_replace("Bearer ", "", $headers['Authorization']);

// Buscar el usuario con ese token y verificar si ha expirado
$stmt = $pdo->prepare("SELECT * FROM members WHERE token = :token LIMIT 1");
$stmt->execute(["token" => $token]);
$user = $stmt->fetch();

if (!$user || strtotime($user['expiration_date_token']) < time()) {
    echo json_encode(["error" => "Token inválido o expirado."]);
    http_response_code(401);
    exit;
}

// Si el token es válido, el usuario está autenticado
define("AUTH_USER", $user);
