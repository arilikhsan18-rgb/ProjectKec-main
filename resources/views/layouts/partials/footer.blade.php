<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright d-flex justify-content-between my-auto">
            
            {{-- Bagian Kiri: Copyright --}}
            <span class="text-left">
                Copyright &copy; SIDAKEP {{ date('Y') }}. Dibuat dengan ❤️ di Tasikmalaya.
            </span>

            {{-- Bagian Kanan: Versi Aplikasi (Praktik yang Baik) --}}
            <span class="text-right d-none d-sm-inline-block">
                Versi {{ env('APP_VERSION', '1.9.0') }}
            </span>

        </div>
    </div>
</footer>
