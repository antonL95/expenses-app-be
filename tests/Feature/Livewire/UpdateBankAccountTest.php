<?php

namespace Tests\Feature\Livewire;

use App\Livewire\UpdateBankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
