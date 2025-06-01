<div>
    <!-- Table Layout Section -->
    <div>
        @if(count($tables) === 0)
            <div class="p-4 bg-yellow-50 text-yellow-700 border border-yellow-300 rounded-md mb-4">
                {{ __('translations.No tables created yet') }}. 
                {{ __('translations.Click the Add Table button to start creating your seating layout') }}.
            </div>
        @endif

    {{ $this->form }}
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listen for the maxSeatsReverted event
            Livewire.on('maxSeatsReverted', ({ maxSeats }) => {
                // Find and update any UI elements displaying the max seats value
                const maxSeatsInputs = document.querySelectorAll('[name="max_seats_per_table"]');
                if (maxSeatsInputs.length) {
                    maxSeatsInputs.forEach(input => {
                        input.value = maxSeats;
                        
                        // If using Filament forms, also update any display elements
                        const displayElement = input.parentElement.querySelector('.fi-input-wrapper');
                        if (displayElement) {
                            displayElement.innerText = maxSeats;
                        }
                    });
                }
                
                // Force Filament form fields to refresh if they exist
                if (window.Alpine) {
                    Alpine.nextTick(() => {
                        document.querySelectorAll('[x-data]').forEach(el => {
                            if (el.__x) {
                                el.__x.updateElements(el);
                            }
                        });
                    });
                }
            });
        });
    </script>
</div>
