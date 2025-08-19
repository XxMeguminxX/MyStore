<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <!-- menghubungkan ke file CSS profil -->
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
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

        <div class="info-box" style="background: #e8f5e8; border: 1px solid #2a9d8f; border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: center;">
            <p style="margin: 0; color: #2a9d8f; font-size: 0.9em;">
                <strong>ℹ️ Informasi:</strong> Semua data profile harus lengkap untuk dapat melakukan checkout.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Kartu profil dengan struktur baru --}}
        <div class="profile-content">
            <form method="POST" action="{{ route('profile.update') }}" class="profile-form" id="profileForm">
                @csrf

                <div class="profile-info">
                <div class="profile-item">
                    <span class="profile-label">ID User</span>
                    <div class="profile-value">{{ $user->id }}</div>
                </div>

                <div class="profile-item">
                    <label for="name" class="profile-label">Nama</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" class="profile-input {{ empty($user->name) ? 'field-empty' : '' }}" readonly>
                    @if(empty($user->name))
                        <small class="field-warning">⚠️ Nama harus diisi untuk melakukan checkout</small>
                    @endif
                </div>

                <div class="profile-item">
                    <label for="email" class="profile-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" class="profile-input {{ empty($user->email) ? 'field-empty' : '' }}" readonly>
                    @if(empty($user->email))
                        <small class="field-warning">⚠️ Email harus diisi untuk melakukan checkout</small>
                    @endif
                </div>

                <div class="profile-item">
                    <label for="phone" class="profile-label">No HP</label>
                    <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}" class="profile-input {{ empty($user->phone) ? 'field-empty' : '' }}" readonly>
                    @if(empty($user->phone))
                        <small class="field-warning">⚠️ No HP harus diisi untuk melakukan checkout</small>
                    @endif
                </div>

                <div class="profile-item">
                    <span class="profile-label">Tanggal Daftar</span>
                    <div class="profile-value">{{ $user->created_at->format('d M Y H:i') }}</div>
                </div>

                <div class="profile-actions">
                    <button type="button" class="btn-update" onclick="showEditProfileModal()">Edit Profil</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div id="editProfileModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Profil</h3>
                <span class="modal-close" onclick="closeEditProfileModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" class="edit-profile-form">
                    <div class="form-group">
                        <label for="editName" class="form-label">Nama</label>
                        <input type="text" id="editName" name="edit_name" value="{{ $user->name }}" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" id="editEmail" name="edit_email" value="{{ $user->email }}" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editPhone" class="form-label">No HP</label>
                        <input type="tel" id="editPhone" name="edit_phone" value="{{ $user->phone ?? '' }}" class="form-input" placeholder="Masukkan nomor HP">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditProfileModal()">Batal</button>
                <button type="button" class="btn-confirm" onclick="saveProfileChanges()">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan modal edit profil
        function showEditProfileModal() {
            // Isi form edit dengan data saat ini
            document.getElementById('editName').value = '{{ $user->name }}';
            document.getElementById('editEmail').value = '{{ $user->email }}';
            document.getElementById('editPhone').value = '{{ $user->phone ?? "" }}';
            
            // Tampilkan modal
            document.getElementById('editProfileModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        // Fungsi untuk menutup modal edit profil
        function closeEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        
        // Fungsi untuk menyimpan perubahan profil
        function saveProfileChanges() {
            const name = document.getElementById('editName').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const phone = document.getElementById('editPhone').value.trim();
            
            // Validasi input
            if (!name) {
                alert('Nama harus diisi.');
                document.getElementById('editName').focus();
                return;
            }
            
            if (!email) {
                alert('Email harus diisi.');
                document.getElementById('editEmail').focus();
                return;
            }
            
            // Validasi format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Format email tidak valid.');
                document.getElementById('editEmail').focus();
                return;
            }
            
            // Update field readonly di halaman utama
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('phone').value = phone;
            
            // Submit form untuk menyimpan ke database
            document.getElementById('profileForm').submit();
        }
        
        // Menutup modal jika klik di luar konten modal
        window.onclick = function(event) {
            const editProfileModal = document.getElementById('editProfileModal');
            
            if (event.target == editProfileModal) {
                closeEditProfileModal();
            }
        };
    </script>
</body>

</html>