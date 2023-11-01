<div>
    @if($accounts !== null)
        @foreach($accounts as $account)
            <div class="flex" wire:key="{{$account->id}}">
                <div class="col-span-6 sm:col-span-4">
                    {{ __('Account Name') }}:
                    {{ $account->accountName }}
                </div>

                <div class="col-span-6 sm:col-span-4">
                    {{ __('Account API token') }}:
                    {{ $account->accountApiToken }}
                </div>

                <div class="col-span-6 sm:col-span-4">
                    {{ __('Account Currency') }}:
                    {{ $account->accountCurrency }}
                </div>
                <x-danger-button wire:click="deleteBankAccount({{$account->id}})">
                    {{ __('Delete') }}
                </x-danger-button>
            </div>
        @endforeach
    @endif

    @livewire('update-profile-bank-account')
</div>
