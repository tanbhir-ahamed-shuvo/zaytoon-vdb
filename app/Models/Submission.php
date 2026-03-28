<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Submission extends Model
{
    use HasFactory;

    protected $fillable = ['field_officer_id', 'field_officer_name', 'submitter_email', 'submission_date'];

    protected $casts = [
        'submission_date' => 'date',
    ];

    public function vdbEntries(): HasMany
    {
        return $this->hasMany(VdbEntry::class);
    }

    public function fieldOfficer(): BelongsTo
    {
        return $this->belongsTo(FieldOfficer::class);
    }

    public function accountInformations(): HasMany
    {
        return $this->hasMany(AccountInformation::class);
    }
}
