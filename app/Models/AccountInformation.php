<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountInformation extends Model
{
    use HasFactory;

    protected $table = 'account_informations';

    protected $fillable = [
        'submission_id',
        'created_through',
        'vdb_name',
        'account_holder_name',
        'account_type',
        'account_no',
        'card_distributed',
        'card_no',
        'app_distribution',
        'qr_distribution',
    ];

    protected $casts = [
        'app_distribution' => 'boolean',
        'qr_distribution'  => 'boolean',
        'card_distributed' => 'boolean',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
