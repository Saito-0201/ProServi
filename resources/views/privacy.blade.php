@extends('layouts.app')

@section('title', 'Política de Privacidad')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Política de Privacidad</h1>
    <p><strong>Última actualización:</strong> {{ now()->format('d/m/Y') }}</p>

    <h3>1. Datos que recopilamos</h3>
    <ul>
        <li>Datos de registro: nombre, correo electrónico, teléfono.</li>
        <li>Datos de prestadores: documento de identidad, foto, descripción de servicios.</li>
        <li>Datos de uso: calificaciones, comentarios y actividad en la plataforma.</li>
        <li>Información técnica: dirección IP y cookies de sesión.</li>
    </ul>

    <h3>2. Finalidad del tratamiento</h3>
    <p>
        Tus datos se utilizan para habilitar el acceso a la plataforma, mostrar perfiles y servicios,
        enviar notificaciones, y generar estadísticas internas.
    </p>

    <h3>3. Protección de los datos</h3>
    <p>
        Los datos sensibles (documentos de identidad) se almacenan cifrados. Aplicamos medidas
        de seguridad para evitar accesos no autorizados.
    </p>

    <h3>4. Derechos del usuario</h3>
    <ul>
        <li>Acceder a tus datos personales.</li>
        <li>Rectificar información incorrecta.</li>
        <li>Solicitar la eliminación de tus datos o cuenta.</li>
        <li>Oponerte al tratamiento de tus datos en casos justificados.</li>
    </ul>
    <p>Para ejercer tus derechos, contáctanos en: <a href="mailto:soporte@proservi.com">soporte@proservi.com</a></p>

    <h3>5. Cookies</h3>
    <p>
        Usamos cookies técnicas para mantener la seguridad y el inicio de sesión.
        También podemos usar cookies de análisis para mejorar el servicio.
        No utilizamos cookies de publicidad invasiva.
    </p>

    <h3>6. Conservación de los datos</h3>
    <p>
        Los datos se conservarán mientras tengas una cuenta activa o hasta que solicites su eliminación.
    </p>

    <h3>7. Modificaciones</h3>
    <p>
        Esta política puede actualizarse en cualquier momento. Los cambios serán publicados en la plataforma.
    </p>

    <h3>8. Legislación aplicable</h3>
    <p>
        Esta política se rige por la legislación vigente en Bolivia sobre protección de datos personales.
    </p>
</div>
@endsection
