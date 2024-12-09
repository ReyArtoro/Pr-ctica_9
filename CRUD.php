<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "registros";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$editMode = false;
$editUser = null;

if (isset($_POST['create'])) {
    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoM = $_POST['apellidoM'];
    $correoE = $_POST['correoE'];
    $telefono = $_POST['telefono'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $hobby = $_POST['hobby'];
    $genero = $_POST['genero'];

    $sql = "INSERT INTO users (Nombre, ApellidoP, ApellidoM, correoE, Telefono, Fecha_Nacimiento, Hobby, Genero) 
            VALUES ('$nombre', '$apellidoP', '$apellidoM', '$correoE', '$telefono', '$fechaNacimiento', '$hobby', '$genero')";
    $conn->query($sql);
    header("Location: crud.php");
}

if (isset($_GET['edit'])) {
    $editMode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM users WHERE ID = $id");
    $editUser = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoM = $_POST['apellidoM'];
    $correoE = $_POST['correoE'];
    $telefono = $_POST['telefono'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $hobby = $_POST['hobby'];
    $genero = $_POST['genero'];

    $sql = "UPDATE users SET 
            Nombre='$nombre', ApellidoP='$apellidoP', ApellidoM='$apellidoM', 
            correoE='$correoE', Telefono='$telefono', Fecha_Nacimiento='$fechaNacimiento', 
            Hobby='$hobby', Genero='$genero' WHERE ID = $id";
    $conn->query($sql);
    header("Location: crud.php");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE ID = $id");
    header("Location: CRUD.php");
}

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD - Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="CSS/estulos.css">
</head>
<body>
    <div class="container">
        <header>
        <h1 class="desktop">CRUD - Gestión de Usuarios</h1>
        </header>

        <div class="form-section">
            <h2><?php echo $editMode ? "Editar Usuario" : "Agregar Usuario"; ?></h2>
            <form method="POST">
                <?php if ($editMode): ?>
                    <input type="hidden" name="id" value="<?php echo $editUser['ID']; ?>">
                <?php endif; ?>
                <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $editMode ? $editUser['Nombre'] : ''; ?>" required>
                <input type="text" name="apellidoP" placeholder="Apellido Paterno" value="<?php echo $editMode ? $editUser['ApellidoP'] : ''; ?>" required>
                <input type="text" name="apellidoM" placeholder="Apellido Materno" value="<?php echo $editMode ? $editUser['ApellidoM'] : ''; ?>" required>
                <input type="email" name="correoE" placeholder="Correo Electrónico" value="<?php echo $editMode ? $editUser['correoE'] : ''; ?>" required>
                <input type="text" name="telefono" placeholder="Teléfono" value="<?php echo $editMode ? $editUser['Telefono'] : ''; ?>" required>
                <input type="date" name="fechaNacimiento" value="<?php echo $editMode ? $editUser['Fecha_Nacimiento'] : ''; ?>" required>
                <input type="text" name="hobby" placeholder="Hobby" value="<?php echo $editMode ? $editUser['Hobby'] : ''; ?>" required>
                <select name="genero" required>
                    <option value="" disabled <?php echo !$editMode ? 'selected' : ''; ?>>Seleccione Género</option>
                    <option value="Masculino" <?php echo $editMode && $editUser['Genero'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="Femenino" <?php echo $editMode && $editUser['Genero'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                </select>
                <button type="submit" name="<?php echo $editMode ? "update" : "create"; ?>">
                    <?php echo $editMode ? "Actualizar" : "Guardar"; ?>
                </button>
            </form>
        </div>

        <div class="table-section">
            <h2>Lista de Usuarios</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Fecha Nacimiento</th>
                        <th>Hobby</th>
                        <th>Género</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['Nombre']; ?></td>
                            <td><?php echo $row['ApellidoP']; ?></td>
                            <td><?php echo $row['ApellidoM']; ?></td>
                            <td><?php echo $row['correoE']; ?></td>
                            <td><?php echo $row['Telefono']; ?></td>
                            <td><?php echo $row['Fecha_Nacimiento']; ?></td>
                            <td><?php echo $row['Hobby']; ?></td>
                            <td><?php echo $row['Genero']; ?></td>
                            <td>
                                <a href="crud.php?edit=<?php echo $row['ID']; ?>">Editar</a> |
                                <a href="crud.php?delete=<?php echo $row['ID']; ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
