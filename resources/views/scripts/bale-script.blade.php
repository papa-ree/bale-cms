<script>
    window.firebaseConfig = @json(config('bale-cms.firebase'));
</script>

@auth
    <script type="module">
        async function initFirebaseMessagingRegistration() {
            try {
                if (typeof getToken === 'undefined' || typeof messaging === 'undefined') {
                    console.error("❌ Firebase belum terinisialisasi dengan benar.");
                    // alert("Gagal memuat notifikasi. Silakan refresh halaman atau hubungi admin.");
                    return;
                }

                const token = await getToken(messaging, {
                    vapidKey: "BMzz7JE77E_mKMAMaGfa1Zbwi6veXuhJ7UitlKzU_q-Isc75eOkWSoCIHDITX7UtuDLUKFTQF0W6DfgesXASgE0",
                });

                if (token) {
                    await axios({
                        url: @js(route('update-fcm-token')),
                        method: 'post',
                        data: {
                            _method: 'PATCH',
                            currentToken: token,
                        },
                    });

                    // console.info("✅ Token FCM berhasil dikirim ke server.");
                } else {
                    console.warn("⚠️ Token tidak tersedia. Mungkin pengguna belum memberi izin notifikasi.");
                    // alert("Izin notifikasi belum diberikan. Aktifkan notifikasi agar mendapatkan update real-time.");
                }
            } catch (error) {
                if (error.message.includes('Missing App configuration value')) {
                    console.error("❌ Konfigurasi Firebase belum lengkap.");
                    // alert("Konfigurasi notifikasi salah. Hubungi administrator.");
                } else {
                    console.error("❌ Gagal mendapatkan token atau mengirim ke server:", error);
                    // alert("Terjadi kesalahan saat mengaktifkan notifikasi.");
                }
            }
        }

        document.addEventListener('DOMContentLoaded', async () => {
            const permission = Notification.permission;

            if (permission === 'granted') {
                await initFirebaseMessagingRegistration();
            } else if (permission === 'default') {
                try {
                    const request = await Notification.requestPermission();
                    if (request === 'granted') {
                        await initFirebaseMessagingRegistration();
                    } else {
                        console.warn("🔕 Notifikasi ditolak oleh pengguna.");
                        // alert("Anda menolak notifikasi. Silakan aktifkan dari pengaturan browser untuk mendapatkan update.");
                    }
                } catch (e) {
                    console.error("❌ Gagal meminta izin notifikasi:", e);
                }
            } else if (permission === 'denied') {
                console.warn("🔕 Notifikasi diblokir.");
                // alert("Anda telah memblokir notifikasi. Aktifkan kembali dari pengaturan browser.");
            }
        });
    </script>
@endauth
