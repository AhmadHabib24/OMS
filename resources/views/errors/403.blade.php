<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Unauthorized</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-lg w-full bg-white shadow rounded-2xl p-8 text-center">
        <h1 class="text-4xl font-bold text-red-600 mb-3">403</h1>
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Unauthorized Access</h2>
        <p class="text-gray-600 mb-6">
            You do not have permission to access this page.
        </p>

        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            Back to Dashboard
        </a>
    </div>
</body>
</html>