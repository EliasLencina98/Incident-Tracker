<!-- Esta pantalla no usa el layout del Panel porque todavía no hay una sesión iniciada (invitado) -->

<!DOCTYPE html> 
<html>

    <head>
        <title>Ingresar | Panel de Control de Accidentes</title>

        <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/gestion.css') }}">
    </head>

    <body class="acceso">
        <main class="gestion acceso-contenedor">
            <div class="panel-tarjeta">
                <h1>Ingresar al Panel</h1>

                @if ($errors->any())
                    <ul class="gestion-errores">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="gestion-campos acceso-campos">
                        <div>
                            <label for="email">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div>
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                    </div>

                    <div class="gestion-formulario-acciones">
                        <button type="submit" class="panel-boton gestion-boton-azul">
                            Iniciar sesión
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </body>

</html>