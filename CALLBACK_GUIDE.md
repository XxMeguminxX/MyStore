# 🚀 Panduan Sistem Callback Tripay

## 📋 **Ringkasan**
Sistem callback memungkinkan status transaksi diupdate secara otomatis ketika pembayaran selesai di Tripay, tanpa perlu intervensi manual.

## 🔧 **Komponen Sistem**

### 1. **Callback Endpoint**
- **URL**: `/tripay/callback`
- **Method**: POST
- **Authentication**: Tidak diperlukan (callback dari Tripay)
- **Controller**: `TripayController@handleCallback`

### 2. **Fitur Keamanan**
- **Signature Verification**: Memverifikasi signature dari Tripay menggunakan private key
- **JSON Validation**: Memvalidasi format JSON yang diterima
- **Transaction Lookup**: Mencari transaksi berdasarkan `merchant_ref`

### 3. **Logging & Debugging**
- **Detailed Logs**: Semua callback dicatat di Laravel logs
- **Error Handling**: Penanganan error yang komprehensif
- **Debug Info**: Informasi debug untuk troubleshooting

## 🔄 **Alur Kerja Callback**

### **Step 1: Tripay Mengirim Callback**
```
Tripay → POST /tripay/callback
Headers: X-Callback-Signature: {signature}
Body: JSON dengan data transaksi
```

### **Step 2: Verifikasi & Validasi**
1. **Log Raw Data**: Mencatat semua data yang diterima
2. **Signature Check**: Verifikasi signature menggunakan private key
3. **JSON Parse**: Parse dan validasi JSON body
4. **Data Validation**: Pastikan `merchant_ref` ada

### **Step 3: Update Database**
1. **Find Transaction**: Cari transaksi berdasarkan `merchant_ref`
2. **Status Update**: Update status transaksi
3. **Response Log**: Simpan data callback di field `response`
4. **Email Notification**: Kirim email jika status berubah ke PAID/SETTLED

### **Step 4: Response**
```
HTTP 200 OK
{"success": true, "message": "Callback processed successfully"}
```

## 🛠 **Testing & Debugging**

### **1. Manual Status Update**
```javascript
// Di halaman histori transaksi
updateTransactionStatus(transactionId, 'PAID')
```

### **2. Test Callback Simulation**
```javascript
// Simulasi callback dari Tripay
testCallback(transactionId)
```

### **3. Command Line Testing**
```bash
# Test callback menggunakan script PHP
php test_callback.php
```

### **4. Log Monitoring**
```bash
# Lihat logs Laravel
tail -f storage/logs/laravel.log
```

## 📊 **Status Transaksi**

### **Status yang Didukung**
- `UNPAID` - Belum dibayar
- `PAID` - Sudah dibayar
- `EXPIRED` - Kadaluarsa
- `CANCELLED` - Dibatalkan
- `PENDING` - Menunggu konfirmasi
- `SETTLED` - Sudah diselesaikan

### **Status Badge Colors**
- **UNPAID/PENDING**: Kuning (`#fff3cd`)
- **PAID/SETTLED**: Hijau (`#d4edda`)
- **EXPIRED/CANCELLED**: Merah (`#f8d7da`)

## 🔍 **Troubleshooting**

### **Masalah Umum**

#### **1. Callback Tidak Terima**
- **Cek**: Route `/tripay/callback` terdaftar
- **Cek**: Server bisa diakses dari internet
- **Cek**: Firewall tidak memblokir request

#### **2. Signature Mismatch**
- **Cek**: Private key di `.env` benar
- **Cek**: Signature header `X-Callback-Signature` ada
- **Cek**: Body request tidak berubah

#### **3. Transaction Not Found**
- **Cek**: `merchant_ref` di callback sama dengan di database
- **Cek**: Transaksi belum dihapus dari database

#### **4. Status Tidak Update**
- **Cek**: Logs untuk error
- **Cek**: Database connection
- **Cek**: Model Transaction fillable fields

### **Debug Commands**
```bash
# Cek transaksi di database
php artisan tinker
>>> App\Models\Transaction::all()->pluck('merchant_ref', 'status')

# Cek logs terbaru
tail -n 50 storage/logs/laravel.log | grep "Tripay callback"

# Test callback manual
php test_callback.php
```

## 📝 **Konfigurasi Tripay**

### **Callback URL di Dashboard Tripay**
```
https://yourdomain.com/tripay/callback
```

### **Environment Variables**
```env
TRIPAY_API_KEY=your_api_key
TRIPAY_MERCHANT_CODE=your_merchant_code
TRIPAY_PRIVATE_KEY=your_private_key
```

## 🎯 **Best Practices**

### **1. Monitoring**
- Monitor logs secara regular
- Set up alerting untuk callback failures
- Track callback success rate

### **2. Security**
- Selalu verifikasi signature
- Gunakan HTTPS untuk callback URL
- Log semua callback attempts

### **3. Error Handling**
- Implement retry mechanism
- Send notifications for failed callbacks
- Keep backup of transaction data

### **4. Testing**
- Test callback dengan berbagai status
- Simulate network failures
- Test signature verification

## 📞 **Support**

Jika ada masalah dengan sistem callback:
1. Cek logs di `storage/logs/laravel.log`
2. Test callback menggunakan fitur "Test Callback"
3. Verifikasi konfigurasi Tripay
4. Pastikan server bisa diakses dari internet 