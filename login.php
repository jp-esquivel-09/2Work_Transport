<?php
// ESTO DEBE IR AL PRINCIPIO, ANTES DE CUALQUIER HTML
error_reporting(E_ALL);
ini_set('display_errors', 1);

// PROCESAR LOGIN EN EL MISMO ARCHIVO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "conexion.php";
    
    // Obtener datos del formulario
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validaciones
    if (empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } else {
        // Buscar usuario en la base de datos
        $sql = "SELECT cedula, nombre, primer_apellido, email, password FROM usuario WHERE email = '$email' AND activo = 1";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verificar contraseña usando password_verify()
            if (password_verify($password, $user['password'])) {
                // Iniciar sesión - AHORA AL PRINCIPIO
                session_start();
                $_SESSION['user_id'] = $user['cedula'];
                $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['primer_apellido'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                
                // Redirigir al dashboard - AHORA AL PRINCIPIO
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Email o contraseña incorrectos";
            }
        } else {
            $error = "Email o contraseña incorrectos";
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - 2Work Transport Services</title>
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

        .login-form-container {
            background: white;
            border-radius: 10px;
            padding: 3rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 500px;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 2rem;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
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

        .login-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-links a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .login-links a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
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
                        <a class="nav-link active" href="login.php">Iniciar sesión</a>
                    </li>
                    <li>
                        <a class="nav-link" href="registro.php">Registrarse</a>
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
            <h1 class="display-4">Iniciar Sesión</h1>
            <p class="lead">Acceda a su cuenta para gestionar sus servicios</p>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="login-form-container">
                        <h3 class="form-title">Ingrese a su Cuenta</h3>

                        <!-- Mostrar mensajes de error -->
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Mostrar mensaje de éxito si viene del registro -->
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo $_POST['email'] ?? ''; ?>" placeholder="su@email.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Ingrese su contraseña" required>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordar mi sesión
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </button>
                        </form>

                        <div class="login-links">
                            <p>¿No tiene una cuenta? <a href="registro.php">Regístrese aquí</a></p>
                            <p><a href="#">¿Olvidó su contraseña?</a></p>
                        </div>
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
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>