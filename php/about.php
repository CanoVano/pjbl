<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us</title>
  <link rel="stylesheet" href="../css/about.css">
  <script src="../js/script.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <header class="app-bar">
  <i class="fas fa-arrow-left back-icon" onclick="history.back()"></i>
    <h3>Informasi</h3>
  </header>

  <main>
    <section class="location-info">
  <h2 class="store-title">Hexagon Mart - Semarang</h2>
  <i class="fas fa-map-marker-alt"></i>
  <div class="map-box">
    <img src="../images/gambar maps.jpg" alt="Map" class="map-image">
  </div>
  <div class="info-item">
    <i class="fas fa-paper-plane"></i>
    <p>Jl. Pandanaran 2, Mugassari, Kec. Semarang Sel.,<br>
       Kota Semarang, Jawa Tengah<br>50249</p>
  </div>
  <div class="info-item">
    <i class="fas fa-clock"></i>
    <p>Jam Buka (WIB):<br>Sen–Min&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;09.00–21.00</p>
  </div>
</section>

    <section class="about-us">
      <h2 style="text-align: center; font-size: 26px; margin-bottom: 5px;">Kelompok 4</h2>
  <!-- Modal -->
  <div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <div class="modal-caption">
      <h3 id="memberName">Nama Anggota</h3>
      <p id="memberFeature">Fitur yang dikerjakan</p>
    </div>
  </div>

  <h2>About us</h2>
  <div class="team-photos">
    <img src="../images/anggota kelompok pjbl1.jpg" alt="Anggota Tim 1" onclick="showModal(this)" data-name="Ernesya Fatimah Zahra" data-feature="Fitur Belanja Dan Keranjang">
    <img src="../images/anggota kelompok pjbl2.jpg" alt="Anggota Tim 2" onclick="showModal(this)" data-name="Fatih Dzaki" data-feature="Fitur Pembelian">
    <img src="../images/anggota kelompok pjbl3.jpg" alt="Anggota Tim 3" onclick="showModal(this)" data-name="Muhammad Syahrul" data-feature="Fitur Login Dan Register">
    <img src="../images/anggota kelompok pjbl4.jpg" alt="Anggota Tim 4" onclick="showModal(this)" data-name="Nizar Azzuhra" data-feature="Fitur Login Admin">
    <img src="../images/anggota kelompok pjbl5.jpg" alt="Anggota Tim 5" onclick="showModal(this)" data-name="Revano Fachrezi" data-feature="Fitur Blog">
    <img src="../images/anggota kelompok pjbl6.jpg" alt="Anggota Tim 6" onclick="showModal(this)" data-name="Syavira Putri" data-feature="Fitur Ulasan Dan Kritik / Review">
  </div>
</section>

    <section class="team-section">
  <div class="team-container">
    <div class="team-photo">
      <img src="../images/foto kelompok pjbl.jpg" alt="Foto Tim Hexagon">
    </div>
    <div class="team-desc">
      <h3>Tim Hexagon mart</h3>
      <div class="desc-box">
        <p>
          Dengan senang hati kami mengembangkan website e-commerce food bernama Hexagon Mart.
          Yang bertujuan untuk membantu UMKM di kota Semarang melalui platform kami.
          Hexagon Mart hadir sebagai jembatan antara pelaku usaha kecil dengan pelanggan setia
          yang mencari produk makanan terbaik.
        </p>
        <p class="quote">
          –Tim Hexagon mart <span class="heart">❤️</span>
        </p>
      </div>
    </div>
  </div>
</section>
