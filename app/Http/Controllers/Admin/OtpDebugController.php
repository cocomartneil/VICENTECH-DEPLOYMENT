<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class OtpDebugController extends Controller
{
    /**
     * Return recent users with OTPs for debugging verification.
     * Protected by ADMIN_OTP_KEY header. Remove after SMTP is fixed.
     */
    public function index(Request $request)
    {
        $key = $request->header('X-ADMIN-OTP-KEY') ?? '';

        // Allow only when the key matches env or when APP_DEBUG is true
        if (config('app.debug') !== true && $key !== env('ADMIN_OTP_KEY')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $count = (int) $request->query('count', 20);

        $users = User::select('id', 'name', 'email', 'phone', 'otp', 'otp_expires_at', 'created_at')
            ->whereNotNull('otp')
            ->orderBy('created_at', 'desc')
            ->take($count)
            ->get();

        return response()->json(['data' => $users]);
    }
}
