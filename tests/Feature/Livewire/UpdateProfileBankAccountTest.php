<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\UpdateProfileBankAccount;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateProfileBankAccountTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(UpdateProfileBankAccount::class)
            ->assertStatus(200);
    }
}
