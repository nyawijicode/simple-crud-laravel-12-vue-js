<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel + Vue CRUD</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --secondary: #0ea5e9;
            --dark: #1e293b;
            --light: #f8fafc;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-900);
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.15);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .card-content {
            padding: 2.5rem;
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 1.25rem;
            color: var(--gray-700);
            margin-bottom: 2rem;
        }

        .status {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0.75rem 1.25rem;
            background-color: var(--primary-light);
            border-radius: 0.5rem;
            color: var(--primary);
            font-weight: 500;
        }

        .status i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .api-info {
            background-color: var(--gray-100);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .api-info h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .api-endpoint {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background-color: white;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--gray-200);
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .api-endpoint .method {
            font-weight: bold;
            margin-right: 1rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .api-endpoint .get {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .api-endpoint .post {
            background-color: #d1fae5;
            color: var(--success);
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary-light);
        }

        .footer {
            margin-top: 2rem;
            text-align: center;
            color: var(--gray-700);
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .card-content {
                padding: 1.5rem;
            }
            
            .title {
                font-size: 2rem;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Simple animation for page load */
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

        .card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <h1 class="title">Laravel + Vue CRUD</h1>
                <p class="subtitle">RESTful API for modern web applications</p>
                
                <div class="status">
                    <i class="fas fa-check-circle"></i>
                    <span>API Server is running successfully</span>
                </div>
                
                <div class="api-info">
                    <h3>Available API Endpoints</h3>
                    <div class="api-endpoint">
                        <span class="method get">GET</span>
                        <span>/api/test</span>
                    </div>
                    <div class="api-endpoint">
                        <span class="method post">POST</span>
                        <span>/api/login</span>
                    </div>
                    <div class="api-endpoint">
                        <span class="method post">POST</span>
                        <span>/api/register</span>
                    </div>
                    <div class="api-endpoint">
                        <span class="method get">GET</span>
                        <span>/api/users</span>
                    </div>
                </div>
                
                <div class="buttons">
                    <a href="http://localhost:5173" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> Open Frontend
                    </a>
                    <a href="/api/test" target="_blank" class="btn btn-outline">
                        <i class="fas fa-code"></i> Test API
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Made with <i class="fas fa-heart" style="color: var(--danger);"></i> Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
        </div>
    </div>
</body>
</html>