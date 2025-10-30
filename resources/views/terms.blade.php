@extends('layouts.app')

@section('title', 'Syarat dan Ketentuan - K-OnDownloader')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header with Icon -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-contract text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-4">Syarat dan Ketentuan</h1>
            <p class="text-xl text-gray-600">Ketentuan Penggunaan NekoDrop Media Downloader</p>
            <p class="text-gray-500 mt-2">Terakhir diperbarui: 28 Oktober 2025</p>
        </div>

        <!-- Table of Contents -->
        <div class="futuristic-card mb-12 p-6">
            <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-list text-green-500"></i>
                Daftar Isi
            </h3>
            <nav class="space-y-2">
                <a href="#section-1" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">1</span>
                    <span>Deskripsi Layanan</span>
                </a>
                <a href="#section-2" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">2</span>
                    <span>Kelayakan Penggunaan</span>
                </a>
                <a href="#section-3" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">3</span>
                    <span>Akun Pengguna</span>
                </a>
                <a href="#section-4" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">4</span>
                    <span>Penggunaan Layanan</span>
                </a>
                <a href="#section-5" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">5</span>
                    <span>Kekayaan Intelektual</span>
                </a>
                <a href="#section-6" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">6</span>
                    <span>Pembatasan Tanggung Jawab</span>
                </a>
                <a href="#section-7" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">7</span>
                    <span>Terminasi</span>
                </a>
                <a href="#section-8" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">8</span>
                    <span>Hukum yang Berlaku</span>
                </a>
                <a href="#section-9" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">9</span>
                    <span>Perubahan Syarat</span>
                </a>
                <a href="#section-10" class="flex items-center gap-3 text-gray-700 hover:text-green-600 transition-colors p-2 rounded-lg hover:bg-green-50">
                    <span class="w-6 text-right text-sm font-medium">10</span>
                    <span>Kontak Kami</span>
                </a>
            </nav>
        </div>

        <!-- Introduction -->
        <div class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-handshake text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Penerimaan Syarat</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Selamat datang di <strong>NekoDrop Media Downloader</strong>. Dengan mengakses atau menggunakan layanan kami ("Layanan"), Anda setuju untuk terikat oleh Syarat dan Ketentuan Penggunaan ini ("Syarat"). Jika Anda tidak setuju dengan ketentuan apa pun, jangan gunakan Layanan kami. Penggunaan berkelanjutan berarti penerimaan penuh terhadap syarat ini.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 1 -->
        <section id="section-1" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-play-circle text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Deskripsi Layanan</h2>
                    <p class="text-gray-700 mb-6">
                        NekoDrop Media Downloader adalah platform web inovatif yang memungkinkan pengguna untuk mendownload video dan audio dari berbagai platform media sosial dan streaming seperti YouTube, Instagram, TikTok, Facebook, dan lainnya. Layanan ini dirancang untuk kemudahan penggunaan, kecepatan, dan keandalan.
                    </p>
                    <div class="bg-indigo-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-indigo-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-indigo-500"></i>
                            Fitur Utama
                        </h4>
                        <ul class="text-indigo-700 space-y-2 text-sm list-disc list-inside">
                            <li>Download video berkualitas tinggi dari multiple platform</li>
                            <li>Konversi audio dari video</li>
                            <li>Riwayat download dan manajemen file</li>
                            <li>Antarmuka yang ramah pengguna</li>
                        </ul>
                    </div>
                    <p class="text-gray-600 mt-4 text-sm italic">
                        Layanan ini disediakan "sebagaimana adanya" tanpa jaminan ketersediaan 100% atau performa tertentu.
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 2 -->
        <section id="section-2" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-shield text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Kelayakan Penggunaan</h2>
                    <p class="text-gray-700 mb-6">
                        Untuk menggunakan Layanan, Anda harus memenuhi persyaratan berikut:
                    </p>
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-orange-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-id-card text-orange-500"></i>
                                Usia Minimum
                            </h4>
                            <p class="text-orange-700 text-sm">Anda harus berusia minimal 18 tahun atau memiliki persetujuan tertulis dari orang tua/wali hukum jika di bawah umur.</p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-orange-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-globe text-orange-500"></i>
                                Kepatuhan Hukum
                            </h4>
                            <p class="text-orange-700 text-sm">Anda bertanggung jawab penuh atas kepatuhan terhadap hukum setempat terkait download, distribusi, dan penggunaan konten digital.</p>
                        </div>
                    </div>
                    <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                        <p class="text-red-800 font-semibold">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            Pelanggaran kelayakan dapat mengakibatkan penangguhan akun atau akses permanen.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3 -->
        <section id="section-3" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-lock text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Akun Pengguna</h2>
                    <p class="text-gray-700 mb-6">
                        Saat ini, Layanan NekoDrop Media Downloader tidak memerlukan pembuatan akun untuk penggunaan dasar. Namun, fitur premium atau manajemen lanjutan mungkin memerlukan registrasi.
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-key text-blue-500"></i>
                                Keamanan Akun
                            </h4>
                            <p class="text-blue-700 text-sm">Jika diperlukan registrasi di masa depan, Anda bertanggung jawab menjaga kerahasiaan kredensial login Anda. Jangan bagikan password dengan pihak lain.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-phone text-blue-500"></i>
                                Verifikasi
                            </h4>
                            <p class="text-blue-700 text-sm">Kami dapat meminta verifikasi identitas untuk mencegah penyalahgunaan akun.</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mt-4 text-sm">
                        Anda setuju untuk memberitahu kami segera jika ada penggunaan tidak sah pada akun Anda.
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 4 -->
        <section id="section-4" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-download text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Penggunaan Layanan</h2>
                    <p class="text-gray-700 mb-6">
                        Anda diberikan hak terbatas untuk menggunakan Layanan sesuai ketentuan berikut:
                    </p>
                    <div class="space-y-6">
                        <div class="bg-green-50 p-6 rounded-lg border-l-4 border-green-500">
                            <h4 class="font-bold text-green-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Penggunaan yang Diizinkan
                            </h4>
                            <ul class="text-green-700 space-y-2 text-sm list-disc list-inside">
                                <li>Penggunaan pribadi dan non-komersial untuk download media legal</li>
                                <li>Penyimpanan konten untuk penggunaan pribadi sesuai hukum</li>
                                <li>Penggunaan antarmuka sesuai desain</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 p-6 rounded-lg border-l-4 border-red-500">
                            <h4 class="font-bold text-red-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-ban text-red-500"></i>
                                Penggunaan yang Dilarang
                            </h4>
                            <ul class="text-red-700 space-y-2 text-sm list-disc list-inside">
                                <li>Mendownload konten yang melanggar hak cipta tanpa izin pemilik</li>
                                <li>Penggunaan bot, scraper, atau otomatisasi tanpa izin</li>
                                <li>Mengganggu server atau layanan (DDoS, spam, dll.)</li>
                                <li>Distribusi malware atau konten ilegal melalui Layanan</li>
                                <li>Reverse engineering atau modifikasi kode Layanan</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <p class="text-yellow-800 font-semibold">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                            Kami tidak bertanggung jawab atas konten pihak ketiga yang Anda download. Pastikan Anda memiliki hak legal untuk mendownload dan menggunakan konten tersebut.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 5 -->
        <section id="section-5" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-copyright text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Kekayaan Intelektual</h2>
                    <p class="text-gray-700 mb-6">
                        Semua hak cipta, merek dagang, paten, rahasia dagang, dan kekayaan intelektual lainnya yang terkait dengan Layanan NekoDrop Media Downloader adalah milik kami, pemberi lisensi, atau mitra kami.
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-gift text-yellow-500"></i>
                                Lisensi Pengguna
                            </h4>
                            <p class="text-yellow-700 text-sm">Anda diberikan lisensi terbatas, non-eksklusif, non-transferable untuk menggunakan Layanan sesuai Syarat ini untuk tujuan pribadi.</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-times-circle text-yellow-500"></i>
                                Pembatasan Lisensi
                            </h4>
                            <p class="text-yellow-700 text-sm">Lisensi ini tidak memberikan hak untuk menyalin, modifikasi, distribusi, atau komersialisasi Layanan atau kontennya.</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mt-4 text-sm">
                        Pelanggaran kekayaan intelektual akan dikejar secara hukum.
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 6 -->
        <section id="section-6" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-balance-scale text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Pembatasan Tanggung Jawab</h2>
                    <p class="text-gray-700 mb-6">
                        Layanan disediakan "sebagaimana adanya" dan "sebagaimana tersedia" tanpa jaminan apa pun, baik tersurat maupun tersirat.
                    </p>
                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                        <h4 class="font-bold text-red-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-red-500"></i>
                            Tidak Ada Jaminan
                        </h4>
                        <ul class="text-red-700 space-y-2 text-sm list-disc list-inside">
                            <li>Ketersediaan, keandalan, atau ketepatan waktu Layanan</li>
                            <li>Akurasi, kelengkapan, atau keamanan konten</li>
                            <li>Kebebasan dari virus, malware, atau kerusakan perangkat</li>
                        </ul>
                    </div>
                    <p class="text-red-800 mt-4 font-semibold">
                        Dalam batas maksimum yang diizinkan oleh hukum, kami tidak bertanggung jawab atas kerugian tidak langsung, insidental, khusus, konsekuensial, atau ganti rugi yang timbul dari penggunaan Layanan, termasuk kerugian dari download konten ilegal atau pelanggaran hak cipta.
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 7 -->
        <section id="section-7" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-stopwatch text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Terminasi</h2>
                    <p class="text-gray-700 mb-6">
                        Kami berhak menangguhkan atau menghentikan akses Anda ke Layanan kapan saja, dengan atau tanpa pemberitahuan, jika:
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 p-4 bg-pink-50 rounded-lg border-l-4 border-pink-500">
                            <i class="fas fa-times-circle text-pink-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Pelanggaran Syarat:</strong> Anda melanggar ketentuan ini atau kebijakan kami.
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-4 bg-pink-50 rounded-lg border-l-4 border-pink-500">
                            <i class="fas fa-times-circle text-pink-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Penyalahgunaan:</strong> Penggunaan yang merugikan Layanan atau pengguna lain.
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-4 bg-pink-50 rounded-lg border-l-4 border-pink-500">
                            <i class="fas fa-times-circle text-pink-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Kewajiban Hukum:</strong> Diwajibkan oleh hukum atau otoritas.
                            </div>
                        </li>
                    </ul>
                    <p class="text-gray-600 mt-4">
                        Anda dapat berhenti menggunakan Layanan kapan saja. Setelah terminasi, kewajiban Anda tetap berlaku, termasuk pembayaran yang tertunggak.
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 8 -->
        <section id="section-8" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-gavel text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Hukum yang Berlaku</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Syarat dan Ketentuan ini diatur dan ditafsirkan sesuai dengan hukum Republik Indonesia, tanpa memandang prinsip konflik hukum. Semua sengketa yang timbul dari atau terkait dengan Syarat ini akan diselesaikan secara eksklusif di pengadilan yang kompeten di Jakarta, Indonesia.
                    </p>
                    <div class="mt-6 p-4 bg-teal-50 rounded-lg">
                        <h4 class="font-semibold text-teal-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-balance-scale text-teal-500"></i>
                            Penyelesaian Sengketa
                        </h4>
                        <p class="text-teal-700 text-sm">Kami mendorong penyelesaian damai terlebih dahulu melalui dukungan pelanggan sebelum melanjutkan ke jalur hukum.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 9 -->
        <section id="section-9" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-sync-alt text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Perubahan Syarat</h2>
                    <p class="text-gray-700 mb-6">
                        Kami berhak memperbarui atau memodifikasi Syarat dan Ketentuan ini kapan saja. Perubahan akan diposting di halaman ini dengan tanggal efektif yang jelas. Penggunaan berkelanjutan Layanan setelah perubahan berarti penerimaan penuh terhadap ketentuan baru.
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-amber-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-amber-800 mb-2">Notifikasi Perubahan</h4>
                            <ul class="text-sm text-amber-700 space-y-1 list-disc list-inside">
                                <li>Posting di halaman Syarat & Ketentuan</li>
                                <li>Email untuk perubahan signifikan</li>
                                <li>Banner notifikasi di dashboard</li>
                            </ul>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-amber-800 mb-2">Rekomendasi</h4>
                            <p class="text-sm text-amber-700">Periksa halaman ini secara berkala, terutama sebelum menggunakan fitur baru.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 10 -->
        <section id="section-10" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-envelope-open text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Kontak Kami</h2>
                    <p class="text-gray-700 mb-6">
                        Jika Anda memiliki pertanyaan, masukan, atau keluhan terkait Syarat dan Ketentuan ini, tim hukum dan dukungan kami siap membantu:
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-emerald-50 p-6 rounded-lg text-center">
                            <i class="fas fa-envelope text-emerald-500 text-3xl mb-3"></i>
                            <h4 class="font-bold text-emerald-800 mb-2">Email Hukum</h4>
                            <p class="text-emerald-700"><a href="mailto:legal@nekodrop.com" class="underline hover:text-emerald-600">legal@nekodrop.com</a></p>
                            <p class="text-sm text-emerald-600 mt-2">Untuk pertanyaan syarat & ketentuan</p>
                        </div>
                        <div class="bg-emerald-50 p-6 rounded-lg text-center">
                            <i class="fas fa-map-marker-alt text-emerald-500 text-3xl mb-3"></i>
                            <h4 class="font-bold text-emerald-800 mb-2">Alamat Perusahaan</h4>
                            <p class="text-emerald-700">NekoDrop Media Downloader<br>Jl. Teknologi No. 123<br>Jakarta, Indonesia 12190</p>
                            <p class="text-sm text-emerald-600 mt-2">Jam Operasional: Senin - Jumat 09:00 - 18:00 WIB</p>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">Dukungan Tambahan</h4>
                        <p class="text-sm text-gray-600">Untuk dukungan teknis, kunjungi Pusat Bantuan atau hubungi support@nekodrop.com.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Disclaimer -->
        <div class="futuristic-card p-8 border-t-4 border-orange-500">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-orange-500 text-3xl mb-4"></i>
                <p class="text-sm text-gray-500 mb-4">
                    <em>Catatan Penting: Dokumen ini adalah template placeholder dan bukan nasihat hukum profesional. Konsultasikan dengan pengacara atau ahli hukum untuk menyesuaikan syarat dengan persyaratan hukum Indonesia yang berlaku.</em>
                </p>
                <p class="text-xs text-gray-400">
                    NekoDrop Media Downloader © 2025. Semua hak dilindungi undang-undang.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection