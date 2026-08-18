@extends('admin.layouts.admin')

@section('title', 'Admin Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Profile Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <div class="p-3 bg-gray-50 rounded-md border border-gray-200 text-gray-800">
                    {{ $user->name }}
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="p-3 bg-gray-50 rounded-md border border-gray-200 text-gray-800">
                    {{ $user->email }}
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <div class="p-3 bg-gray-50 rounded-md border border-gray-200 text-gray-800 uppercase">
                    {{ $user->role }}
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Change Password</h2>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('code_sent'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('code_sent') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Step 1: Request Verification Code -->
        @if (!session('code_sent') && !$errors->has('verification_code'))
            <div class="mb-6">
                <p class="text-gray-600 mb-4">To ensure account security, a verification code will be sent to your email <strong>{{ $user->email }}</strong> before you can change your password.</p>
                
                <form action="{{ route('admin.profile.password.send_code') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md transition duration-200">
                        <i class="fas fa-paper-plane mr-2"></i> Send Verification Code
                    </button>
                </form>
            </div>
        @endif

        <!-- Step 2: Update Password Form (Shown after code is sent) -->
        @if (session('code_sent') || $errors->has('verification_code') || $errors->has('password') || $errors->has('current_password'))
            <form action="{{ route('admin.profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-yellow-700"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Please check your email for the verification code.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
                        <input type="text" name="verification_code" id="verification_code" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter 6-digit code" required>
                    </div>

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md transition duration-200">
                            Update Password
                        </button>
                        
                        <form action="{{ route('admin.profile.password.send_code') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Resend Code
                            </button>
                        </form>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
