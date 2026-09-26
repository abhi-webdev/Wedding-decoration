@extends('layouts.app')

@section('title', 'Get a Custom Wedding Decoration Quote | Aditya Utsav')
@section('meta_description', 'Request a customized wedding decoration estimate for Jaimala, Mandap, Haldi, or full Vivah packages in Siwan, Gopalganj, Chapra.')

@section('content')
<section class="bg-brand-deep-burgundy text-white py-14 border-b border-brand-gold relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-brand-gold mb-2">
            TRANSPARENT ESTIMATES
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
            Get a Custom Decoration Quote
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/80 max-w-xl mx-auto mt-2">
            Share your celebration requirements and our Siwan decoration team will prepare a tailored proposal.
        </p>
    </div>
</section>

<section class="py-16 bg-brand-cream min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl p-8 sm:p-10 border border-brand-gold/50 shadow-card-hover">
            <form onsubmit="handleQuoteSubmit(event)" class="space-y-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-1">Your Full Name *</label>
                        <input type="text" required placeholder="e.g. Ramesh Chandra" class="w-full px-4 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-1">Phone / WhatsApp *</label>
                        <input type="tel" required placeholder="e.g. 98765 43210" class="w-full px-4 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-1">Event Location / City *</label>
                        <select class="w-full px-4 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                            <optgroup label="Bihar">
                                <option value="Siwan">Siwan</option>
                                <option value="Mairwa">Mairwa</option>
                                <option value="Gopalganj">Gopalganj</option>
                                <option value="Chapra">Chapra / Saran</option>
                                <option value="Barharia">Barharia</option>
                                <option value="Maharajganj">Maharajganj</option>
                            </optgroup>
                            <optgroup label="Nearby Uttar Pradesh">
                                <option value="Gorakhpur">Gorakhpur (UP)</option>
                                <option value="Deoria">Deoria (UP)</option>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-1">Ceremony Date *</label>
                        <input type="date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2">Ceremonies You Require Decoration For</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                        <label class="flex items-center gap-2 p-2.5 rounded bg-brand-offwhite border border-brand-light-border cursor-pointer">
                            <input type="checkbox" name="ceremonies[]" class="text-brand-burgundy rounded">
                            <span>Jaimala Stage</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded bg-brand-offwhite border border-brand-light-border cursor-pointer">
                            <input type="checkbox" name="ceremonies[]" class="text-brand-burgundy rounded">
                            <span>Vedic Mandap</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded bg-brand-offwhite border border-brand-light-border cursor-pointer">
                            <input type="checkbox" name="ceremonies[]" class="text-brand-burgundy rounded">
                            <span>Haldi Setup</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded bg-brand-offwhite border border-brand-light-border cursor-pointer">
                            <input type="checkbox" name="ceremonies[]" class="text-brand-burgundy rounded">
                            <span>Mehendi Courtyard</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded bg-brand-offwhite border border-brand-light-border cursor-pointer">
                            <input type="checkbox" name="ceremonies[]" class="text-brand-burgundy rounded">
                            <span>Sangeet Night</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded bg-brand-offwhite border border-brand-light-border cursor-pointer">
                            <input type="checkbox" name="ceremonies[]" class="text-brand-burgundy rounded">
                            <span>Grand Reception</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-1">Additional Details / Venue Notes</label>
                    <textarea rows="4" placeholder="Mention venue name (home, marriage hall, lawn), expected guest size, or custom flower preferences..." class="w-full px-4 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy"></textarea>
                </div>

                <div id="quote-success" class="hidden p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-xs text-emerald-800">
                    <i class="fas fa-check-circle text-emerald-600 mr-2"></i>
                    Thank you! Your quote request has been received. Our team will contact you via Phone/WhatsApp shortly.
                </div>

                <button type="submit" id="quote-btn" class="w-full py-3.5 px-6 text-xs sm:text-sm font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow-md hover:shadow-gold-glow transition-all">
                    <i class="fas fa-paper-plane mr-2 text-brand-gold"></i>
                    Submit Custom Quote Request
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    function handleQuoteSubmit(e) {
        e.preventDefault();
        const btn = document.getElementById('quote-btn');
        const msg = document.getElementById('quote-success');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check mr-2"></i> Request Submitted';
            btn.classList.add('bg-emerald-800');
            msg.classList.remove('hidden');
            showToast('Quote request submitted successfully!', 'success');
        }, 600);
    }
</script>
@endsection
