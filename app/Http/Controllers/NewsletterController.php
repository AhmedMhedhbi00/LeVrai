<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data  = $request->json()->all();
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email non valida']);
        }

        if (Newsletter::where('email', $email)->exists()) {
            return response()->json(['success' => false, 'message' => 'Sei già iscritto 🔥']);
        }

        $codice   = 'LEVRAI-' . strtoupper(substr(md5($email . uniqid()), 0, 6));
        $scadenza = now()->addDays(7)->format('Y-m-d');

        Newsletter::create([
            'email'         => $email,
            'codice_sconto' => $codice,
            'percentuale'   => 10,
            'scadenza'      => $scadenza,
        ]);

        $subject  = "🎁 Il tuo sconto Le Vrai Streetwear";
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Le Vrai Streetwear <info@levraistreetwear.com>\r\n";

        $message = "
        <div style='font-family:Arial,sans-serif;background:#000;color:#fff;padding:40px;max-width:600px;margin:0 auto;'>
            <h1 style='color:#fff;'>Benvenuto in Le Vrai Streetwear 🔥</h1>
            <p style='color:#ccc;'>Grazie per esserti iscritto!</p>
            <p>Il tuo codice sconto <strong style='color:#3a86ff;'>-10%</strong>:</p>
            <div style='font-size:26px;font-weight:bold;background:#fff;color:#000;padding:15px 25px;display:inline-block;margin:20px 0;letter-spacing:3px;'>
                {$codice}
            </div>
            <p style='color:#aaa;font-size:14px;'>Valido fino al <strong style='color:#fff;'>{$scadenza}</strong></p>
            <hr style='border-color:#333;margin:30px 0;'>
            <p style='font-size:12px;color:#666;'>© 2025 Le Vrai Streetwear — Via Palermo 7, Caltagirone (CT)</p>
        </div>";

        @mail($email, $subject, $message, $headers);

        return response()->json([
            'success' => true,
            'message' => 'Iscrizione avvenuta! Controlla la tua mail 📩'
        ]);
    }
}