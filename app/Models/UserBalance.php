<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBalance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUserBalance(User $user): int
    {
        $balance = UserBalance::whereHas('user', function (Builder $query) use ($user){
            return $query->whereKey($user->id);
        })->first();
        return $balance ? $balance->balance : 0;
    }
}
