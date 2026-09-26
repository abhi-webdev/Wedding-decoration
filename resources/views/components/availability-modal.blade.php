<!-- Availability Modal Backdrop & Container -->
<div id="availability-backdrop" class="hidden fixed inset-0 bg-brand-charcoal/70 backdrop-blur-sm z-50 transition-opacity"></div>

<div id="availability-modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 border border-brand-gold shadow-2xl overflow-hidden">
        
        <!-- Top Floral Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-gold via-brand-royal-rose to-brand-gold"></div>

        <!-- Close Button -->
        <button type="button" onclick="closeAvailabilityModal()" class="absolute top-4 right-4 text-gray-400 hover:text-brand-burgundy p-2 rounded-full hover:bg-brand-offwhite transition-colors" aria-label="Close dialog">
            <i class="fas fa-times text-lg"></i>
        </button>

        <!-- Header -->
        <div class="text-center mb-6">
            <span class="inline-block text-[11px] font-bold tracking-widest text-brand-burgundy uppercase mb-1">
                Aditya Utsav • Siwan Hub
            </span>
            <h3 id="modal-title" class="font-serif text-2xl font-bold text-brand-charcoal">
                Check Wedding Date Availability
            </h3>
            <p class="text-xs text-brand-muted-brown mt-1">
                Serving Siwan, Mairwa, Gopalganj, Chapra &amp; Nearby UP Border Districts.
            </p>
        </div>

        <!-- Form -->
        <form id="availability-modal-form" onsubmit="handleAvailabilitySubmit(event)" class="space-y-4">
            <div>
                <label for="modal_event_type" class="block text-xs font-semibold text-brand-charcoal mb-1">
                    Event / Ceremony Type <span class="text-brand-royal-rose">*</span>
                </label>
                <select id="modal_event_type" name="event_type" required class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy">
                    <option value="Wedding & Vivah">Wedding &amp; Vedic Vivah Mandap</option>
                    <option value="Jaimala / Varmala">Jaimala / Varmala Stage</option>
                    <option value="Haldi Ceremony">Haldi Ceremony Setup</option>
                    <option value="Mehendi Ceremony">Mehendi Courtyard Setup</option>
                    <option value="Sangeet Night">Sangeet &amp; Lighting Stage</option>
                    <option value="Tilak / Sagai">Tilak &amp; Auspicious Rituals</option>
                    <option value="Reception">Grand Wedding Reception</option>
                    <option value="Baraat Entry">Baraat Swagat &amp; Entrance Gate</option>
                    <option value="Complete Wedding Package">Complete Vivah Package (All Events)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="modal_city" class="block text-xs font-semibold text-brand-charcoal mb-1">
                        City / District <span class="text-brand-royal-rose">*</span>
                    </label>
                    <select id="modal_city" name="city" required class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy">
                        <optgroup label="Bihar (Primary Service)">
                            <option value="Siwan">Siwan (City &amp; Blocks)</option>
                            <option value="Mairwa">Mairwa</option>
                            <option value="Gopalganj">Gopalganj</option>
                            <option value="Chapra / Saran">Chapra / Saran</option>
                            <option value="Barharia">Barharia</option>
                            <option value="Maharajganj">Maharajganj</option>
                            <option value="Ziradei">Ziradei</option>
                            <option value="Darauli">Darauli</option>
                            <option value="Andar">Andar</option>
                            <option value="Hasanpura">Hasanpura</option>
                            <option value="Lakri Nabiganj">Lakri Nabiganj</option>
                        </optgroup>
                        <optgroup label="Nearby Uttar Pradesh">
                            <option value="Gorakhpur">Gorakhpur (UP)</option>
                            <option value="Deoria">Deoria (UP)</option>
                            <option value="Bhatpar Rani">Bhatpar Rani (UP)</option>
                            <option value="Salempur">Salempur (UP)</option>
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label for="modal_event_date" class="block text-xs font-semibold text-brand-charcoal mb-1">
                        Ceremony Date <span class="text-brand-royal-rose">*</span>
                    </label>
                    <input type="date" id="modal_event_date" name="event_date" required min="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="modal_phone" class="block text-xs font-semibold text-brand-charcoal mb-1">
                        Phone / WhatsApp <span class="text-brand-royal-rose">*</span>
                    </label>
                    <input type="tel" id="modal_phone" name="phone" required placeholder="e.g. 98765 43210" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy">
                </div>

                <div>
                    <label for="modal_name" class="block text-xs font-semibold text-brand-charcoal mb-1">
                        Your Name
                    </label>
                    <input type="text" id="modal_name" name="name" placeholder="Family / Host Name" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy">
                </div>
            </div>

            <div id="modal-result-msg" class="hidden p-3 rounded-lg bg-emerald-50 border border-emerald-300 text-xs text-emerald-800">
                <i class="fas fa-check-circle text-emerald-600 mr-1.5"></i>
                <span>Our decor team is available on this date! We will call/WhatsApp you with the decor portfolio and quote options.</span>
            </div>

            <div class="pt-2">
                <button type="submit" id="modal-submit-btn" class="w-full py-3 px-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow-md hover:shadow-gold-glow transition-all">
                    <i class="fas fa-calendar-check mr-2 text-brand-gold"></i>
                    Check Availability &amp; Get Price
                </button>
            </div>
        </form>

        <div class="mt-4 pt-4 border-t border-brand-light-border/70 flex items-center justify-center gap-4 text-xs text-brand-muted-brown">
            <span class="flex items-center gap-1"><i class="fas fa-bolt text-brand-gold"></i> Instant Response</span>
            <span>•</span>
            <span class="flex items-center gap-1"><i class="fas fa-shield-alt text-brand-gold"></i> Transparent Pricing</span>
        </div>
    </div>
</div>

<script>
    function handleAvailabilitySubmit(e) {
        e.preventDefault();
        const btn = document.getElementById('modal-submit-btn');
        const msg = document.getElementById('modal-result-msg');
        const city = document.getElementById('modal_city')?.value;
        const type = document.getElementById('modal_event_type')?.value;

        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Checking Availability...';
            btn.disabled = true;
        }

        setTimeout(() => {
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check mr-2 text-emerald-300"></i> Availability Confirmed!';
                btn.classList.remove('bg-brand-burgundy');
                btn.classList.add('bg-emerald-800');
            }
            if (msg) msg.classList.remove('hidden');
            showToast(`Good news! Aditya Utsav is available for ${type} in ${city}.`, 'success');
        }, 600);
    }
</script>
