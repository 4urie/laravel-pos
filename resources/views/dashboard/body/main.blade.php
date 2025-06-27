<!doctype html>
<html lang="en" data-theme="default">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>POS Dash</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}"/>
        <link rel="stylesheet" href="{{ asset('assets/css/backend-plugin.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/backend.css?v=1.0.0') }}">

        <link rel="stylesheet" href="{{ asset('assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/fonts/remixicon.css') }}">
        
        <!-- Theme stylesheets - will be dynamically loaded -->
        <link rel="stylesheet" href="{{ asset('assets/css/custom-theme.css') }}" id="theme-style" disabled>
        <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" id="dark-style" disabled>
        <link rel="stylesheet" href="{{ asset('assets/css/vibrant-theme.css') }}" id="vibrant-style" disabled>

        @yield('specificpagestyles')
        
        <style>
            .theme-switcher {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 999;
            }
            
            .theme-switcher .dropdown-menu {
                min-width: 200px;
                padding: 15px;
            }
            
            .theme-option {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
                cursor: pointer;
            }
            
            .color-preview {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                margin-right: 10px;
            }
            
            .default-preview {
                background: #4e73df;
            }
            
            .dark-preview {
                background: #1e293b;
            }
            
            .vibrant-preview {
                background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            }
        </style>
    </head>
<body>
    <!-- loader Start -->
    {{-- <div id="loading">
        <div id="loading-center"></div>
    </div> --}}
    <!-- loader END -->

    <!-- Wrapper Start -->
    <div class="wrapper">
        @include('dashboard.body.sidebar')

        @include('dashboard.body.navbar')

        <div class="content-page">
            @yield('container')
        </div>
    </div>
    <!-- Wrapper End-->

    @include('dashboard.body.footer')
    
    <!-- Theme Switcher -->
    <div class="theme-switcher">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="themeSwitcherBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ri-palette-line"></i> Theme
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="themeSwitcherBtn">
                <div class="theme-option" data-theme="default">
                    <div class="color-preview default-preview"></div>
                    <span>Default Theme</span>
                </div>
                <div class="theme-option" data-theme="dark">
                    <div class="color-preview dark-preview"></div>
                    <span>Dark Theme</span>
                </div>
                <div class="theme-option" data-theme="vibrant">
                    <div class="color-preview vibrant-preview"></div>
                    <span>Vibrant Theme</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/js/backend-bundle.min.js') }}"></script>
    <script src="https://kit.fontawesome.com/4c897dc313.js" crossorigin="anonymous"></script>

    @yield('specificpagescripts')
    
    <!-- Custom scripts for specific pages -->
    @yield('scripts')

    <!-- App JavaScript -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    <!-- Theme Switcher Script -->
    <script>
        $(document).ready(function() {
            // Check if a theme is saved in localStorage
            const savedTheme = localStorage.getItem('posTheme') || 'default';
            setTheme(savedTheme);
            
            // Theme switcher functionality
            $('.theme-option').on('click', function() {
                const theme = $(this).data('theme');
                setTheme(theme);
                localStorage.setItem('posTheme', theme);
            });
            
            function setTheme(theme) {
                // First disable all theme stylesheets
                $('#theme-style, #dark-style, #vibrant-style').prop('disabled', true);
                
                // Update the data-theme attribute on html element
                $('html').attr('data-theme', theme);
                
                // Enable the selected theme
                if (theme === 'default') {
                    // No need to enable anything, using the base styles
                } else if (theme === 'dark') {
                    $('#dark-style').prop('disabled', false);
                } else if (theme === 'vibrant') {
                    $('#vibrant-style').prop('disabled', false);
                } else {
                    $('#theme-style').prop('disabled', false);
                }
            }
        });
    </script>
</body>
</html>
