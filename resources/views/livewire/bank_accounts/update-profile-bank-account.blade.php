<div>
    <x-form-section submit="addBankAccount" wire:key="{{now()->getTimestamp()}}">
        <x-slot name="title">
            {{ __('Bank accounts information') }}
        </x-slot>

        <x-slot name="description">
        </x-slot>

        <x-slot name="form">
            <!-- Account Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="accountName" value="{{ __('Account Name') }}"/>
                <x-input id="accountName" type="text" class="mt-1 block w-full" wire:model="accountName"/>
                <x-input-error for="accountName" class="mt-2"/>
            </div>

            <!-- Account API token -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="accountApiToken" value="{{ __('Account API token') }}"/>
                <x-input id="accountApiToken" type="text" class="mt-1 block w-full" wire:model="accountApiToken"
                         required/>
                <x-input-error for="accountApiToken" class="mt-2"/>
            </div>

            <!-- Account Currency -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="accountCurrency" value="{{ __('Account Currency') }}"/>
                <x-currency-select id="accountCurrency" type="select" class="mt-1 block w-full"
                                   wire:model="accountCurrency"
                                   required/>
                <x-input-error for="accountCurrency" class="mt-2"/>
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="photo">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
