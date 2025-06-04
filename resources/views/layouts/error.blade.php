<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IntraGest') }} - Error @yield('error_code')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .error-page {
            background: linear-gradient(135deg, #f6f8fc 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .error-container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            line-height: 1;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: #1e293b;
            margin: 1rem 0;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards 0.2s;
        }

        .error-message {
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards 0.4s;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards 0.6s;
        }

        .error-button {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .error-button-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1), 0 2px 4px -1px rgba(37, 99, 235, 0.06);
        }

        .error-button-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(37, 99, 235, 0.15), 0 3px 6px -1px rgba(37, 99, 235, 0.1);
        }

        .error-button-secondary {
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .error-button-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.08), 0 3px 6px -1px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .error-button svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
        }

        .error-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .error-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            animation: float 6s ease-in-out infinite;
        }

        .error-shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .error-shape-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation-delay: 2s;
        }

        .error-shape-3 {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .error-page {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            }

            .error-title {
                color: #f8fafc;
            }

            .error-message {
                color: #94a3b8;
            }

            .error-button-secondary {
                background: #1e293b;
                color: #f8fafc;
                border-color: #334155;
            }

            .error-button-secondary:hover {
                border-color: #475569;
            }

            .error-shape {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.15) 100%);
            }
        }

        /* Fix button size after click (ripple/active/focus) */
        .error-button .ripple {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: ripple-effect 0.6s linear;
            background: rgba(59, 130, 246, 0.2);
            pointer-events: none;
        }
        @keyframes ripple-effect {
            to {
                transform: scale(2.5);
                opacity: 0;
            }
        }
        .error-button:active, .error-button:focus {
            box-shadow: none !important;
            outline: none !important;
            /* Prevent size change */
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="error-page">
        <!-- App Logo and Name in Top Left -->
        <div style="position: absolute; top: 2rem; left: 2rem; z-index: 10; display: flex; align-items: center;">
            <img src="/storage/logo/intragest-logo.png" alt="IntraGest Logo" style="width: 48px; height: 48px; border-radius: 50%; background: transparent; object-fit: contain; display: block;" />
            <span style="font-size: 2rem; text-shadow: 0 6px black; font-weight: 800; margin-left: 1rem; color: #3276F2; letter-spacing: 0.01em; font-family: 'Inter', sans-serif;">IntraGest</span>
        </div>
        <!-- App Logo and Name in Top Right -->
        <div style="position: absolute; top: 2rem; right: 2rem; z-index: 10; display: flex; align-items: center;">
            <span style="font-size: 2rem; text-shadow: 0 6px black; font-weight: 800; margin-right: 1rem; color:rgb(255, 255, 255); letter-spacing: 0.01em; font-family: 'Inter', sans-serif;">OFPPT</span>
            <img src="/storage/logo/ofppt-logo.png" alt="OFPPT Logo" style="width: 48px; height: 48px; border-radius: 50%; background: transparent; object-fit: contain; display: block;" />
        </div>
        <div class="error-shapes">
            <div class="error-shape error-shape-1"></div>
            <div class="error-shape error-shape-2"></div>
            <div class="error-shape error-shape-3"></div>
        </div>
        
        <div class="error-container">
            <h1 class="error-code">@yield('error_code')</h1>
            <h2 class="error-title">@yield('error_title')</h2>
            <p class="error-message">@yield('error_message')</p>
            
            <div class="error-actions">
                <a href="{{ route('dashboard') }}" class="error-button error-button-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Back to Dashboard
                </a>
                <a href="javascript:history.back()" class="error-button error-button-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </a>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', () => {
            const shapes = document.querySelectorAll('.error-shape');
            
            // Add mouse move parallax effect
            document.addEventListener('mousemove', (e) => {
                const { clientX, clientY } = e;
                const centerX = window.innerWidth / 2;
                const centerY = window.innerHeight / 2;
                
                shapes.forEach((shape, index) => {
                    const speed = (index + 1) * 0.02;
                    const x = (clientX - centerX) * speed;
                    const y = (clientY - centerY) * speed;
                    
                    shape.style.transform = `translate(${x}px, ${y}px)`;
                });
            });

            // Add click effect on buttons
            const buttons = document.querySelectorAll('.error-button');
            buttons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const ripple = document.createElement('div');
                    ripple.classList.add('ripple');
                    button.appendChild(ripple);
                    
                    const rect = button.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = `${size}px`;
                    ripple.style.left = `${x}px`;
                    ripple.style.top = `${y}px`;
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });
        });
    </script>
</body>
</html> 