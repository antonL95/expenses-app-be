<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowBankAccounts extends Component
{
    public ?Collection $accounts;


    public function mount(): void
    {
        $this->accounts = Auth::user()?->bankAccounts;
    }

    public function deleteBankAccount(int $bankAccountId): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        $user->bankAccounts()->where('id', $bankAccountId)->delete();

        $this->dispatch('userBankAccountDeleted');
    }


    #[On('userBankAccountDeleted')]
    #[On('userBankAccountAdded')]
    public function updateBankAccounts(): void
    {
        $this->accounts = Auth::user()?->bankAccounts;
    }

    public function render(): View
    {
        return view(
            'livewire.show-bank-accounts',
            [
                'bankAccounts' => $this->accounts,
            ],
        );
    }
}
