<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VdbEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'vdb_name',
        'division',
        'district',
        'thana',
        'union',
        'village',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
