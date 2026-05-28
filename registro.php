<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - 2Work Transport Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #f97316;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: var(--primary-color);
            padding: 1rem 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .registration-form-container {
            background: white;
            border-radius: 10px;
            padding: 3rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 800px;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 2rem;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-center {
            display: block;
            margin: 2rem auto 0 auto;
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-links a:hover {
            color: var(--secondary-color);
        }

        .form-title {
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-bus"></i> 2Work Transport
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="acerca.html">Acerca de</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="servicios.html">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mapa.html">Ubicación</a>
                    </li>
                    <li>
                        <a class="nav-link" href="login.php">Iniciar sesión</a>
                    </li>
                    <li>
                        <a class="nav-link active" href="registro.php">Registrarse</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.html">Contáctenos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h1 class="display-4">Registro</h1>
            <p class="lead">Cree su cuenta para acceder a nuestros servicios</p>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="registration-form-container">
                        <h3 class="form-title">Formulario de Registro</h3>
                        
                        <?php
                        error_reporting(E_ALL);
                        ini_set('display_errors', 1);
                        
                        // INCLUIR TU ARCHIVO DE CONEXIÓN
                        require_once "conexion.php";
                        
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            // Obtener datos del formulario
                            $nombre = mysqli_real_escape_string($conn, $_POST['nombre'] ?? '');
                            $primer_apellido = mysqli_real_escape_string($conn, $_POST['primer_apellido'] ?? '');
                            $segundo_apellido = mysqli_real_escape_string($conn, $_POST['segundo_apellido'] ?? '');
                            $cedula = mysqli_real_escape_string($conn, $_POST['cedula'] ?? '');
                            $telefono = mysqli_real_escape_string($conn, $_POST['telefono'] ?? '');
                            $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
                            $password = $_POST['password'] ?? '';
                            $confirm_password = $_POST['confirm_password'] ?? '';

                            // Validaciones
                            if (empty($nombre) || empty($primer_apellido) || empty($segundo_apellido) || 
                                empty($cedula) || empty($telefono) || empty($email) || empty($password)) {
                                $error = "Todos los campos son obligatorios";
                            } elseif ($password !== $confirm_password) {
                                $error = "Las contraseñas no coinciden";
                            } else {
                                // Verificar si el usuario ya existe
                                $check_sql = "SELECT cedula FROM usuario WHERE cedula = '$cedula' OR email = '$email'";
                                $check_result = mysqli_query($conn, $check_sql);

                                if (mysqli_num_rows($check_result) > 0) {
                                    $error = "Ya existe un usuario con esta cédula o email";
                                } else {
                                    // Hash de la contraseña
                                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                    // INSERTAR en la base de datos
                                    $sql = "INSERT INTO usuario (cedula, nombre, primer_apellido, segundo_apellido, telefono, email, password, fecha_registro, activo) 
                                            VALUES ('$cedula', '$nombre', '$primer_apellido', '$segundo_apellido', '$telefono', '$email', '$hashed_password', NOW(), 1)";

                                    if (mysqli_query($conn, $sql)) {
                                        $success = "✅ Registro exitoso. Ahora puede iniciar sesión.";
                                        // Limpiar campos después del registro exitoso
                                        $_POST = array();
                                    } else {
                                        $error = "❌ Error al registrar: " . mysqli_error($conn);
                                    }
                                }
                            }
                        }
                        ?>

                        <!-- Mostrar mensajes -->
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario -->
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $_POST['nombre'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="primer_apellido" class="form-label">Primer apellido<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="<?php echo $_POST['primer_apellido'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="segundo_apellido" class="form-label">Segundo apellido<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="<?php echo $_POST['segundo_apellido'] ?? ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cedula" class="form-label">Cédula <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" value="<?php echo $_POST['cedula'] ?? ''; ?>" pattern="[0-9]{9,12}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo $_POST['telefono'] ?? ''; ?>" pattern="[0-9]{8,}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terminos" name="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="pagina_error_404.html" class="text-primary">términos y condiciones</a> <span class="text-danger">*</span>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-center">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-bus"></i> 2Work Transport</h5>
                    <p>Transporte ejecutivo de calidad para empresas en Costa Rica.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Enlaces Rápidos</h5>
                    <div class="footer-links">
                        <a href="index.html">Inicio</a>
                        <a href="acerca.html">Acerca de</a>
                        <a href="servicios.html">Servicios</a>
                        <a href="mapa.html">Ubicación</a>
                        <a href="contacto.html">Contáctenos</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-map-marker-alt"></i> San José, Costa Rica</p>
                    <p><i class="fas fa-phone"></i> +506 1234-5678</p>
                    <p><i class="fas fa-envelope"></i> info@2worktransport.cr</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p>&copy; 2024 2Work Transport Services. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>