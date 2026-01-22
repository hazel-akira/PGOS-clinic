<x-filament-widgets::widget>
    <x-filament::section>
       <x-filament::card>
            <h2 class="text-lg font-bold mb-4">Quick Actions</h2>

            <div class="flex flex-wrap gap-3">
                
                <x-filament::button icon="heroicon-o-plus-circle">
                <a href="{{ route('filament.clinic.resources.visits.create') }}" class="block w-full">
                    New Visit
                </a>
                </x-filament::button>

                <x-filament::button color="success" icon="heroicon-o-home">
                    Admit Patient
                </x-filament::button>

                <x-filament::button color="warning" icon="heroicon-o-arrow-right-circle">
                    Refer Patient
                </x-filament::button>
            </div>
        </x-filament::card>

    </x-filament::section>
</x-filament-widgets::widget>
