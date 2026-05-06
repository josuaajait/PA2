<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Caldera Resto & Pool</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .icon-wrap {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #dcfce7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .icon-wrap svg {
            width: 28px;
            height: 28px;
            stroke: #15803d;
            fill: none;
            stroke-width: 2;
        }
        h2 {
            font-size: 22px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .subtitle strong { color: #111827; }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 13px;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-size: 13px;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 24px;
        }
        .otp-inputs input {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s;
            color: #111827;
        }
        .otp-inputs input:focus {
            border-color: #15803d;
            box-shadow: 0 0 0 3px rgba(21,128,61,0.1);
        }
        .btn-verify {
            width: 100%;
            padding: 13px;
            background: #15803d;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 16px;
        }
        .btn-verify:hover { background: #166534; }
        .resend-text {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .btn-resend {
            background: none;
            border: none;
            color: #15803d;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
        }
        .btn-resend:hover { color: #166534; }
        .otp-note {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 20px;
        }
        .btn-logout {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 12px;
            cursor: pointer;
        }
        .btn-logout:hover { color: #6b7280; text-decoration: underline; }
    </style>
</head>
<body>

<div class="card">

    {{-- Icon --}}
    <div class="icon-wrap">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="m22 7-10 7L2 7"/>
        </svg>
    </div>

    <h2>Verifikasi Email</h2>
    <p class="subtitle">
        Masukkan kode 6 digit yang dikirim ke<br>
        <strong>{{ auth()->user()->email }}</strong>
    </p>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Alert Error --}}
    @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- Form Verifikasi OTP --}}
    <form method="POST" action="{{ route('verification.otp.verify') }}" id="otp-form">
        @csrf
        <div class="otp-inputs">
            @for($i = 0; $i < 6; $i++)
                <input
                    type="text"
                    maxlength="1"
                    class="otp-digit"
                    inputmode="numeric"
                    autocomplete="off"
                >
            @endfor
        </div>
        <input type="hidden" name="otp" id="otp-hidden">
        <button type="submit" class="btn-verify">Verifikasi</button>
    </form>

    {{-- Kirim Ulang OTP --}}
    <p class="resend-text">
        Tidak dapat kode?
        <form method="POST" action="{{ route('verification.otp.send') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-resend">Kirim ulang OTP</button>
        </form>
    </p>

    <p class="otp-note">Kode berlaku selama <strong>10 menit</strong></p>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Keluar dari akun ini</button>
    </form>

</div>

<script>
    const digits = document.querySelectorAll('.otp-digit');
    const hidden = document.getElementById('otp-hidden');

    function updateHidden() {
        hidden.value = [...digits].map(d => d.value).join('');
    }

    digits.forEach((input, index) => {
        // Hanya izinkan angka
        input.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) e.preventDefault();
        });

        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 1);
            if (this.value && index < digits.length - 1) {
                digits[index + 1].focus();
            }
            updateHidden();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                digits[index - 1].focus();
            }
        });

        // Support paste OTP sekaligus
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach(function(char, i) {
                if (digits[i]) digits[i].value = char;
            });
            updateHidden();
            const lastFilled = Math.min(pasted.length, digits.length - 1);
            digits[lastFilled].focus();
        });
    });

    // Update hidden sebelum submit
    document.getElementById('otp-form').addEventListener('submit', function() {
        updateHidden();
    });

    // Auto focus input pertama
    digits[0].focus();
</script>

</body>
</html>