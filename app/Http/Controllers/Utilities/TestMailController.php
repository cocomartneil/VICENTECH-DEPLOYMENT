<?php

namespace App\Http\Controllers\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    /**
     * Send a simple test email to the address provided in `to`.
     * Guarded by X-ADMIN-OTP-KEY header or allowed in APP_DEBUG mode.
     */
    public function send(Request $request)
    {
        $key = $request->header('X-ADMIN-OTP-KEY') ?? '';
        if (config('app.debug') !== true && $key !== env('ADMIN_OTP_KEY')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $to = $request->input('to');
        if (empty($to)) {
            return response()->json(['message' => 'Missing `to` address'], 422);
        }

        // Prefer SendGrid HTTP API to avoid SMTP port issues
        $sendgridKey = env('SENDGRID_API_KEY');
        if (!empty($sendgridKey)) {
            try {
                $emailObj = new \SendGrid\Mail\Mail();
                $fromAddress = env('MAIL_FROM_ADDRESS', 'no-reply@example.com');
                $fromName = env('MAIL_FROM_NAME', 'Vicentech');
                $emailObj->setFrom($fromAddress, $fromName);
                $emailObj->setSubject('Vicentech SMTP Test');
                $emailObj->addTo($to);
                $emailObj->addContent('text/plain', 'This is a SendGrid HTTP API test from Vicentech app.');

                $sg = new \SendGrid($sendgridKey);
                $response = $sg->send($emailObj);

                return response()->json([
                    'message' => 'Mail queued/sent (check provider logs)',
                    'status' => $response->statusCode(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to send test mail via SendGrid HTTP API',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        // Fallback to Laravel mailer
        try {
            Mail::raw('This is a SendGrid SMTP test from Vicentech app.', function ($m) use ($to) {
                $m->to($to)->subject('Vicentech SMTP Test');
            });

            return response()->json(['message' => 'Mail queued/sent (check provider logs)']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send test mail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
