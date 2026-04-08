<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
//**aqui adelante */
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
            background: linear-gradient(135deg, #0f2b44 0%, #1a4a6e 50%, #0b2b3f 100%);
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
            opacity: 0.08;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.03) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Contenedor principal cuadrado centrado */
        .main-card {
            max-width: 1100px;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.4);
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

        .logo-circle {
            width: 48px;
            height: 48px;
            background: linear-gradient(145deg, #1e3a5f, #0f2b44);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px -6px rgba(15, 43, 68, 0.2);
        }

        .logo-circle i {
            font-size: 1.6rem;
            color: white;
        }

        .brand h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0b2b3f, #1c4e6f);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .tagline {
            font-size: 0.75rem;
            font-weight: 500;
            color: #5e7a93;
            letter-spacing: 0.5px;
        }

        /* Título formulario */
        .form-title {
            margin-bottom: 1.8rem;
        }

        .form-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.4rem;
        }

        .form-title p {
            color: #64748b;
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
            color: #1e3a5f;
            margin-bottom: 0.5rem;
        }

        .input-label i {
            font-size: 0.75rem;
            color: #4a7c9c;
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
            color: #94a3b8;
            pointer-events: none;
        }

        input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.6rem;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.8rem;
            background: white;
            transition: all 0.25s;
            outline: none;
            color: #0f172a;
        }

        input:focus {
            border-color: #2c6e9e;
            box-shadow: 0 0 0 3px rgba(44, 110, 158, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 0.8rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            color: #94a3b8;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #2c6e9e;
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
            color: #475569;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .remember input {
            width: 0.9rem;
            height: 0.9rem;
            accent-color: #2c6e9e;
        }

        .forgot-link {
            color: #2c6e9e;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #154a6e;
            text-decoration: underline;
        }

        /* Botón */
        .btn-login {
            width: 100%;
            background: linear-gradient(105deg, #1e3a5f, #1c4e6f);
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
            box-shadow: 0 4px 12px -4px rgba(28, 78, 111, 0.3);
        }

        .btn-login i {
            font-size: 0.9rem;
        }

        .btn-login:hover {
            background: linear-gradient(105deg, #143450, #163f59);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -6px rgba(28, 78, 111, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Links extra */
        .extra-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.2rem;
            border-top: 1px solid #e2e8f0;
        }

        .register-link {
            color: #2c6e9e;
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
            color: #0f4468;
        }

        .staff-link {
            display: inline-block;
            margin-top: 0.6rem;
            font-size: 0.7rem;
            color: #7f9ab3;
            text-decoration: none;
        }

        .staff-link i {
            margin-right: 0.3rem;
        }

        .staff-link:hover {
            color: #2c6e9e;
        }

        /* Mensajes */
        .message {
            margin-top: 1rem;
            padding: 0.7rem;
            border-radius: 0.8rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
            display: none;
        }

        .message.error {
            background: #fef2f2;
            color: #dc2626;
            border-left: 3px solid #dc2626;
            display: block;
        }

        .message.success {
            background: #f0fdf4;
            color: #16a34a;
            border-left: 3px solid #16a34a;
            display: block;
        }

        /* Columna derecha - Imagen */
        .login-right {
            flex: 1;
            position: relative;
            background: #0f2b44;
            overflow: hidden;
        }

        .login-right img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .main-card:hover .login-right img {
            transform: scale(1.03);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 43, 68, 0.2) 0%, rgba(28, 78, 111, 0.15) 100%);
            pointer-events: none;
        }

        .image-caption {
            position: absolute;
            bottom: 1.5rem;
            left: 0;
            right: 0;
            text-align: center;
            color: white;
            z-index: 2;
            padding: 0.8rem;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.3), transparent);
        }

        .image-caption p {
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .image-caption i {
            margin: 0 0.3rem;
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
                min-height: 240px;
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
        }
    </style>
</head>
<body>
    <div class="main-card">
        <div class="two-columns">
            <!-- Columna Izquierda: Formulario -->
            <div class="login-left">
                <div class="login-container">
                    <div class="brand">
                        <div class="logo-wrapper">
                            <div class="logo-circle">
                                <i class="fas fa-tooth"></i>
                            </div>
                            <div>
                                <h1>Radiance</h1>
                                <div class="tagline">Where your smile shines</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-title">
                        <h2>Bienvenido de vuelta</h2>
                        <p>Ingresa a tu cuenta para gestionar tus citas</p>
                    </div>

                    <form id="loginForm">
                        <div class="form-group">
                            <div class="input-label">
                                <i class="fas fa-envelope"></i>
                                <span>Correo electrónico</span>
                            </div>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="email" id="email" placeholder="nombre@radiance.com" autocomplete="email">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-label">
                                <i class="fas fa-lock"></i>
                                <span>Contraseña</span>
                            </div>
                            <div class="input-wrapper">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password" id="password" placeholder="Ingresa tu contraseña" autocomplete="current-password">
                                <button type="button" class="toggle-password" id="togglePassword" aria-label="Mostrar contraseña">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="options">
                            <label class="remember">
                                <input type="checkbox" id="rememberCheckbox"> Recordar sesión
                            </label>
                            <a href="#" class="forgot-link" id="forgotPassword">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            Iniciar sesión
                            <i class="fas fa-sparkles"></i>
                        </button>

                        <div id="messageBox" class="message"></div>
                    </form>

                    <div class="extra-links">
                        <a href="#" class="register-link" id="registerLink">
                            <i class="fas fa-user-plus"></i> Crear cuenta nueva
                        </a><br>
                        <a href="#" class="staff-link" id="staffLink">
                            <i class="fas fa-id-card"></i> Acceso para personal / administradores
                        </a>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Imagen -->
            <div class="login-right">
                <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?q=80&w=2070&auto=format" alt="Radiance Dental Clinic - Sonrisa saludable" loading="lazy">
                <div class="image-overlay"></div>
                <div class="image-caption">
                    <p><i class="fas fa-star-of-life"></i> Excelencia en cuidado dental <i class="fas fa-star-of-life"></i></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const rememberCheckbox = document.getElementById('rememberCheckbox');
            const messageBox = document.getElementById('messageBox');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const forgotLink = document.getElementById('forgotPassword');
            const registerLink = document.getElementById('registerLink');
            const staffLink = document.getElementById('staffLink');

            const validUsers = [
                { email: "paciente@radiance.com", password: "sonrisa2025", role: "paciente", name: "Laura Méndez" },
                { email: "admin@radiance.com", password: "adminradiance", role: "admin", name: "Administrador" },
                { email: "dra.garza@radiance.com", password: "smilepro", role: "doctor", name: "Dra. Garza" },
                { email: "bienestar@radiance.com", password: "brightsmile", role: "paciente", name: "Carlos Ruiz" }
            ];

            function showMessage(text, type = 'error') {
                messageBox.textContent = text;
                messageBox.className = `message ${type}`;
                messageBox.style.display = 'block';
                setTimeout(() => {
                    if (messageBox.style.display === 'block') {
                        messageBox.style.display = 'none';
                        messageBox.className = 'message';
                        messageBox.textContent = '';
                    }
                }, 4500);
            }

            function saveSession(email, password, remember) {
                if (remember) {
                    localStorage.setItem('radiance_email', email);
                    localStorage.setItem('radiance_password', password);
                    localStorage.setItem('radiance_remember', 'true');
                } else {
                    localStorage.removeItem('radiance_email');
                    localStorage.removeItem('radiance_password');
                    localStorage.setItem('radiance_remember', 'false');
                }
            }

            function loadSavedCredentials() {
                const savedEmail = localStorage.getItem('radiance_email');
                const savedPassword = localStorage.getItem('radiance_password');
                const savedRemember = localStorage.getItem('radiance_remember') === 'true';
                
                if (savedRemember && savedEmail && savedPassword) {
                    emailInput.value = savedEmail;
                    passwordInput.value = savedPassword;
                    rememberCheckbox.checked = true;
                } else {
                    rememberCheckbox.checked = false;
                }
            }

            function validateCredentials(email, password) {
                const normalizedEmail = email.trim().toLowerCase();
                const normalizedPassword = password.trim();
                const user = validUsers.find(u => u.email === normalizedEmail && u.password === normalizedPassword);
                
                if (user) {
                    return { success: true, role: user.role, email: normalizedEmail, name: user.name };
                }
                return { success: false, message: "Credenciales incorrectas. Verifica tu correo y contraseña." };
            }

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = emailInput.value;
                const password = passwordInput.value;
                const remember = rememberCheckbox.checked;

                if (!email || !password) {
                    showMessage("Por favor completa todos los campos.", "error");
                    return;
                }

                if (!email.includes('@') || !email.includes('.')) {
                    showMessage("Ingresa un correo electrónico válido.", "error");
                    return;
                }

                if (password.length < 4) {
                    showMessage("La contraseña debe tener al menos 4 caracteres.", "error");
                    return;
                }

                const result = validateCredentials(email, password);
                
                if (result.success) {
                    saveSession(email, password, remember);
                    let roleText = "";
                    if (result.role === "admin") roleText = "Administrador";
                    else if (result.role === "doctor") roleText = "Especialista";
                    else roleText = "Paciente";
                    
                    showMessage(`Bienvenido(a) ${result.name} · Acceso ${roleText}. Redirigiendo al panel...`, "success");
                    
                    setTimeout(() => {
                        alert(`✨ Sesión iniciada exitosamente como ${roleText}. ✨\nBienvenido a Radiance Clínica Dental.`);
                    }, 1700);
                } else {
                    showMessage(result.message || "Error de autenticación. Intenta de nuevo.", "error");
                    passwordInput.value = '';
                }
            });

            togglePasswordBtn.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                const icon = togglePasswordBtn.querySelector('i');
                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });

            forgotLink.addEventListener('click', (e) => {
                e.preventDefault();
                showMessage("Hemos enviado un enlace de recuperación a tu correo registrado.", "success");
            });

            registerLink.addEventListener('click', (e) => {
                e.preventDefault();
                showMessage("Próximamente: Regístrate y agenda tu primera cita en Radiance.", "success");
            });

            staffLink.addEventListener('click', (e) => {
                e.preventDefault();
                emailInput.value = "admin@radiance.com";
                passwordInput.value = "adminradiance";
                rememberCheckbox.checked = true;
                showMessage("Acceso rápido para personal. Credenciales cargadas.", "success");
            });

            loadSavedCredentials();

            emailInput.addEventListener('focus', () => {
                if (messageBox.style.display === 'block') {
                    messageBox.style.display = 'none';
                    messageBox.className = 'message';
                }
            });
            passwordInput.addEventListener('focus', () => {
                if (messageBox.style.display === 'block') {
                    messageBox.style.display = 'none';
                    messageBox.className = 'message';
                }
            });
        })();
    </script>
</body>
</html>