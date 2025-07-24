<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <!-- menghubungkan ke file CSS profil -->
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
</head>
<body>
    {{-- Header baru dengan tombol kembali --}}
    <div class="header-bar">
        <a href="{{ url('/dashboard') }}" class="btn-kembali">
            &larr; Kembali
        </a>
    </div>

    {{-- Kontainer untuk konten utama --}}
    <div class="profile-container">
        <h1>Profil Pengguna</h1>

        {{-- Kartu profil dengan struktur baru --}}
        <div class="profile-content">
            <div class="profile-item">
                <span class="profile-label">Nama</span>
                <div class="profile-value">Erik Wahyu Saputra</div>
            </div>

            <div class="profile-item">
                <span class="profile-label">Email</span>
                <div class="profile-value">saputraerik042@gmail.com</div>
            </div>
            
            </div>
    </div>
</body>
</html>