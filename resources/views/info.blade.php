<flux:modal name="info" class="max-w-md">
    <flux:heading size="lg" class="modal-header">{{ config('app.name') }}</flux:heading>
    <div class="text-center">
        <h3 class="text-xl font-bold text-zinc-800 dark:text-white">{{ config('app.name') }} <span class="font-normal ml-1"> 2.0.0</span></h3>
        <div class="mt-6">
            <p class="text-zinc-800 dark:text-white mb-2">&copy; 2025 Marc Schlagenhauf</p>
            <p class="text-zinc-800 dark:text-white">{{ __('common.licensedUnder') }} <flux:link href="https://interoperable-europe.ec.europa.eu/collection/eupl/eupl-text-eupl-12" target="_blank" rel="noopener noreferrer">{{ __('common.license') }}</flux:link>.</p>
        </div>
        <div class="space-y-2 bg-zinc-100 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 -mx-6 -mb-6 px-6 py-5 mt-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <flux:button size="sm" icon="external-link" href="{{ route('imprint') }}" target="_blank">{{ __('common.imprint') }}</flux:button>
                <flux:button size="sm" icon="external-link" href="{{ route('privacy') }}" target="_blank">{{ __('common.privacyPolicy') }}</flux:button>
                <flux:button size="sm" icon="external-link" href="{{ route('source-code') }}" target="_blank">{{ __('common.sourceCode') }}</flux:button>
            </div>
        </div>
    </div>
</flux:modal>