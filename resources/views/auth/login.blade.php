<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Radiance | Clínica Dental - Iniciar Sesión</title>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            min-height: 100vh;
            /* Fondo negro elegante en lugar de azules */
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0d0d0d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Decoración de fondo sutil */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1606811971618-4486d14f3f99?q=80&w=1974&auto=format');
            background-size: cover;
            background-position: center;
            opacity: 0.04;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.02) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Contenedor principal cuadrado centrado */
        .main-card {
            max-width: 1100px;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.6);
        }

        /* Contenido de dos columnas dentro del cuadrado */
        .two-columns {
            display: flex;
            min-height: 580px;
        }

        /* Columna izquierda - Formulario */
        .login-left {
            flex: 1;
            padding: 2.5rem;
            display: flex;
            align-items: center;
            background: white;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Marca */
        .brand {
            margin-bottom: 2rem;
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        /* Estilo para la imagen del logo */
        .logo-img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 12px;
        }

        .brand h1 {
            font-size: 1.8rem;
            font-weight: 700;
            /* Gradiente negro/gris elegante */
            background: linear-gradient(135deg, #1a1a1a, #333333);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .tagline {
            font-size: 0.75rem;
            font-weight: 500;
            color: #666666;
            letter-spacing: 0.5px;
        }

        /* Título formulario */
        .form-title {
            margin-bottom: 1.8rem;
        }

        .form-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111111;
            margin-bottom: 0.4rem;
        }

        .form-title p {
            color: #666666;
            font-size: 0.85rem;
        }

        /* Campos */
        .form-group {
            margin-bottom: 1.3rem;
        }

        .input-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #333333;
            margin-bottom: 0.5rem;
        }

        .input-label i {
            font-size: 0.75rem;
            color: #555555;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            font-size: 0.9rem;
            color: #999999;
            pointer-events: none;
        }

        input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.6rem;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            border: 1.5px solid #e0e0e0;
            border-radius: 0.8rem;
            background: white;
            transition: all 0.25s;
            outline: none;
            color: #111111;
        }

        input:focus {
            border-color: #333333;
            box-shadow: 0 0 0 3px rgba(51, 51, 51, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 0.8rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            color: #999999;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #333333;
        }

        /* Opciones */
        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.2rem 0 1.5rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            color: #555555;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .remember input {
            width: 0.9rem;
            height: 0.9rem;
            accent-color: #333333;
        }

        .forgot-link {
            color: #555555;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #111111;
            text-decoration: underline;
        }

        /* Botón */
        .btn-login {
            width: 100%;
            background: linear-gradient(105deg, #1a1a1a, #2d2d2d);
            color: white;
            border: none;
            padding: 0.8rem;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border-radius: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 4px 12px -4px rgba(0, 0, 0, 0.3);
        }

        .btn-login i {
            font-size: 0.9rem;
        }

        .btn-login:hover {
            background: linear-gradient(105deg, #000000, #1a1a1a);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Links extra */
        .extra-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.2rem;
            border-top: 1px solid #e0e0e0;
        }

        .register-link {
            color: #555555;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .register-link i {
            margin-right: 0.3rem;
            font-size: 0.8rem;
        }

        .register-link:hover {
            color: #111111;
        }

        /* Columna derecha - Imagen centrada y completa */
        .login-right {
            flex: 1;
            position: relative;
            /* Fondo negro en lugar de azul */
            background: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .image-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .centered-logo {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            transition: transform 0.4s ease;
        }

        .main-card:hover .centered-logo {
            transform: scale(1.02);
        }

        /* Responsive */
        @media (max-width: 850px) {
            body {
                padding: 1rem;
            }
            
            .two-columns {
                flex-direction: column;
            }
            
            .login-right {
                min-height: 100%;
                order: -1;
            }
            
            .login-left {
                padding: 2rem;
            }
            
            .login-container {
                max-width: 100%;
            }
            
            .brand, .form-title {
                text-align: center;
            }
            
            .logo-wrapper {
                justify-content: center;
            }
            
            .image-container {
                padding: 1.5rem;
            }
            
            .centered-logo {
                max-width: 100%;
                max-height: 100%;
            }
        }

        @media (max-width: 480px) {
            .login-left {
                padding: 1.5rem;
            }
            
            .brand h1 {
                font-size: 1.5rem;
            }
            
            .form-title h2 {
                font-size: 1.3rem;
            }
            
            .image-container {
                padding: 1rem;
            }
            
            .centered-logo {
                max-width: 60%;
                max-height: 60%;
            }
        }
    </style>
</head>
<body>
    <div class="main-card">
        <div class="two-columns">
            <!-- Columna Izquierda: Formulario -->
            <div class="login-left">
                <div class="login-container">

                    <div class="form-title">
                        <h2>Bienvenido de vuelta</h2>
                        <p>Ingresa a tu cuenta para gestionar tus citas</p>
                    </div>

                    <!-- MENSAJE DE SESIÓN -->
                    @if (session('status'))
                        <div class="message success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <div class="input-label">
                                <i class="fas fa-envelope"></i>
                                <span>Correo electrónico</span>
                            </div>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nombre@radiance.com" 
                                    autocomplete="email"
                                    required
                                >
                            </div>

                            @error('email')
                                <div class="message error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="input-label">
                                <i class="fas fa-lock"></i>
                                <span>Contraseña</span>
                            </div>
                            <div class="input-wrapper">
                                <i class="fas fa-key input-icon"></i>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password"
                                    placeholder="Ingresa tu contraseña" 
                                    autocomplete="current-password"
                                    required
                                >
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <div class="message error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="options">
                            <label class="remember">
                                <input type="checkbox" name="remember"> Recordar sesión
                            </label>

                            <a href="{{ route('password.request') }}" class="forgot-link">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            Iniciar sesión
                            <i class="fas fa-sparkles"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Columna Derecha: Imagen centrada -->
            <div class="login-right">
                <div class="image-container">
                    <img src="{{ asset('imagen/LogoFinal.png') }}" alt="Radiance Dental Clinic" class="centered-logo">
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar/ocultar contraseña
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePasswordBtn.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            const icon = togglePasswordBtn.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>