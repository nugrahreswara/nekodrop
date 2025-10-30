@extends('layouts.app')

@section('title', 'Dukung NekoDrop - Donasi')

@push('styles')
<style>
    .donation-section {
        background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 50%, #fed7aa 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .donation-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 
            0 8px 32px rgba(249, 115, 22, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.6);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .gradient-border {
        position: relative;
        background: linear-gradient(135deg, #f97316, #ea580c, #dc2626);
        padding: 2px;
        border-radius: 24px;
    }
    
    .gradient-border::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #f97316, #ea580c, #dc2626);
        border-radius: 26px;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .gradient-border:hover::before {
        opacity: 1;
    }
    
    .pulse-animation {
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(249, 115, 22, 0);
        }
    }
    
    .float-animation {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        33% {
            transform: translateY(-15px) rotate(2deg);
        }
        66% {
            transform: translateY(-8px) rotate(-2deg);
        }
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #dc2626 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
    
    .qr-container {
        background: white;
        border-radius: 20px;
        padding: 1rem;
        box-shadow: 
            0 10px 25px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        position: relative;
        overflow: hidden;
    }
    
    
    .donation-method {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: linear-gradient(white, white) padding-box,
                   linear-gradient(135deg, #f97316, #ea580c) border-box;
    }
    
    .donation-method:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(249, 115, 22, 0.15);
    }
    
    .benefit-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .benefit-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
        border-radius: 20px;
    }
    
    .heart-beat {
        animation: heartBeat 1.5s ease-in-out infinite;
    }
    
    @keyframes heartBeat {
        0%, 100% {
            transform: scale(1);
        }
        25% {
            transform: scale(1.1);
        }
        50% {
            transform: scale(1);
        }
        75% {
            transform: scale(1.05);
        }
    }
    
    .section-title {
        position: relative;
        display: inline-block;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #f97316, #ea580c);
        border-radius: 2px;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .donation-section {
            padding: 1rem 0;
        }
        
        .donation-card {
            margin: 0.5rem;
            padding: 1.5rem;
        }
        
        .benefit-icon {
            width: 60px;
            height: 60px;
        }
        
        .qr-container {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="donation-section">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <div class="flex items-center justify-center gap-4 mb-8">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-4xl font-black text-gray-900 mb-6">
                    Dukung Pengembangan <span class="gradient-text">NekoDrop</span>!
                </h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Bantu kami terus menghadirkan layanan download terbaik dengan dukungan Anda. 
                    Setiap kontribusi berarti untuk perkembangan NekoDrop.
                </p>
            </div>
        </div>

        <!-- Donation Methods Grid -->
        <div class="grid lg:grid-cols-2 gap-8 mb-16">
            <!-- QR Code Card -->
            <div class="donation-card rounded-3xl p-8">
                <div class="text-center">
                    <div class="inline-block mb-6">
                        <div class="w-16 h-16 bg-white flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-qrcode text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 section-title">QR Code</h2>
                    </div>
                    
                    <p class="text-gray-600 font-medium mb-8 text-lg">
                        Scan Untuk Menuju Link Saweria
                    </p>
                    
                    <!-- QR Code Container -->
                    <div class="qr-container inline-block mb-6">
                        <img src="https://ik.imagekit.io/hdxn6kcob/Screenshot%20from%202025-10-30%2015-23-31.png?updatedAt=1761809034896"
                             alt="QR Code Donasi Saweria"
                             class="w-64 h-64 object-cover rounded-lg">
                    </div>
                    
                    <p class="text-sm text-gray-500 mt-4">
                        <!-- Support langsung melalui QRIS atau aplikasi bank -->
                    </p>
                </div>
            </div>

            <!-- Alternative Methods Card -->
            <div class="donation-card rounded-3xl p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-heart text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 section-title">Platform Donasi</h2>
                </div>
                
                <p class="text-gray-600 font-medium mb-8 text-center text-lg">
                    Pilih metode donasi yang paling nyaman untuk Anda
                </p>
                
                <div class="space-y-4">
                    <!-- Saweria -->
                    <a href="https://saweria.co/meowzheta" target="_blank" 
                       class="donation-method block w-full rounded-2xl p-6 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <i class="fas fa-coffee text-white text-xl"></i>
                            </div>
                            <div class="text-left flex-1">
                                <p class="font-bold text-lg text-gray-900 mb-1">Saweria</p>
                                <p class="text-sm text-gray-600">Traktir kopi untuk tim developer ☕</p>
                            </div>
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                <i class="fas fa-arrow-right text-orange-500 text-sm"></i>
                            </div>
                        </div>
                    </a>

                    <!-- Ko-fi -->
                    <a
                       class="donation-method block w-full rounded-2xl p-6 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <i class="fas fa-mug-hot text-white text-xl"></i>
                            </div>
                            <div class="text-left flex-1">
                                <p class="font-bold text-lg text-gray-900 mb-1">Coming Soon!!</p>
                                <p class="text-sm text-gray-600">-</p>
                            </div>
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-arrow-right text-blue-500 text-sm"></i>
                            </div>
                        </div>
                    </a>

                    <!-- PayPal -->
                    <a  
                       class="donation-method block w-full rounded-2xl p-6 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <i class="fab fa-paypal text-white text-xl"></i>
                            </div>
                            <div class="text-left flex-1">
                                <p class="font-bold text-lg text-gray-900 mb-1">Coming Soon</p>
                                <p class="text-sm text-gray-600">-</p>
                            </div>
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-arrow-right text-indigo-500 text-sm"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="donation-card rounded-3xl p-8 mb-12">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-black text-gray-900 section-title inline-block">
                    Mengapa Donasi Anda Penting? 
                </h3>
                <p class="text-gray-600 mt-4 text-lg max-w-2xl mx-auto">
                    Setiap kontribusi membantu kami menjaga dan meningkatkan kualitas layanan NekoDrop
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Server & Hosting -->
                <div class="text-center group">
                    <div class="benefit-icon bg-gradient-to-br from-green-500 to-green-600 group-hover:scale-110 transition-transform">
                        <i class="fas fa-server text-black text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-xl text-gray-900 mb-3">Infrastruktur Server</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Biaya server dan hosting untuk memastikan layanan tetap cepat, stabil, dan tersedia 24/7
                    </p>
                </div>
                
                <!-- Development -->
                <div class="text-center group">
                    <div class="benefit-icon bg-gradient-to-br from-blue-500 to-blue-600 group-hover:scale-110 transition-transform">
                        <i class="fas fa-code text-black text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-xl text-gray-900 mb-3">Pengembangan Fitur</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Pengembangan fitur baru, perbaikan bug, dan peningkatan performa aplikasi secara berkala
                    </p>
                </div>
                
                <!-- Free Service -->
                <div class="text-center group">
                    <div class="benefit-icon bg-gradient-to-br from-purple-500 to-purple-600 group-hover:scale-110 transition-transform">
                        <i class="fas fa-heart text-black text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-xl text-gray-900 mb-3">Layanan Gratis</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Memastikan NekoDrop tetap gratis dan dapat diakses oleh semua pengguna tanpa batasan
                    </p>
                </div>
            </div>
        </div>

        <!-- Thank You Section -->
        <div class="donation-card rounded-3xl p-12 text-center relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-0 left-0 w-32 h-32 bg-orange-500 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-48 h-48 bg-red-500 rounded-full translate-x-1/2 translate-y-1/2"></div>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-gray-900 mb-6">
                    Terima Kasih Atas Dukungannya! 
                </h3>
                
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed mb-8">
                    Setiap donasi, sekecil apapun, sangat berarti bagi kami dan akan digunakan sebaik-baiknya 
                    untuk membuat NekoDrop semakin berkembang. Komunitas seperti Anda yang membuat kami terus semangat! 
                    <span class="text-orange-500 font-semibold"></span>
                </p>
                
                <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        Laporan penggunaan dana transparan
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        Update perkembangan berkala
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        Prioritas request fitur
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection