<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E Store ID</title>
  <style>
  </style>
  <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
  <div class="header-bar">
    <div class="header-icons">
      <a href="#" class="icon-btn" title="Profil">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="8" r="4" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/></svg>
      </a>
    </div>
  </div>
  <h1>Produk Digital Saya</h1>
  <div class="product-grid">
    @foreach ($product as $data)
      <div class="product-card">
      <div class="product-id">ID: {{ $data->id }}</div>
      <img class="product-img" src="{{ $data->image }}" alt="Ebook Belajar Laravel">
      <div class="product-title">{{ $data->name }}</div>
      <div class="product-desc">
        <span class="desc-short">{{ substr($data->description,0,80) }}</span>
        <span class="desc-full" style="display:none;">{!! nl2br(e($data->description)) !!}</span>
        <button class="btn-desc-toggle" onclick="openDescModal(this)">Lihat Selengkapnya</button>
      </div>
      <div class="product-price">Rp {{ number_format($data->price,0,'','.') }}</div>
      <div class="product-actions">
        <a href="{{url('/beli/'.$data->id) }}" class="btn btn-beli" id="beli-produk-1">Beli</a>

      </div>
    </div>
    @endforeach


  <div id="desc-modal" class="desc-modal" style="display:none;">
    <div class="desc-modal-content">
      <span class="desc-modal-close" onclick="closeDescModal()">&times;</span>
      <div id="desc-modal-title"></div>
      <div id="desc-modal-body"></div>
    </div>
  </div>
  <script>
    
function openDescModal(btn) {
  const card = btn.closest('.product-card');
  const title = card.querySelector('.product-title').textContent;
  const fullDesc = card.querySelector('.desc-full').innerHTML;

  document.getElementById('desc-modal-title').textContent = title;
  document.getElementById('desc-modal-body').innerHTML = fullDesc;
  document.getElementById('desc-modal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
function closeDescModal() {
  document.getElementById('desc-modal').style.display = 'none';
  document.body.style.overflow = '';
}
</script>
</body>
</html>