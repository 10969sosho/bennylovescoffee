<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bennylovescoffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .admin-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .admin-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="admin-login-bg min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Admin Login Card -->
        <div class="admin-card p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full mb-4">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-gray-600 mt-2">Bennylovescoffee Management System</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                           placeholder="admin@bennylovescoffee.com"
                           required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                           placeholder="Enter your password"
                           required>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full admin-btn text-white py-3 px-4 rounded-lg font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Admin Panel
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    This is a secure admin area. Only authorized personnel can access.
                </p>
            </div>
        </div>

        <!-- Back to Store Link -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" 
               class="text-white hover:text-gray-200 transition flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Bennylovescoffee Store
            </a>
        </div>
    </div>
</body>
</html>
