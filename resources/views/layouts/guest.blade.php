<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Wedding Invitation' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS - Animate On Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #F8F3D9;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Cormorant Garamond', serif;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #EBE5C2;
        }

        ::-webkit-scrollbar-thumb {
            background: #B9B28A;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9a947a;
        }

        /* Modern Hover Effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(185, 178, 138, 0.2);
        }

        /* Custom Animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease forwards;
        }
    </style>
    
    @livewireStyles
</head>
<body>
    {{ $slot }}
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                easing: 'ease-out',
                once: true,
                offset: 100
            });
        });
    </script>
    
    @livewireScripts
</body>
</html> 