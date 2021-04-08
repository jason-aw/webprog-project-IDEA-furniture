<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeaderTransaction extends Model
{
    protected $fillable = [
        'user_id', 'transaction_date'
    ];

    // relation with Detail Transaction, 1 header transaction many transaction detail
    public function detailTransactions() {
        return $this->hasMany(DetailTransaction::class);
    }

    // relation with user
    public function user() {
        return $this->belongsTo(User::class);
    }
}
