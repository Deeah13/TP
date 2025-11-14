<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Terminal Pintar</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                color-scheme: light;
            }

            *,
            *::before,
            *::after {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                background: linear-gradient(180deg, #fef7ff 0%, #ffffff 60%, #f4fbf3 100%);
                font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            }

            #app {
                min-height: 100vh;
            }

            noscript {
                align-items: center;
                color: #2a2a2a;
                display: flex;
                font-size: 1rem;
                justify-content: center;
                min-height: 100vh;
                padding: 1.5rem;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <noscript>
                Pengalaman ini memerlukan JavaScript agar landing page interaktif Terminal Pintar dapat tampil dengan sempurna.
            </noscript>
        </div>
    </body>
</html>
