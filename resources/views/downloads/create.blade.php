@extends('layouts.app')

@section('title', 'Unduh Media - NekoDrop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16 items-start">
            <!-- Left Column - Hero Content -->
            <div class="space-y-8">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-700 px-4 py-2 rounded-lg text-sm font-semibold">
                        <i class="fas fa-bolt"></i>
                        <span>Unduh 2x Lebih Cepat</span>
                    </div>
                    
                    <h2 class="text-5xl font-bold text-gray-900 leading-tight">
                        STOP RIBET<br>
                        <span class="text-orange-600">START DROP</span>
                    </h2>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Nikmati kemudahan mengunduh video dan audio dari YouTube, Instagram, TikTok, dan Facebook dengan kualitas terbaik. Gratis selamanya tanpa iklan.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-orange-300 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Kualitas 4K</div>
                                <div class="text-xs text-gray-500">HD & Ultra HD</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-orange-300 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-bolt text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Super Cepat</div>
                                <div class="text-xs text-gray-500">Instan Download</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-orange-300 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shield-alt text-purple-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Aman</div>
                                <div class="text-xs text-gray-500">100% Secure</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-orange-300 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-infinity text-orange-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Gratis</div>
                                <div class="text-xs text-gray-500">No Hidden Fees</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Download Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Download Sekarang</h3>
                    <p class="text-gray-600 text-sm">Paste URL dan mulai download</p>
                </div>

                <form id="downloadForm" method="POST" action="{{ route('downloads.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="url" class="block text-sm font-semibold text-gray-700 mb-2">
                            URL Konten
                        </label>
                        <div class="relative">
                            <input type="url"
                                   id="url"
                                   name="url"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all"
                                   placeholder="https://youtube.com/watch?v=..."
                                   required>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Video Preview -->
                    <div id="videoInfo" class="hidden opacity-0 transition-all duration-300">
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <div class="flex gap-4">
                                <div id="videoThumbnail" class="w-32 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3">
                                        <div id="platformBadge" class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0 border border-gray-200">
                                            <i class="fas fa-video text-gray-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 id="videoTitle" class="font-semibold text-gray-900 text-sm line-clamp-2 mb-2">
                                                Mendeteksi video...
                                            </h4>
                                            <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                                                <span id="videoUploader" class="flex items-center gap-1">
                                                    <i class="fas fa-user"></i>
                                                    <span>Tidak Diketahui</span>
                                                </span>
                                                <span id="videoDuration" class="flex items-center gap-1">
                                                    <i class="fas fa-clock"></i>
                                                    <span>--:--</span>
                                                </span>
                                                <span id="videoViews" class="flex items-center gap-1 hidden">
                                                    <i class="fas fa-eye"></i>
                                                    <span>0</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Options -->
                    <div id="downloadOptions" class="hidden opacity-0 transition-all duration-300 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div id="qualitySection" class="hidden">
                                <label for="quality" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kualitas Video
                                </label>
                                <select id="quality"
                                        name="quality"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">
                                    <option value="best">Kualitas Terbaik</option>
                                    <option value="1080p">1080p Full HD</option>
                                    <option value="720p">720p HD</option>
                                    <option value="480p">480p SD</option>
                                    <option value="360p">360p</option>
                                    <option value="240p">240p</option>
                                </select>
                            </div>

                            <div id="formatSection" class="hidden">
                                <label for="format" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Format File
                                </label>
                                <select id="format"
                                        name="format"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">
                                    <option value="mp4">MP4 Video</option>
                                    <option value="mp3">MP3 Audio</option>
                                    <option value="m4a">M4A Audio</option>
                                    <option value="wav">WAV Audio</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" id="downloadBtn" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            Download Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Platform yang didukung:</p>
                        <div class="flex items-center gap-3">
                            <i class="fab fa-youtube text-red-500"></i>
                            <i class="fab fa-instagram text-pink-500"></i>
                            <i class="fab fa-tiktok text-gray-900"></i>
                            <i class="fab fa-facebook text-blue-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <section class="mb-16">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div class="order-1 lg:order-1">
                        <img src="https://ik.imagekit.io/hdxn6kcob/cat.png?updatedAt=1761654620477" alt="NekoDrop" class="w-full max-w-sm mx-auto rounded-xl shadow-sm">
                    </div>
                    <div class="order-2 lg:order-2">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Tentang NekoDrop</h3>
                        <div class="space-y-4 text-gray-600">
                            <p>NekoDrop adalah solusi modern untuk kebutuhan download media sosial. Kami memahami betapa pentingnya konten digital dalam kehidupan sehari-hari, dan hadir untuk mempermudah akses Anda terhadap konten favorit.</p>
                            <p>Dengan teknologi terkini dan interface yang intuitif, kami berkomitmen menyediakan pengalaman download yang cepat, aman, dan tanpa kompromi pada kualitas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Platform Support -->
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Platform yang Didukung</h2>
                <p class="text-gray-600">Download dari berbagai platform media sosial populer</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-6 text-center border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-youtube text-red-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">YouTube</h4>
                    <p class="text-xs text-gray-500 mt-1">Video & Audio</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-instagram text-pink-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">Instagram</h4>
                    <p class="text-xs text-gray-500 mt-1">Reels & Post</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-tiktok text-gray-900 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">TikTok</h4>
                    <p class="text-xs text-gray-500 mt-1">Video & Musik</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-facebook text-blue-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">Facebook</h4>
                    <p class="text-xs text-gray-500 mt-1">Video & Reels</p>
                </div>
            </div>
        </section>

        <!-- Statistics -->
        <section class="mb-16">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div id="totalUsers" class="text-3xl font-bold text-orange-600 mb-1"></div>
                        <div class="text-sm text-gray-600">Pengunjung Aktif</div>
                    </div>
                    <div>
                        <div id="totalDownloads" class="text-3xl font-bold text-orange-600 mb-1">2M+</div>
                        <div class="text-sm text-gray-600">Total Download</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-orange-600 mb-1">99.5%</div>
                        <div class="text-sm text-gray-600">Uptime</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-orange-600 mb-1">24/7</div>
                        <div class="text-sm text-gray-600">Support</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Cara Kerja</h2>
                <p class="text-gray-600">3 langkah mudah untuk download media</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold">
                        1
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Paste URL</h4>
                    <p class="text-sm text-gray-600">Salin URL video dari platform</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold">
                        2
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Pilih Format</h4>
                    <p class="text-sm text-gray-600">Pilih kualitas dan format</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold">
                        3
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Download</h4>
                    <p class="text-sm text-gray-600">Klik download dan selesai</p>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="mb-16">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">Tim Pengembang</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center p-6 bg-gray-50 rounded-xl">
                        <img src="https://ik.imagekit.io/hdxn6kcob/Screenshot%20from%202025-10-28%2017-09-32.png?updatedAt=1761642587185" alt="Developer" class="w-full h-50 border border-black mx-auto mb-4 object-cover">
                        <h4 class="font-semibold text-gray-900 mb-1">@meowzheta</h4>
                        <p class="text-sm text-gray-600 mb-2">Fullstack Developer</p>
                        <p class="text-xs text-gray-500">Pengembangan & Maintenance Platform</p>
                    </div>

                    <div class="text-center p-6 bg-gray-50 rounded-xl">
                        <img src="https://ik.imagekit.io/hdxn6kcob/Screenshot%20from%202025-10-28%2016-47-17.png?updatedAt=1761642508504" alt="Developer" class="w-full h-50 border border-black mx-auto mb-4 object-cover">
                        <h4 class="font-semibold text-gray-900 mb-1">@nugrahreswara</h4>
                        <p class="text-sm text-gray-600 mb-2">Network Administrator</p>
                        <p class="text-xs text-gray-500">Manajemen Server & Jaringan</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-3"></div>
@endsection

@push('styles')
<style>
/* Professional base styles */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f3f4f6;
}

::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Text selection */
::selection {
    background: rgba(251, 146, 60, 0.2);
}

/* Focus states */
.focus-ring:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.1);
}

/* Smooth transitions */
.transition-smooth {
    transition: all 0.2s ease;
}
</style>
@endpush

@push('scripts')
<script>
const storageUrl = '{{ asset('storage/') }}';

// Stats update
function updateStats() {
    fetch('{{ route("stats") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const totalUsersElement = document.getElementById('totalUsers');
            if (totalUsersElement) {
                totalUsersElement.textContent = formatNumber(data.total_users);
            }

            const totalDownloadsElement = document.getElementById('totalDownloads');
            if (totalDownloadsElement) {
                totalDownloadsElement.textContent = formatNumber(data.total_downloads);
            }
        }
    })
    .catch(error => {
        console.warn('Failed to update stats:', error);
    });
}

function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}

// Update stats every 30 seconds
setInterval(updateStats, 30000);

// Initial update
document.addEventListener('DOMContentLoaded', function() {
    updateStats();
});
</script>
<script>
// Main functionality
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const downloadForm = document.getElementById('downloadForm');
    const urlInput = document.getElementById('url');
    const videoInfo = document.getElementById('videoInfo');
    const downloadOptions = document.getElementById('downloadOptions');
    const qualitySection = document.getElementById('qualitySection');
    const formatSection = document.getElementById('formatSection');
    const qualitySelect = document.getElementById('quality');
    const formatSelect = document.getElementById('format');
    const downloadBtn = document.getElementById('downloadBtn');

    // Video info elements
    const videoThumbnail = document.getElementById('videoThumbnail');
    const platformBadge = document.getElementById('platformBadge');
    const videoTitle = document.getElementById('videoTitle');
    const videoUploader = document.getElementById('videoUploader');
    const videoDuration = document.getElementById('videoDuration');
    const videoViews = document.getElementById('videoViews');

    let videoDetected = false;
    const videoInfoCache = new Map();

    // URL input handler
    urlInput.addEventListener('input', debounce(function() {
        const url = this.value.trim();
        if (url && isValidUrl(url)) {
            detectVideo(url);
        } else {
            hideVideoInfo();
        }
    }, 300));

    urlInput.addEventListener('blur', function() {
        const url = this.value.trim();
        if (url && isValidUrl(url)) {
            detectVideo(url);
        }
    });

    // Form submission
    if (downloadForm) {
        downloadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!videoDetected) {
                showToast('Silakan masukkan URL video yang valid', 'error');
                return;
            }

            const formData = new FormData(this);
            const originalText = downloadBtn.innerHTML;

            downloadBtn.disabled = true;
            downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengunduh...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Download dimulai!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("downloads.show", ":id") }}'.replace(':id', data.download.id);
                    }, 1000);
                } else {
                    showToast(data.message || 'Gagal memulai download', 'error');
                }
            })
            .catch(error => {
                console.error('Download error:', error);
                showToast('Terjadi kesalahan saat memulai download', 'error');
            })
            .finally(() => {
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = originalText;
            });
        });
    }

    // Format change handler
    formatSelect.addEventListener('change', function() {
        const format = this.value;
        const audioFormats = ['mp3', 'm4a', 'wav'];

        if (audioFormats.includes(format)) {
            qualitySection.classList.add('hidden');
        } else {
            qualitySection.classList.remove('hidden');
        }
    });

    // Detect video function
    function detectVideo(url) {
        const cacheKey = `${url}`;
        if (videoInfoCache.has(cacheKey)) {
            const cachedData = videoInfoCache.get(cacheKey);
            updateVideoInfo(cachedData.video_info, cachedData.platform);
            if (cachedData.platform === 'youtube') {
                populateQualityOptions(cachedData.qualityOptions);
                showDownloadOptions(true);
            } else {
                qualitySection.classList.add('hidden');
                showDownloadOptions(false);
            }
            videoDetected = true;
            return;
        }

        showVideoInfoLoading();

        const platform = detectPlatform(url);
        if (!platform) {
            hideVideoInfo();
            showToast('Platform tidak didukung', 'error');
            return;
        }

        updatePlatformBadge(platform);

        fetch('{{ route("downloads.video-info") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                url: url,
                platform: platform
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.video_info) {
                updateVideoInfo(data.video_info, platform);

                if (platform === 'youtube') {
                    fetchAvailableFormats(url, platform)
                        .then(qualityOptions => {
                            populateQualityOptions(qualityOptions);
                            showDownloadOptions(true);
                            videoDetected = true;
                            videoInfoCache.set(cacheKey, {
                                video_info: data.video_info,
                                platform: platform,
                                qualityOptions: qualityOptions
                            });
                        })
                        .catch(error => {
                            const defaultOptions = {
                                'best': 'Kualitas Terbaik',
                                '1080p': '1080p Full HD',
                                '720p': '720p HD',
                                '480p': '480p SD',
                                '360p': '360p',
                                '240p': '240p'
                            };
                            populateQualityOptions(defaultOptions);
                            showDownloadOptions(true);
                            videoDetected = true;
                            videoInfoCache.set(cacheKey, {
                                video_info: data.video_info,
                                platform: platform,
                                qualityOptions: defaultOptions
                            });
                        });
                } else {
                    qualitySection.classList.add('hidden');
                    showDownloadOptions(false);
                    videoDetected = true;
                    videoInfoCache.set(cacheKey, {
                        video_info: data.video_info,
                        platform: platform,
                        qualityOptions: null
                    });
                }
            } else {
                hideVideoInfo();
                showToast(data.message || 'Gagal mendeteksi video', 'error');
                videoDetected = false;
            }
        })
        .catch(error => {
            console.error('Error detecting video:', error);
            hideVideoInfo();
            showToast('Terjadi kesalahan saat mendeteksi video', 'error');
            videoDetected = false;
        });
    }

    function showVideoInfoLoading() {
        videoInfo.classList.remove('hidden');
        setTimeout(() => {
            videoInfo.classList.remove('opacity-0');
        }, 10);

        videoTitle.textContent = 'Mendeteksi video...';
        videoUploader.querySelector('span').textContent = 'Tidak Diketahui';
        videoDuration.querySelector('span').textContent = '--:--';
        videoViews.classList.add('hidden');
    }

    function updateVideoInfo(info, platform) {
        videoTitle.textContent = info.title || 'Judul tidak tersedia';
        videoUploader.querySelector('span').textContent = info.uploader || 'Tidak diketahui';
        videoDuration.querySelector('span').textContent = info.duration || '--:--';

        if (info.view_count) {
            videoViews.querySelector('span').textContent = formatNumber(info.view_count);
            videoViews.classList.remove('hidden');
        } else {
            videoViews.classList.add('hidden');
        }

        if (info.thumbnail) {
            let thumbnailSrc = info.thumbnail;
            if (thumbnailSrc.startsWith('http')) {
                thumbnailSrc = `/thumbnail-proxy/${btoa(thumbnailSrc)}`;
            } else if (thumbnailSrc) {
                thumbnailSrc = storageUrl + thumbnailSrc;
            }
            videoThumbnail.innerHTML = `<img src="${thumbnailSrc}" alt="Thumbnail" class="w-full h-full object-cover">`;
        } else {
            videoThumbnail.innerHTML = '<div class="w-full h-full flex items-center justify-center bg-gray-200"><i class="fas fa-image text-gray-400"></i></div>';
        }
    }

    function updatePlatformBadge(platform) {
        const badges = {
            youtube: '<i class="fab fa-youtube text-red-500"></i>',
            instagram: '<i class="fab fa-instagram text-pink-500"></i>',
            tiktok: '<i class="fab fa-tiktok text-gray-900"></i>',
            facebook: '<i class="fab fa-facebook text-blue-600"></i>'
        };

        platformBadge.innerHTML = badges[platform] || '<i class="fas fa-video text-gray-600"></i>';
    }

    function showDownloadOptions(showQuality = true) {
        downloadOptions.classList.remove('hidden');
        setTimeout(() => {
            downloadOptions.classList.remove('opacity-0');
        }, 10);

        formatSection.classList.remove('hidden');
        
        if (showQuality) {
            qualitySection.classList.remove('hidden');
        } else {
            qualitySection.classList.add('hidden');
        }
    }

    function fetchAvailableFormats(url, platform) {
        const cacheKey = `formats_${url}_${platform}`;
        if (videoInfoCache.has(cacheKey)) {
            return Promise.resolve(videoInfoCache.get(cacheKey));
        }

        return fetch('{{ route("downloads.formats") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                url: url,
                platform: platform
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.quality_options) {
                videoInfoCache.set(cacheKey, data.quality_options);
                return data.quality_options;
            } else {
                throw new Error(data.message || 'Failed to fetch formats');
            }
        });
    }

    function populateQualityOptions(options) {
        qualitySelect.innerHTML = '';
        Object.entries(options).forEach(([value, label]) => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = label;
            qualitySelect.appendChild(option);
        });
        qualitySelect.value = 'best';
    }

    function hideVideoInfo() {
        videoInfo.classList.add('opacity-0');
        setTimeout(() => {
            videoInfo.classList.add('hidden');
        }, 300);

        downloadOptions.classList.add('opacity-0');
        setTimeout(() => {
            downloadOptions.classList.add('hidden');
        }, 300);

        videoDetected = false;
    }

    function detectPlatform(url) {
        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            return 'youtube';
        } else if (url.includes('instagram.com')) {
            return 'instagram';
        } else if (url.includes('tiktok.com')) {
            return 'tiktok';
        } else if (url.includes('facebook.com')) {
            return 'facebook';
        }
        return null;
    }

    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        
        const bgColor = type === 'success' ? 'bg-green-500' :
                       type === 'error' ? 'bg-red-500' :
                       'bg-orange-500';
        
        toast.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg mb-3 transform translate-x-full transition-transform duration-300`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-exclamation' : 'fa-info'}"></i>
                <span>${message}</span>
            </div>
        `;

        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
});
</script>
@endpush