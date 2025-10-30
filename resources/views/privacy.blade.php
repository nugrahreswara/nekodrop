@extends('layouts.app')

@section('title', 'Kebijakan Privasi - K-OnDownloader')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header with Icon -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-shield-alt text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">Kebijakan Privasi</h1>
            <p class="text-xl text-gray-600">Pelindung Data Anda di NekoDrop Media Downloader</p>
            <p class="text-gray-500 mt-2">Terakhir diperbarui: 28 Oktober 2025</p>
        </div>

        <!-- Table of Contents -->
        <div class="futuristic-card mb-12 p-6">
            <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-list text-blue-500"></i>
                Daftar Isi
            </h3>
            <nav class="space-y-2">
                <a href="#section-1" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">1</span>
                    <span>Informasi yang Kami Kumpulkan</span>
                </a>
                <a href="#section-2" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">2</span>
                    <span>Bagaimana Kami Menggunakan Informasi Anda</span>
                </a>
                <a href="#section-3" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">3</span>
                    <span>Berbagi Informasi</span>
                </a>
                <a href="#section-4" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">4</span>
                    <span>Keamanan Data</span>
                </a>
                <a href="#section-5" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">5</span>
                    <span>Hak Anda</span>
                </a>
                <a href="#section-6" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">6</span>
                    <span>Data Anak-Anak</span>
                </a>
                <a href="#section-7" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">7</span>
                    <span>Perubahan Kebijakan</span>
                </a>
                <a href="#section-8" class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50">
                    <span class="w-6 text-right text-sm font-medium">8</span>
                    <span>Kontak Kami</span>
                </a>
            </nav>
        </div>

        <!-- Introduction -->
        <div class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Selamat datang di <strong>NekoDrop Media Downloader</strong> ("Kami", "Kita", atau "NekoDrop"). Kami berkomitmen untuk melindungi privasi Anda dan memastikan bahwa informasi pribadi Anda ditangani dengan aman dan bertanggung jawab. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, membagikan, dan melindungi informasi Anda saat Anda menggunakan layanan kami.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 1 -->
        <section id="section-1" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-database text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Informasi yang Kami Kumpulkan</h2>
                    <p class="text-gray-700 mb-6">
                        Kami mengumpulkan informasi berikut untuk menyediakan layanan terbaik:
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-user text-green-500"></i>
                                Informasi yang Anda Berikan
                            </h4>
                            <p class="text-gray-600 text-sm">Saat Anda menggunakan layanan download, kami mungkin mengumpulkan URL video yang Anda masukkan. Kami tidak mengumpulkan informasi pribadi seperti nama, email, atau alamat kecuali Anda secara sukarela memberikan informasi tersebut melalui formulir kontak atau fitur lainnya.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-chart-line text-green-500"></i>
                                Informasi Penggunaan
                            </h4>
                            <p class="text-gray-600 text-sm">Kami mengumpulkan data non-pribadi seperti jenis perangkat, browser, IP address (untuk keamanan dan analisis), dan pola penggunaan layanan untuk meningkatkan performa.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-cookie-bite text-green-500"></i>
                                Cookies dan Teknologi Serupa
                            </h4>
                            <p class="text-gray-600 text-sm">Kami menggunakan cookies untuk menyimpan preferensi Anda, melacak sesi, dan analisis penggunaan. Anda dapat mengelola cookies melalui pengaturan browser Anda.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-server text-green-500"></i>
                                Log Server
                            </h4>
                            <p class="text-gray-600 text-sm">Kami mencatat akses ke server untuk keamanan, termasuk timestamp, IP address, dan halaman yang diakses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2 -->
        <section id="section-2" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cogs text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Bagaimana Kami Menggunakan Informasi Anda</h2>
                    <p class="text-gray-700 mb-6">
                        Informasi yang dikumpulkan digunakan untuk tujuan berikut:
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 p-4 bg-orange-50 rounded-lg">
                            <i class="fas fa-check-circle text-orange-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Menyediakan dan meningkatkan layanan download video.</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-4 bg-orange-50 rounded-lg">
                            <i class="fas fa-check-circle text-orange-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Menganalisis penggunaan untuk optimasi situs.</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-4 bg-orange-50 rounded-lg">
                            <i class="fas fa-check-circle text-orange-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Mencegah penyalahgunaan dan memastikan keamanan.</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-4 bg-orange-50 rounded-lg">
                            <i class="fas fa-check-circle text-orange-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Mengirim pembaruan atau notifikasi terkait layanan (jika Anda berlangganan).</strong>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-4 bg-orange-50 rounded-lg">
                            <i class="fas fa-check-circle text-orange-500 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Mematuhi kewajiban hukum.</strong>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <p class="text-gray-700 font-semibold">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                            Kami tidak menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3 -->
        <section id="section-3" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-share-alt text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Berbagi Informasi</h2>
                    <p class="text-gray-700 mb-6">
                        Kami mungkin membagikan informasi dengan pihak ketiga terbatas:
                    </p>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <i class="fas fa-cloud text-purple-500 text-3xl mb-3"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Penyedia Layanan</h4>
                            <p class="text-gray-600 text-sm">Pihak ketiga tepercaya seperti hosting dan analitik tools (misalnya, Google Analytics) yang terikat kontrak kerahasiaan.</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <i class="fas fa-gavel text-purple-500 text-3xl mb-3"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Persyaratan Hukum</h4>
                            <p class="text-gray-600 text-sm">Jika diwajibkan oleh hukum atau untuk melindungi hak kami.</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <i class="fas fa-building text-purple-500 text-3xl mb-3"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Merger atau Akuisisi</h4>
                            <p class="text-gray-600 text-sm">Jika bisnis kami diakuisisi, informasi mungkin ditransfer sebagai aset.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4 -->
        <section id="section-4" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lock text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Keamanan Data</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Kami menerapkan langkah keamanan standar industri untuk melindungi informasi Anda, termasuk enkripsi SSL/TLS, kontrol akses berbasis peran, dan pemantauan keamanan 24/7. Namun, tidak ada sistem yang 100% aman, jadi kami tidak dapat menjamin keamanan absolut terhadap ancaman eksternal.
                    </p>
                    <div class="mt-6 grid md:grid-cols-2 gap-6">
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-red-800 mb-2">Fitur Keamanan</h4>
                            <ul class="text-sm text-red-700 space-y-1">
                                <li>• Enkripsi data saat transit dan istirahat</li>
                                <li>• Firewall dan deteksi intrusi</li>
                                <li>• Audit keamanan rutin</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-red-800 mb-2">Tanggung Jawab Pengguna</h4>
                            <p class="text-sm text-red-700">Gunakan kata sandi kuat dan jangan bagikan informasi login Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 5 -->
        <section id="section-5" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-check text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Hak Anda</h2>
                    <p class="text-gray-700 mb-6">
                        Sebagai pengguna, Anda memiliki hak-hak berikut terkait data pribadi Anda:
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-indigo-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-eye text-indigo-500"></i>
                                Akses Data
                            </h4>
                            <p class="text-sm text-indigo-700">Minta salinan data pribadi yang kami simpan tentang Anda.</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-indigo-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-edit text-indigo-500"></i>
                                Perbarui Data
                            </h4>
                            <p class="text-sm text-indigo-700">Perbarui atau koreksi informasi yang tidak akurat.</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-indigo-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-trash text-indigo-500"></i>
                                Hapus Data
                            </h4>
                            <p class="text-sm text-indigo-700">Minta penghapusan data Anda (subjek pada kewajiban hukum).</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-indigo-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-ban text-indigo-500"></i>
                                Opt-Out
                            </h4>
                            <p class="text-sm text-indigo-700">Opt-out dari cookies atau komunikasi pemasaran.</p>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-blue-800 font-semibold">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>
                            Hubungi kami di <a href="mailto:privacy@nekodrop.com" class="underline">privacy@nekodrop.com</a> untuk permintaan terkait data.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 6 -->
        <section id="section-6" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-child text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Data Anak-Anak</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Layanan kami tidak ditujukan untuk anak di bawah 13 tahun sesuai dengan COPPA (Children's Online Privacy Protection Act) dan regulasi serupa di Indonesia. Jika kami mengetahui pengumpulan data anak tanpa persetujuan orang tua, kami akan menghapusnya segera dan memberitahu orang tua jika memungkinkan.
                    </p>
                    <div class="mt-6 p-4 bg-pink-50 rounded-lg">
                        <h4 class="font-semibold text-pink-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-pink-500"></i>
                            Jika Anda Orang Tua
                        </h4>
                        <p class="text-pink-700 text-sm">Hubungi kami jika Anda percaya anak Anda telah memberikan informasi pribadi tanpa persetujuan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 7 -->
        <section id="section-7" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-edit text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Perubahan Kebijakan</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu untuk mencerminkan perubahan dalam praktik kami atau regulasi hukum. Perubahan signifikan akan diumumkan melalui email atau notifikasi di situs web. Perubahan akan diposting di halaman ini dengan tanggal efektif. Penggunaan berkelanjutan setelah perubahan berarti penerimaan terhadap ketentuan baru.
                    </p>
                    <div class="mt-6 grid md:grid-cols-2 gap-6">
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2">Cara Kami Memberitahu</h4>
                            <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                                <li>Email notifikasi untuk perubahan besar</li>
                                <li>Posting di blog atau homepage</li>
                                <li>Tanggal efektif di bagian atas dokumen</li>
                            </ul>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2">Rekomendasi</h4>
                            <p class="text-sm text-yellow-700">Periksa halaman ini secara berkala untuk pembaruan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 8 -->
        <section id="section-8" class="futuristic-card mb-12 p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-headset text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Kontak Kami</h2>
                    <p class="text-gray-700 mb-6">
                        Jika Anda memiliki pertanyaan, kekhawatiran, atau permintaan terkait Kebijakan Privasi ini, tim dukungan privasi kami siap membantu:
                    </p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-teal-50 p-6 rounded-lg text-center">
                            <i class="fas fa-envelope text-teal-500 text-3xl mb-3"></i>
                            <h4 class="font-bold text-teal-800 mb-2">Email</h4>
                            <p class="text-teal-700"><a href="mailto:privacy@nekodrop.com" class="underline hover:text-teal-600">privacy@nekodrop.com</a></p>
                            <p class="text-sm text-teal-600 mt-2">Respons dalam 48 jam</p>
                        </div>
                        <div class="bg-teal-50 p-6 rounded-lg text-center">
                            <i class="fas fa-map-marker-alt text-teal-500 text-3xl mb-3"></i>
                            <h4 class="font-bold text-teal-800 mb-2">Alamat</h4>
                            <p class="text-teal-700">NekoDrop Media Downloader<br>Jl. Teknologi No. 123<br>Jakarta, Indonesia 12190</p>
                            <p class="text-sm text-teal-600 mt-2">Senin - Jumat: 09:00 - 18:00 WIB</p>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">Otoritas Pengawas</h4>
                        <p class="text-sm text-gray-600">Untuk keluhan privasi, hubungi Kementerian Komunikasi dan Informatika Republik Indonesia.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Disclaimer -->
        <div class="futuristic-card p-8 border-t-4 border-orange-500">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-orange-500 text-3xl mb-4"></i>
                <p class="text-sm text-gray-500 mb-4">
                    <em>Catatan Penting: Dokumen ini adalah template placeholder dan bukan nasihat hukum profesional. Konsultasikan dengan pengacara atau ahli hukum untuk menyesuaikan kebijakan dengan persyaratan hukum Indonesia yang berlaku.</em>
                </p>
                <p class="text-xs text-gray-400">
                    NekoDrop Media Downloader © 2025. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
