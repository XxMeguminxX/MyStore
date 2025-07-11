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