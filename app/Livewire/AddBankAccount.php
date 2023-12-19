<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\UserBankAccounts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AddBankAccount extends Component
{
    public ?string $accountName;

    #[Rule(['required'])]
    public string $accountApiToken;

    #[Rule(['required'])]
    public ?string $accountCurrency = null;

    public function addBankAccount(): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        UserBankAccounts::create(
            [
                'userId' => $user->id,
                'accountName' => $this->accountName,
                'accountApiToken' => $this->accountApiToken,
                'accountCurrency' => $this->accountCurrency,
            ],
        );

        $this->redirect(ShowBankAccounts::class, true);
    }

    public function render(): View
    {
        return view(
            'livewire.bank_accounts.add-bank-account',
        );
    }
}
