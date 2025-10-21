@extends('layouts.app')

@section('title', 'Términos y Condiciones')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Términos y Condiciones de Uso</h1>
    <p><strong>Última actualización:</strong> {{ now()->format('d/m/Y') }}</p>

    <h3>1. Objeto</h3>
    <p>
        PROSERVI es una plataforma digital que facilita la conexión entre prestadores de servicios y usuarios.
        PROSERVI no es parte de la relación contractual ni garantiza la calidad de los servicios ofrecidos;
        únicamente actúa como intermediario tecnológico.
    </p>

    <h3>2. Registro y uso de la plataforma</h3>
    <ul>
        <li>Los usuarios deben registrarse con datos reales y actualizados.</li>
        <li>Los prestadores deben proporcionar información veraz sobre sus servicios.</li>
        <li>PROSERVI puede requerir verificación de identidad (documento y fotografía).</li>
    </ul>

    <h3>3. Responsabilidad de los prestadores</h3>
    <p>
        Los prestadores son los únicos responsables por la calidad, legalidad, cumplimiento y ejecución de los
        servicios que ofrezcan. PROSERVI no se hace responsable de daños o perjuicios ocasionados por dichos servicios.
    </p>

    <h3>4. Restricciones de uso</h3>
    <p>Queda prohibido:</p>
    <ul>
        <li>Publicar servicios ilegales, ofensivos, engañosos o fraudulentos.</li>
        <li>Usar la plataforma con fines distintos a la contratación u oferta de servicios lícitos.</li>
        <li>Crear cuentas falsas o múltiples sin autorización.</li>
    </ul>
    <p>El incumplimiento podrá resultar en la suspensión o eliminación de la cuenta.</p>

    <h3>5. Contenido y propiedad intelectual</h3>
    <p>
        Los contenidos (textos, imágenes, descripciones) publicados son propiedad de sus autores.
        El usuario concede a PROSERVI una licencia no exclusiva y limitada para mostrar dicho contenido en la plataforma.
    </p>

    <h3>6. Responsabilidad de PROSERVI</h3>
    <p>
        PROSERVI no garantiza disponibilidad permanente del servicio y no es responsable por pérdidas económicas,
        daños indirectos o disputas entre usuarios y prestadores.
    </p>

    <h3>7. Modificaciones</h3>
    <p>
        PROSERVI podrá modificar estos términos en cualquier momento. Los cambios serán notificados
        en la plataforma.
    </p>

    <h3>8. Legislación y jurisdicción</h3>
    <p>
        Estos Términos se rigen por las leyes de Bolivia. Cualquier disputa será resuelta en
        los tribunales competentes de Cochabamba.
    </p>
</div>
@endsection
