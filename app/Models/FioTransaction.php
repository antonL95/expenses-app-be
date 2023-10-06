<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FioTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'fioId',
        'transactionTimestamp',
        'amount',
        'note',
        'reference',
        'comment',
        'noteForRecipient',
    ];

    protected $casts = [
        'transactionTimestamp' => 'date',
        'amount' => 'decimal:2',
    ];

    protected $table = 'fio_transactions';
}
