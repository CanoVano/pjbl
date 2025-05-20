<?php
include 'koneksi_pjbl.php';
?>  
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ayam Geprek Godzila Meteor - Product & Order Summary</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome untuk ikon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

  <!-- Google Fonts - Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

  <!-- Custom Style -->
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-white text-gray-900 font-sans">
  <div id="app" class="max-w-5xl mx-auto p-4 space-y-10">
    
<!-- Back Arrow Button -->
    <a
      v-show="currentView === 'product'"
      href="menu.php"
      class="fixed top-4 left-10 z-50 bg-[#f2a900] hover:bg-yellow-400 text-black rounded-full p-3 shadow-lg transition"
      aria-label="Kembali ke menu"
      style="display: flex; align-items: center; justify-content: center;"
    >
      <i class="fas fa-arrow-left text-xl"></i>
    </a>
<!-- ...existing code... -->
  
    <!-- === SECTION: Produk === -->
    <section v-show="currentView === 'product'" class="flex flex-col md:flex-row md:space-x-6" >
        
      <!-- Gambar Produk -->
      <div class="flex-shrink-0 w-full md:w-72">
        <img
          alt="Foto Ayam Geprek Godzila Meteor"
          class="w-full h-auto object-cover rounded"
          src="Ayam Geprek Godzila Meteor.jpg"
          width="250"
          height="250"
        />
      </div>

      <!-- Detail Produk -->
      <div class="flex-1 mt-4 md:mt-0">
        <div class="flex justify-between items-start">
          <h1 class="font-semibold text-lg md:text-xl leading-tight max-w-[70%]">Ayam Geprek Godzila Meteor</h1>
          <img
            alt="Logo Hexagon Mart"
            class="w-10 h-10 object-contain"
            src="logo tanpa huruf.jpg"
            width="40"
            height="40"
          />
        </div>

        <!-- Rating Produk -->
        <div class="flex items-center space-x-1 mt-1 text-sm text-gray-600">
          <span class="font-semibold text-yellow-500">4.7</span>
          <div class="flex space-x-0.5 text-yellow-400">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
          </div>
          <span class="text-gray-400">|</span>
          <span class="text-gray-500 text-xs">15.6RB</span>
          <span class="text-gray-400">|</span>
          <span class="text-gray-500 text-xs">terjual</span>
        </div>

        <!-- Harga -->
        <p class="font-bold text-xl mt-3">Rp15.000</p>
        <p class="text-sm text-gray-700 mt-1">per bagian</p>

        <!-- Pilihan bagian ayam -->
        <div class="flex space-x-3 mt-2 max-w-xs flex-wrap">
          <button
            :class="selectedPart === 'Dada' ? 'bg-cyan-600' : 'bg-cyan-400'"
            @click="selectedPart = 'Dada'"
            class="text-white rounded-md px-4 py-1 text-sm font-semibold hover:bg-cyan-500 transition">
            Dada
          </button>
          <button
            :class="selectedPart === 'Sayap' ? 'bg-cyan-600' : 'bg-cyan-400'"
            @click="selectedPart = 'Sayap'"
            class="text-white rounded-md px-4 py-1 text-sm font-semibold hover:bg-cyan-500 transition">
            Sayap
          </button>
          <button
            :class="selectedPart === 'Paha' ? 'bg-cyan-600' : 'bg-cyan-400'"
            @click="selectedPart = 'Paha'"
            class="text-white rounded-md px-4 py-1 text-sm font-semibold hover:bg-cyan-500 transition">
            Paha
          </button>
        </div>

        <!-- Input jumlah pembelian -->
        <div class="mt-4 max-w-xs">
          <label for="quantity-input" class="block text-sm text-gray-700 mb-1">Jumlah</label>
          <input
            id="quantity-input"
            class="w-14 text-center rounded-md bg-cyan-200 text-cyan-900 font-semibold"
            min="1"
            type="number"
            v-model.number="quantity"
          />
        </div>

        <!-- Tombol pesanan -->
        <div class="flex space-x-4 mt-4 max-w-xs">
          <button
            @click="addToCart"
            class="flex-1 bg-cyan-400 text-black rounded-md py-2 text-center text-lg font-semibold hover:bg-cyan-500 transition">
            <i class="fas fa-shopping-cart"></i>
          </button>
          <button
            @click="goToOrderSummary"
            class="flex-1 bg-cyan-400 text-black rounded-md py-2 text-center text-lg font-semibold hover:bg-cyan-500 transition">
            Pesan Sekarang
          </button>
        </div>
      </div>
    </section>

    <!-- === SECTION: Tentang Produk === -->
    <section v-show="currentView === 'product'">
      <p class="text-gray-700 font-semibold mb-1">Tentang Produk</p>
      <div class="bg-yellow-500 bg-opacity-90 p-3 rounded text-sm text-black leading-tight flex justify-between items-center">
        <p class="max-w-[85%]">
          Produk ini dibuat dari ayam pilihan berkualitas tinggi dari peternakan terbaik, diolah secara higienis dan disukai oleh banyak orang.
        </p>
        <a class="text-blue-700 underline text-sm" href="#">Lihat Komentar</a>
      </div>
    </section>

    <!-- === SECTION: Ringkasan Pesanan === -->
    <section v-show="currentView === 'order'" class="max-w-4xl mx-auto w-full">
      
      <!-- Header Ringkasan -->
      <div class="bg-[#f2a900] flex items-center px-4 py-3 rounded-t-md">
        <button @click="goToProduct" class="text-black text-2xl"><i class="fas fa-arrow-left"></i></button>
        <h1 class="flex-1 text-center font-semibold text-black text-lg">Ringkasan Pesanan</h1>
        <div class="w-8"></div>
      </div>

      <!-- Info Pengguna -->
      <div class="flex justify-between items-center px-4 py-4 text-black text-lg font-normal border border-t-0 border-gray-300 rounded-b-md">
        <span>minex Fz</span>
        <span>(+62)86******07</span>
      </div>

      <!-- Detail Produk dalam Pesanan -->
      <div class="bg-[#1ea1ce] rounded-md mx-4 flex flex-col md:flex-row md:items-center md:justify-between text-black mt-6">
        <div class="flex p-4 md:p-6 md:flex-1">
          <img src="Ayam Geprek Godzila Meteor.jpg" alt="Foto Produk" class="rounded-lg w-[120px] h-[120px] object-cover" />
          <div class="ml-4 flex flex-col justify-between">
            <h2 class="font-bold text-lg mb-1"><i class="fas fa-cube mr-2"></i>Hexagon Mart</h2>
            <p class="text-base mb-2">Ayam Geprek Godzila Meteor - {{ selectedPart }}</p>
            <p class="font-extrabold text-xl">Rp15.000</p>
          </div>
          <div class="ml-auto flex items-center justify-center bg-[#1ea1ce]/40 rounded-full w-10 h-10 text-black font-semibold text-lg">
          </div>
        </div>

        <!-- Ringkasan Total pembelian -->
        <div class="p-4 md:p-6 flex-1 text-black">
          <h3 class="font-semibold mb-3">Ringkasan Pesanan</h3>
          <div class="flex justify-between mb-1">
            <span>Waktu Pengambilan</span>
            <span class="font-semibold">09.30 WIB</span>
          </div>
          <div class="flex justify-between mb-3">
            <span>Subtotal</span>
            <span class="font-semibold">Rp{{ (15000 * quantity).toLocaleString('id-ID') }}</span>
          </div>
          <div class="flex justify-between font-extrabold text-lg">
            <span>Total</span>
            <span>Rp{{ (15000 * quantity).toLocaleString('id-ID') }}</span>
          </div>
        </div>
      </div>

      <!-- transaksi Pembayaran -->
      <div class="flex items-center justify-between px-4 py-4 text-black text-lg font-normal mt-6 mx-4 border border-gray-300 rounded-md">
        <div class="flex items-center space-x-2">
          <i class="fas fa-money-bill-wave text-xl"></i>
          <span>Pembayaran</span>
          <span class="ml-2 font-semibold">Tunai</span>
        </div>
      </div>

      <!-- Total dan Tombol Buat Pesanan -->
      <div class="flex justify-between items-center px-4 py-4 text-black text-lg font-normal mx-4">
        <span>Total ({{ quantity }} item{{ quantity > 1 ? 's' : '' }})</span>
        <span class="font-semibold">Rp{{ (15000 * quantity).toLocaleString('id-ID') }}</span>
      </div>
      <button @click="placeOrder" class="mx-4 mb-4 bg-[#e6c94a] text-black font-extrabold text-xl py-4 rounded-md w-full">
        Buat Pesanan
      </button>
    </section>

    <!-- Success Notification -->
    <div v-if="successMessage" class="bg-green-500 text-white p-4 rounded-md">
      {{ successMessage }}
    </div>
  </div>

  <!-- Vue.js -->
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
  <script>
    const { createApp } = Vue;

    createApp({
      data() {
        return {
          currentView: 'product', // Tampilan saat ini: 'product' atau 'order'
          selectedPart: 'Dada',   // Bagian ayam yang dipilih
          quantity: 1,            // Jumlah pembelian
          successMessage: ''      // Pesan sukses
        };
      },

      methods: {

        // Method untuk kembali ke tampilan menu
        goToProduct() {
          this.currentView = 'menu '; // Ganti tampilan saat menjadi 'menu'
          window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll halaman 
        },
        
        // Method untuk berpindah ke tampilan ringkasan pesanan
        goToOrderSummary() {
          // Cek apakah jumlah pesanan kurang dari 1
          if (this.quantity < 1) {
            alert('Jumlah harus minimal 1'); // Tampilan peringatan jika jumlah tidak sesuai
            return; // berhenti jika jumlah tidak valid
          }

          // Ganti tampilan saat ini menjadi 'order'
          this.currentView = 'order'; 
          window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll halaman ke atas
        },

        // Method untuk kembali ke tampilan produk
        goToProduct() {
          this.currentView = 'product'; // Ganti tampilan saat menjadi 'product'
          window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll halaman 
          
        },

        // Method untuk menambahkan produk ke keranjang
        addToCart() {
          // Tampilkan pesan produk yang ditambahkan ke keranjang
          alert(`Added ${this.quantity} x Ayam Geprek Godzila Meteor (${this.selectedPart}) to cart.`);
        },

        // Method untuk melakukan pemesanan 
        placeOrder() {
          // Set pesan sukses
          this.successMessage = `Pembelian sukses:\nProduk: Ayam Geprek Godzila Meteor (${this.selectedPart})\nJumlah: ${this.quantity}\nTotal: Rp${(15000 * this.quantity).toLocaleString('id-ID')}`;
          // Reset tampilan ke produk setelah beberapa detik

          setTimeout(() => {
            this.successMessage = '';
            this.goToProduct();
          }, 3000); // Pesan akan hilang setelah 3 detik
        }
      }
    }).mount('#app');
  </script>
</body>
</html>
