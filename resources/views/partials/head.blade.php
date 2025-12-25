<head>

    <link rel="icon" type="image/png" href="/logotsel.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- CSRF Token for AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Click Tracker Script --}}
    <script src="{{ asset('js/click-tracker.js') }}" defer></script>
    
    <style>
        /* Font optimization for Poppins */
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;    
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'kern' 1;
            letter-spacing: -0.01em;
        }
    </style>

    
</head>