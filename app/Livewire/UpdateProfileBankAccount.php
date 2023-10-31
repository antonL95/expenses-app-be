<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\UserBankAccounts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UpdateProfileBankAccount extends Component
{
    /**
     * @var null|UserBankAccounts[]
     */
    public ?array $accounts = [];

    public ?string $accountName;

    #[Rule(['required'])]
    public string $accountApiToken;

    #[Rule(['required'])]
    public string $accountCurrency = '';

    public function mount(): void
    {
        $this->accounts = Auth::user()?->with('bankAccounts');
    }

    public function addBankAccount(): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        $this->accounts[] = UserBankAccounts::create(
            [
                'userId' => $user->id,
                'accountName' => $this->accountName,
                'accountApiToken' => $this->accountApiToken,
                'accountCurrency' => $this->accountCurrency,
                'createdAt' => now(),
            ],
        );
    }

    public function render()
    {
        return view(
            'livewire.update-profile-bank-account',
            [
                'bankAccounts' => $this->accounts,
            ],
        );
    }
}
