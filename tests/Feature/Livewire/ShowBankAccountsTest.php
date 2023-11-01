<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ShowBankAccounts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ShowBankAccountsTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ShowBankAccounts::class)
            ->assertStatus(200);
    }
}
