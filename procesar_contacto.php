<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
    $empresa = filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING);
    $asunto = filter_input(INPUT_POST, 'asunto', FILTER_SANITIZE_STRING);
    $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);

    if (empty($nombre) || empty($apellidos) || empty($email) || empty($telefono) || empty($asunto) || empty($mensaje)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email inválido.']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tu_email@gmail.com';
        $mail->Password   = 'tu_contraseña_de_aplicacion';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('noreply@2worktransport.cr', '2Work Transport Services');
        $mail->addAddress('info@2worktransport.cr', '2Work Transport');
        $mail->addReplyTo($email, "$nombre $apellidos");

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Contacto Web: $asunto";
        
        $stars = str_repeat('⭐', $rating ? $rating : 0);
        
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #1e3a8a, #f97316); color: white; padding: 20px; text-align: center; }
                .content { background: #f9fafb; padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #1e3a8a; }
                .value { margin-left: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Nuevo Mensaje de Contacto</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='label'>Nombre:</span>
                        <span class='value'>$nombre $apellidos</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>$email</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Teléfono:</span>
                        <span class='value'>$telefono</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Empresa:</span>
                        <span class='value'>" . ($empresa ?: 'No especificada') . "</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Asunto:</span>
                        <span class='value'>$asunto</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Mensaje:</span>
                        <div style='margin-top: 10px; padding: 10px; background: white; border-left: 3px solid #f97316;'>
                            " . nl2br($mensaje) . "
                        </div>
                    </div>
                    <div class='field'>
                        <span class='label'>Calificación del sitio:</span>
                        <span class='value'>$stars</span>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";

        $mail->AltBody = "Nombre: $nombre $apellidos\nEmail: $email\nTeléfono: $telefono\nEmpresa: $empresa\nAsunto: $asunto\n\nMensaje:\n$mensaje\n\nCalificación: $rating estrellas";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Mensaje enviado correctamente.']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Error al enviar el mensaje: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
