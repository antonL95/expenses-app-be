<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ShowBankAccounts;
use Livewire\Livewire;
use Tests\TestCase;

class ShowBankAccountsTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ShowBankAccounts::class)
            ->assertStatus(200)
            ->assertSee('Bank accounts information');
    }
}
