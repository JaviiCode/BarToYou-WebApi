<?php
use Dotenv\Dotenv;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Cargar variables de entorno de Laravel
require __DIR__ . '/../vendor/autoload.php'; // Cargar dependencias de Laravel
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

// Leer los datos enviados en la petición
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name']) || !isset($data['password'])) {
    echo json_encode(["error" => "El nombre y la contraseña son requeridos"]);
    exit;
}

$name = $data['name'];
$password = $data['password'];

// Buscar usuario por nombre en la base de datos
$stmt = $pdo->prepare("SELECT * FROM members WHERE name = :name LIMIT 1");
$stmt->execute(["name" => $name]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(["error" => "Credenciales incorrectas"]);
    exit;
}

// Generar un nuevo token
$token = bin2hex(random_bytes(32));
$expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Guardar el token en la base de datos
$stmt = $pdo->prepare("UPDATE members SET token = :token, expiration_date_token = :expiration WHERE id = :id");
$stmt->execute([
    "token" => $token,
    "expiration" => $expiration,
    "id" => $user["id"]
]);

// Responder con los datos del usuario autenticado
echo json_encode([
    "message" => "Login exitoso",
    "token" => $token,
    "expiration" => $expiration,
    "user" => [
        "id" => $user["id"],
        "name" => $user["name"],
        "role_id" => $user["role_id"],
    ]
]);
?>
