<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <!-- menghubungkan ke file CSS profil -->
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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
                    <input type="text" id="name" name="name" value="{{ $user->name }}" class="profile-input" required>
                </div>

                <div class="profile-item">
                    <label for="email" class="profile-label">Email</label>
                    <div class="email-display">
                        <span class="current-email">{{ $user->email }}</span>
                        <button type="button" class="btn-change-email" onclick="showChangeEmailModal()">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Ubah Email
                        </button>
                    </div>
                    <input type="hidden" id="email" name="email" value="{{ $user->email }}">
                </div>

                <input type="hidden" id="current_password" name="current_password">

                <div class="profile-item">
                    <span class="profile-label">Tanggal Daftar</span>
                    <div class="profile-value">{{ $user->created_at->format('d M Y H:i') }}</div>
                </div>

                <div class="profile-actions">
                    <button type="button" class="btn-update" onclick="showUpdateModal()">Update Profil</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Update -->
    <div id="updateModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi Update Profil</h3>
                <span class="modal-close" onclick="closeUpdateModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengupdate nama profil?</p>
                <div class="confirmation-details">
                    <div class="detail-item">
                        <strong>Nama Baru:</strong> <span id="confirmName"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeUpdateModal()">Batal</button>
                <button type="button" class="btn-confirm" onclick="submitForm()">Ya, Update Nama</button>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Email -->
    <div id="changeEmailModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ubah Email</h3>
                <span class="modal-close" onclick="closeChangeEmailModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="email-form">
                    <div class="form-group">
                        <label for="newEmail" class="form-label">Email Baru</label>
                        <input type="email" id="newEmail" class="form-input" placeholder="Masukkan email baru">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword" class="form-label">Password Saat Ini</label>
                        <input type="password" id="confirmPassword" class="form-input" placeholder="Masukkan password untuk konfirmasi">
                        <small class="form-note">Password diperlukan untuk mengubah email</small>
                    </div>
                </div>
                <div class="email-warning">
                    <p><strong>⚠️ Perhatian:</strong></p>
                    <ul>
                        <li>Email baru akan digunakan untuk login</li>
                        <li>Pastikan email baru valid dan dapat diakses</li>
                        <li>Perubahan email tidak dapat dibatalkan</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeChangeEmailModal()">Batal</button>
                <button type="button" class="btn-confirm" onclick="updateEmail()">Ubah Email</button>
            </div>
        </div>
    </div>

    <script>

        // Fungsi untuk menampilkan modal konfirmasi update nama
        function showUpdateModal() {
            const nameInput = document.getElementById('name');
            
            // Validasi input
            if (!nameInput.value.trim()) {
                alert('Nama harus diisi.');
                nameInput.focus();
                return;
            }
            
            // Isi data konfirmasi
            document.getElementById('confirmName').textContent = nameInput.value;
            
            // Tampilkan modal
            document.getElementById('updateModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        // Fungsi untuk menampilkan modal ubah email
        function showChangeEmailModal() {
            document.getElementById('changeEmailModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            document.getElementById('newEmail').focus();
        }
        
        // Fungsi untuk menutup modal
        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        
        function closeChangeEmailModal() {
            document.getElementById('changeEmailModal').style.display = 'none';
            document.body.style.overflow = '';
            // Reset form
            document.getElementById('newEmail').value = '';
            document.getElementById('confirmPassword').value = '';
        }
        
        // Fungsi untuk submit form update nama
        function submitForm() {
            document.getElementById('profileForm').submit();
        }
        
        // Fungsi untuk update email
        function updateEmail() {
            const newEmail = document.getElementById('newEmail').value.trim();
            const password = document.getElementById('confirmPassword').value.trim();
            
            if (!newEmail) {
                alert('Email baru harus diisi.');
                document.getElementById('newEmail').focus();
                return;
            }
            
            if (!password) {
                alert('Password harus diisi untuk mengubah email.');
                document.getElementById('confirmPassword').focus();
                return;
            }
            
            // Validasi format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(newEmail)) {
                alert('Format email tidak valid.');
                document.getElementById('newEmail').focus();
                return;
            }
            
            // Update hidden email field dan submit form
            document.getElementById('email').value = newEmail;
            document.getElementById('current_password').value = password;
            document.getElementById('profileForm').submit();
        }
        
        // Menutup modal jika klik di luar konten modal
        window.onclick = function(event) {
            const updateModal = document.getElementById('updateModal');
            const changeEmailModal = document.getElementById('changeEmailModal');
            
            if (event.target == updateModal) {
                closeUpdateModal();
            }
            if (event.target == changeEmailModal) {
                closeChangeEmailModal();
            }
        };
    </script>
</body>

</html>
</html>