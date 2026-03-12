<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvTrack — Inventory & Profit System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Sora', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#f0fdf9',
                            100: '#ccfbee',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-100 font-sans flex h-screen overflow-hidden antialiased">

    <!-- Sidebar -->
    <div id="sidebar" class="w-64 flex-shrink-0 h-screen overflow-y-auto bg-slate-900 border-r border-slate-800/60"></div>

    <!-- Main Content -->
    <div id="mainContent" class="flex-1 overflow-y-auto bg-slate-950"></div>

    <!-- Modal Overlay -->
    <div id="modalContainer" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"></div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-6 right-6 z-[60] hidden">
        <div class="bg-slate-800 border border-slate-700 rounded-xl px-5 py-3.5 flex items-center gap-3 shadow-2xl shadow-black/40">
            <div id="toastIcon" class="w-5 h-5 rounded-full flex items-center justify-center text-xs flex-shrink-0"></div>
            <span id="toastMsg" class="text-sm text-slate-200 font-medium"></span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // ── Globals ─────────────────────────────────────────────────────────────
        window.currentPage = '';

        // ── Toast helper ────────────────────────────────────────────────────────
        window.showToast = function(msg, type = 'success') {
            const colors = { success: 'bg-emerald-400', error: 'bg-rose-400', info: 'bg-blue-400' };
            const icons  = { success: '✓', error: '✕', info: 'i' };
            $('#toastIcon').attr('class', `w-5 h-5 rounded-full flex items-center justify-center text-xs flex-shrink-0 text-slate-900 font-bold ${colors[type]}`).text(icons[type]);
            $('#toastMsg').text(msg);
            $('#toast').removeClass('hidden').addClass('flex');
            clearTimeout(window._toastTimer);
            window._toastTimer = setTimeout(() => $('#toast').addClass('hidden').removeClass('flex'), 3500);
        };

        // ── Page loader ─────────────────────────────────────────────────────────
        function loadPage(page) {
            window.currentPage = page;
            $('#mainContent').html(`
                <div class="flex items-center justify-center h-64">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-8 h-8 border-2 border-emerald-400/30 border-t-emerald-400 rounded-full animate-spin"></div>
                        <p class="text-slate-500 text-sm">Loading…</p>
                    </div>
                </div>`);
            $.get('frontend/' + page, function(data) {
                $('#mainContent').html(data);
            });
            // Update active link
            $('#sidebar a[data-page]').each(function() {
                const isActive = $(this).data('page') === page;
                $(this)
                    .toggleClass('bg-emerald-500/10 text-emerald-400 border-emerald-500/40', isActive)
                    .toggleClass('text-slate-400 border-transparent hover:text-slate-200 hover:bg-slate-800/60', !isActive);
            });
        }

        // ── Bootstrap ────────────────────────────────────────────────────────────
        $(function() {
            $.get('frontend/sidebar.php', function(data) {
                $('#sidebar').html(data);
                loadPage('dashboard.php');
            });

            $(document).on('click', '#sidebar a[data-page]', function(e) {
                e.preventDefault();
                loadPage($(this).data('page'));
            });

            // Close modal on backdrop click
            $(document).on('click', '#modalContainer', function(e) {
                if (e.target === this) {
                    $(this).addClass('hidden').empty();
                }
            });
        });
    </script>
</body>
</html>