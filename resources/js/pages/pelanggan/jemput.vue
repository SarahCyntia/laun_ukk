<template>
  <div class="pelanggan-area py-5">
    <!-- Header -->
    <div class="text-center mb-5">
      <span class="badge bg-info text-white fs-6 px-7 py-2 mb-7 mt-10">
        <i class="fas fa-user me-2"></i> Area Pelanggan
      </span>
      <h1 class="fw-bold display-5">
        Selamat Datang di <span class="text-info">SLaundry</span>
      </h1>
      <p class="text-muted fs-5">
        Lacak status cucian Anda atau pesan layanan antar jemput dengan mudah
      </p>
    </div>

    <!-- Tabs -->
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-8">
      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan"
      >
        <i class="fas fa-search me-2"></i> Cek Status
      </router-link>

      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan/riwayat' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/riwayat"
      >
        <i class="fas fa-history me-2"></i> Riwayat
      </router-link>

      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan/antar' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/antar"
      >
        <i class="fas fa-truck-loading me-2"></i> Antar Cucian
      </router-link>

      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan/jemput' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/jemput"
      >
        <i class="fas fa-truck-pickup me-2"></i> Jemput Cucian
      </router-link>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px">
      <div class="card-header bg-info text-white fw-semibold fs-2 py-3 d-flex align-items-center">
        <i class="fas fa-truck-pickup me-3"></i>
        <span>Jemput Cucian</span>
      </div>

      <div class="card-body p-9">
        <!-- Warning Batas Waktu -->
        <div class="alert alert-warning border-start border-warning border-4 mb-4" role="alert">
          <i class="fas fa-clock text-warning me-2"></i>
          <strong>Batas Waktu Penjemputan</strong><br />
          <small class="text-muted">
            Permintaan penjemputan yang dibuat setelah jam 17:00 akan diproses pada hari kerja berikutnya.
          </small>
        </div>

        <form @submit.prevent="kirimPermintaan">
          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Nama Lengkap</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input
                v-model="namaLengkap"
                type="text"
                class="form-control fs-5"
                placeholder="Masukkan nama lengkap Anda"
                required
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Nomor Telepon</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
              <input
                v-model="nomorTelepon"
                type="text"
                class="form-control fs-5"
                placeholder="Masukkan nomor telepon Anda"
                required
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Alamat Penjemputan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
              <textarea
                v-model="alamatPenjemputan"
                class="form-control fs-5"
                rows="3"
                placeholder="Isi alamat lengkap"
                required
              ></textarea>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Waktu Penjemputan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-clock"></i></span>
              <input
                v-model="waktuPenjemputan"
                type="datetime-local"
                class="form-control fs-5"
                required
              />
            </div>
            <small class="text-muted fs-6">
              Maksimal jam 17:00 akan diproses hari itu, lewat akan diproses besok
            </small>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Catatan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
              <textarea
                v-model="catatan"
                class="form-control fs-5"
                rows="3"
                placeholder="Tambahkan catatan jika ada"
              ></textarea>
            </div>
          </div>

          <button type="submit" class="btn btn-info text-white w-100 fs-5 py-3 fw-semibold">
            <i class="fas fa-paper-plane me-2"></i> Kirim Permintaan
          </button>
        </form>
      </div>
    </div>

    <!-- Back button -->
    <div class="mt-4 text-center">
      <router-link to="/beranda" class="btn btn-outline-info btn-lg px-4">
        ⬅️ Kembali ke Beranda
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import axios from "@/libs/axios";
import { ref, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import Swal from "sweetalert2";


const route = useRoute();
const router = useRouter();
const currentRoute = computed(() => route.path);

// Form data
const namaLengkap = ref("");
const nomorTelepon = ref("");
const alamatPenjemputan = ref("");
const waktuPenjemputan = ref("");
const catatan = ref("");

// Status login (misalnya nanti cek dari store/auth middleware)
const isLoggedIn = ref(false);

const kirimPermintaan = async () => {
  if (!isLoggedIn.value) {
  Swal.fire({
    icon: "warning",
    title: "Login Diperlukan",
    text: "Anda harus login terlebih dahulu untuk menggunakan layanan penjemputan.",
    confirmButtonText: "Login Sekarang",
  }).then((result) => {
    if (result.isConfirmed) {
      router.push("/sign-in");
    }
  });
  return;
}

  // if (!isLoggedIn.value) {
  //   router.push("/sign-in"); // langsung redirect ke login page
  //   return;
  // }

  try {
    await axios.post("/penjemputan", {
      nama_pelanggan: namaLengkap.value,
      no_hp: nomorTelepon.value,
      alamat_jemput: alamatPenjemputan.value,
      waktu_jemput: waktuPenjemputan.value,
      catatan: catatan.value,
    });

    alert("Permintaan penjemputan berhasil dikirim!");
    // reset form
    namaLengkap.value = "";
    nomorTelepon.value = "";
    alamatPenjemputan.value = "";
    waktuPenjemputan.value = "";
    catatan.value = "";
  } catch (err) {
    alert("Gagal mengirim permintaan, silakan coba lagi.");
  }
};
</script>

<style scoped>
.pelanggan-area {
  background-color: #e6f7fa;
  padding-top: 80px;
  padding-bottom: 60px;
}
</style>
