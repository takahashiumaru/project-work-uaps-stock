{{-- filepath: /Applications/XAMPP/xamppfiles/htdocs/project-work-uaps-stock/resources/views/emails/login_otp.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Login</title>
</head>
<body style="font-family: 'Public Sans', Arial, sans-serif; background-color: #f4f5fb; margin: 0; padding: 16px;">
    <table align="center" cellpadding="0" cellspacing="0"
           style="max-width: 600px; width:100%; background-color: #ffffff;
                  border-radius: 16px; overflow: hidden;
                  box-shadow: 0 4px 12px rgba(15,23,42,0.12);">
        <tr>
            <td align="center" style="background-color: #0d6efd; padding: 24px;">
                <h2 style="color: #ffffff; margin: 0; font-weight: 600; font-size: 20px;">
                    OTP Login Akun APSone
                </h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 28px 28px 24px 28px;">
                <p style="font-size: 15px; color: #111827; margin: 0 0 12px;">
                    Halo, <strong>{{ $user->fullname ?? ($user->name ?? 'Pengguna') }}</strong>
                </p>
                <p style="font-size: 14px; color: #4b5563; margin: 0 0 18px;">
                    Kami menerima permintaan untuk login ke akun Anda. Berikut kode OTP Anda:
                </p>
                <div style="text-align: center; margin: 24px 0;">
                    <span style="display:inline-block;padding:10px 18px;border-radius:999px;
                                 background-color:#ecf2ff;color:#0d6efd;
                                 font-size:26px;font-weight:700;letter-spacing:4px;">
                        {{ $otp }}
                    </span>
                </div>
                <p style="font-size: 13px; color: #6b7280; margin: 0 0 6px;">
                    Kode ini hanya berlaku selama <strong>10 menit</strong>.
                </p>
                <p style="font-size: 13px; color: #9ca3af; margin: 0;">
                    Jika Anda tidak meminta OTP login, abaikan email ini.
                </p>
                <hr style="margin: 24px 0 12px 0; border: none; border-top: 1px solid #e5e7eb;">
                <p style="font-size: 12px; color: #9ca3af; text-align: center; margin: 0 0 16px;">
                    &copy; {{ date('Y') }} APSone. Semua hak dilindungi.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>