<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Manual Bootstrap</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <style>
        body {
            background-color: #0f172a; /* Dark background */
            color: #f8f9fa;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .navbar {
            background-color: rgba(15, 23, 42, 0.95) !important;
            border-bottom: 1px solid #1e293b;
        }
        .hero-section {
            padding: 100px 0;
            background: radial-gradient(circle at top right, #1e293b 0%, #0f172a 100%);
        }
        .card {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: white;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            border-color: #3b82f6;
        }
        .text-gradient {
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-primary {
            background-color: #3b82f6;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-outline-light {
            border-color: #334155;
            color: #cbd5e1;
        }
        .btn-outline-light:hover {
            background-color: #1e293b;
            border-color: #cbd5e1;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Laravel<span class="text-primary">Buddy</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                </ul>
                <a href="#" class="btn btn-primary ms-3 rounded-pill btn-sm">Sign Up</a>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary mb-3 px-3 py-2 rounded-pill">
                v2.0 Manual Setup
            </span>
            <h1 class="display-3 fw-bold mb-4">Build Fast with <br> <span class="text-gradient">Bootstrap Local</span></h1>
            <p class="lead text-secondary mb-5 mx-auto" style="max-width: 600px;">
                This project is running completely offline. No CDNs, no Node.js, just standard Laravel and Bootstrap files in your public folder.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="btn btn-primary rounded-3">Get Started</a>
                <a href="#" class="btn btn-outline-light rounded-3">Documentation</a>
            </div>
        </div>
    </header>

    <section class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <div class="card-body">
                        <div class="display-6 text-primary mb-3">
                            <i class="bi bi-hdd-network"></i> ⚡
                        </div>
                        <h4 class="card-title">Offline Ready</h4>
                        <p class="card-text text-secondary">Since the CSS files are in your folder, this works without internet access.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <div class="card-body">
                        <div class="display-6 text-primary mb-3">🎨</div>
                        <h4 class="card-title">Customized</h4>
                        <p class="card-text text-secondary">We added a small custom style block to override default Bootstrap colors.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <div class="card-body">
                        <div class="display-6 text-primary mb-3">🚀</div>
                        <h4 class="card-title">Fast Serve</h4>
                        <p class="card-text text-secondary">Just run <code>php artisan serve</code> and you are good to go.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-4 text-secondary border-top border-secondary mt-5" style="border-color: #1e293b !important;">
        <small>&copy; {{ date('Y') }} Deeptha Ranaweera. Built with Laravel & Bootstrap.</small>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>