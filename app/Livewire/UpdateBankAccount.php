<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\UserBankAccounts;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UpdateBankAccount extends Component
{
    public int $id;

    public ?string $accountName;

    #[Rule(['required'])]
    public string $accountApiToken;

    #[Rule(['required'])]
    public ?string $accountCurrency = null;

    public function mount(int $id): void
    {
        $bankAccount = UserBankAccounts::find($id);

        if ($bankAccount === null) {
            throw new \InvalidArgumentException('Bank account no longer exists');
        }

        $this->accountName = $bankAccount->accountName;
        $this->accountApiToken = $bankAccount->accountApiToken;
        $this->accountCurrency = $bankAccount->accountCurrency;
        $this->id = $bankAccount->id;
    }

    public function update(UserBankAccounts $userBankAccount): void
    {
        $userBankAccount->update([
            'accountName' => $this->accountName,
            'accountApiToken' => $this->accountApiToken,
            'accountCurrency' => $this->accountCurrency,
        ]);

        $this->redirect(ShowBankAccounts::class, true);
    }

    public function render(): View
    {
        return view('livewire.update-bank-account');
    }
}
