<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-16 sm:scroll-pt-20 overflow-x-hidden w-full max-w-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Dynamic SEO Meta Tags -->
    <title>@yield('title', 'Aditya Utsav | Bihar Wedding Decoration & Event Services')</title>
    <meta name="description" content="@yield('meta_description', 'Aditya Utsav provides traditional and royal wedding decorations for Jaimala, Mandap, Haldi, Mehendi, Sangeet and Reception celebrations across Siwan, Patna, Chapra, Gopalganj and Bihar.')">
    <meta name="keywords" content="Bihar wedding decoration, Siwan wedding mandap, Jaimala stage Siwan, Haldi decoration Bihar, Aditya Utsav, traditional wedding Bihar, Gopalganj wedding decor, Chapra wedding decorators">
    <meta name="author" content="Aditya Utsav">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="@yield('og_title', 'Aditya Utsav | Bihar Wedding Decoration & Event Services')">
    <meta property="og:description" content="@yield('og_description', 'Make your wedding celebration beautiful with authentic traditional and royal decoration setups across Siwan, Bihar and nearby regions.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @php
        $siteLogo = \App\Models\SiteSetting::getSafeImage('site_logo');
        $siteFavicon = \App\Models\SiteSetting::getSafeImage('site_favicon');
    @endphp
    @if($siteLogo)
        <meta property="og:image" content="@yield('og_image', $siteLogo)">
        <meta name="twitter:image" content="@yield('og_image', $siteLogo)">
    @endif
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Aditya Utsav | Bihar Wedding Decoration & Event Services')">
    <meta name="twitter:description" content="@yield('og_description', 'Make your wedding celebration beautiful with authentic traditional and royal decoration setups across Siwan, Bihar and nearby regions.')">
    
    @if($siteFavicon)
        <link rel="icon" href="{{ $siteFavicon }}">
    @endif

    <!-- Structured Data (JSON-LD) for Local Business -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Aditya Utsav",
      "image": "{{ asset('images/logo/aditya-utsav-logo.svg') }}",
      "@id": "{{ url('/') }}",
      "url": "{{ url('/') }}",
      "telephone": "+919876543210",
      "priceRange": "₹₹",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Siwan",
        "addressRegion": "Bihar",
        "postalCode": "841226",
        "addressCountry": "IN"
      },
      "areaServed": [
        "Siwan", "Gopalganj", "Chapra", "Patna", "Muzaffarpur", "Darbhanga", "Deoria", "Ballia", "Gorakhpur"
      ]
    }
    </script>

    <!-- Google Fonts: Playfair Display + Cormorant Garamond (Serif) & DM Sans + Inter (Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600&display=swap" rel="stylesheet">
    
    <!-- FontAwesome CDN for Crisp Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            burgundy: '#72002F',
                            'deep-burgundy': '#800033',
                            'royal-rose': '#A52A55',
                            gold: '#D4AF37',
                            'gold-light': '#F6E6B4',
                            'gold-dark': '#AA771C',
                            cream: '#FFF8F0',
                            offwhite: '#F8F1EA',
                            charcoal: '#1F1F1F',
                            'muted-brown': '#6B5E57',
                            'light-border': '#EADBCE',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', '"Cormorant Garamond"', 'Georgia', 'serif'],
                        display: ['"Cormorant Garamond"', '"Playfair Display"', 'Georgia', 'serif'],
                        sans: ['"DM Sans"', 'Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft-luxury': '0 10px 30px -5px rgba(114, 0, 47, 0.08), 0 4px 6px -2px rgba(114, 0, 47, 0.03)',
                        'card-hover': '0 20px 35px -8px rgba(114, 0, 47, 0.15), 0 8px 10px -4px rgba(212, 175, 55, 0.1)',
                        'gold-glow': '0 0 25px rgba(212, 175, 55, 0.35)',
                    }
                }
            }
        }
    </script>

    <style>
        /* Base typography & utility styling */
        html, body {
            overflow-x: hidden !important;
            width: 100% !important;
            max-width: 100vw !important;
            position: relative;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: #1F1F1F;
            background-color: #FFF8F0;
        }

        h1, h2, h3, .font-serif-title {
            font-family: 'Playfair Display', Georgia, serif;
        }

        .gold-shimmer {
            background: linear-gradient(135deg, #AA771C 0%, #D4AF37 50%, #F6E6B4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gold-border-gradient {
            border-image: linear-gradient(to right, #D4AF37, #F6E6B4, #D4AF37) 1;
        }

        .subtle-marigold-pattern {
            background-color: #FFF8F0;
            background-image: radial-gradient(#D4AF37 0.75px, transparent 0.75px), radial-gradient(#72002F 0.75px, #FFF8F0 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            background-opacity: 0.03;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FFF8F0;
        }
        ::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #72002F;
        }

        /* Smooth transitions */
        .transition-luxury {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-brand-burgundy selection:text-white">

    <!-- 1. Top Announcement Bar -->
    @include('components.announcement-bar')

    <!-- 2. Main Header / Navigation -->
    @include('components.header')

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- 3. Availability Checker Modal (Global Triggerable) -->
    @include('components.availability-modal')

    <!-- 4. Wishlist Drawer UI -->
    @include('components.wishlist-drawer')

    <!-- 5. Toast Notification System -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none" aria-live="polite"></div>

    <!-- 6. Footer -->
    @include('components.footer')

    <!-- Global Vanilla JavaScript -->
    <script>
        // Global Wishlist state stored in localStorage
        const Wishlist = {
            key: 'aditya_utsav_wishlist',
            get() {
                try {
                    return JSON.parse(localStorage.getItem(this.key)) || [];
                } catch(e) {
                    return [];
                }
            },
            add(item) {
                const list = this.get();
                if (!list.some(i => i.id === item.id)) {
                    list.push(item);
                    localStorage.setItem(this.key, JSON.stringify(list));
                    this.updateUI();
                    showToast(`"${item.name}" added to your shortlisted decorations!`, 'success');
                } else {
                    this.remove(item.id);
                    showToast(`"${item.name}" removed from shortlist.`, 'info');
                }
            },
            remove(id) {
                let list = this.get();
                list = list.filter(i => i.id !== id);
                localStorage.setItem(this.key, JSON.stringify(list));
                this.updateUI();
            },
            has(id) {
                return this.get().some(i => i.id === id);
            },
            updateUI() {
                const list = this.get();
                const badgeElements = document.querySelectorAll('.wishlist-count-badge');
                badgeElements.forEach(el => {
                    el.textContent = list.length;
                    el.style.display = list.length > 0 ? 'inline-flex' : 'none';
                });

                // Update heart icons across cards
                document.querySelectorAll('[data-wishlist-id]').forEach(btn => {
                    const id = parseInt(btn.getAttribute('data-wishlist-id'));
                    const icon = btn.querySelector('i');
                    if (icon) {
                        if (this.has(id)) {
                            icon.classList.remove('far');
                            icon.classList.add('fas', 'text-brand-burgundy');
                        } else {
                            icon.classList.remove('fas', 'text-brand-burgundy');
                            icon.classList.add('far');
                        }
                    }
                });

                // Render wishlist drawer items if present
                const drawerContainer = document.getElementById('wishlist-items-list');
                const emptyMessage = document.getElementById('wishlist-empty-msg');
                if (drawerContainer && emptyMessage) {
                    if (list.length === 0) {
                        drawerContainer.innerHTML = '';
                        emptyMessage.classList.remove('hidden');
                    } else {
                        emptyMessage.classList.add('hidden');
                        drawerContainer.innerHTML = list.map(item => `
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-brand-light-border shadow-sm">
                                <img src="${item.image}" alt="${item.name}" class="w-14 h-14 object-cover rounded-md flex-shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-sm font-semibold text-brand-charcoal truncate font-serif">${item.name}</h5>
                                    <p class="text-xs text-brand-royal-rose font-medium">${item.price}</p>
                                    <span class="text-[11px] text-brand-muted-brown">${item.category}</span>
                                </div>
                                <button onclick="Wishlist.remove(${item.id})" class="text-gray-400 hover:text-red-500 text-xs p-1" title="Remove" aria-label="Remove item">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `).join('');
                    }
                }
            }
        };

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto flex items-center gap-3 px-5 py-3 rounded-lg shadow-lg border text-sm font-medium transform transition-all duration-300 translate-y-4 opacity-0 ${
                type === 'success' ? 'bg-brand-burgundy text-white border-brand-gold' :
                type === 'info' ? 'bg-brand-charcoal text-white border-gray-600' :
                'bg-red-800 text-white border-red-500'
            }`;

            const icon = type === 'success' ? 'fa-check-circle text-brand-gold' : 'fa-info-circle text-brand-gold-light';
            toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            }, 10);

            // Animate out & remove
            setTimeout(() => {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Drawer & Modal Helpers
        function toggleWishlistDrawer(open = true) {
            const drawer = document.getElementById('wishlist-drawer');
            const backdrop = document.getElementById('wishlist-backdrop');
            if (!drawer || !backdrop) return;

            if (open) {
                Wishlist.updateUI();
                drawer.classList.remove('invisible', 'pointer-events-none');
                void drawer.offsetWidth;
                drawer.classList.remove('translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => {
                    drawer.classList.add('invisible', 'pointer-events-none');
                }, 300);
            }
        }

        function openAvailabilityModal(prefillType = '', prefillCity = '') {
            const modal = document.getElementById('availability-modal');
            const backdrop = document.getElementById('availability-backdrop');
            if (!modal || !backdrop) return;

            if (prefillType) {
                const typeInput = document.getElementById('modal_event_type');
                if (typeInput) typeInput.value = prefillType;
            }
            if (prefillCity) {
                const cityInput = document.getElementById('modal_city');
                if (cityInput) cityInput.value = prefillCity;
            }

            modal.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAvailabilityModal() {
            const modal = document.getElementById('availability-modal');
            const backdrop = document.getElementById('availability-backdrop');
            if (!modal || !backdrop) return;

            modal.classList.add('hidden');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Initialize UI states on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            Wishlist.updateUI();
        });
    </script>

    @stack('scripts')
</body>
</html>
