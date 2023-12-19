<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\UpdateBankAccount;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateBankAccountTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(UpdateBankAccount::class)
            ->assertStatus(200);
    }
}
