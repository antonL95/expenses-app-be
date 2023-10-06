<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FioTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'fio_id',
        'transaction_timestamp',
        'amount',
        'note',
        'reference',
        'comment',
        'note_for_recipient',
    ];

    protected $casts = [
        'transaction_timestamp' => 'date',
        'amount' => 'decimal:6',
    ];

    protected $table = 'fio_transactions';
}
