<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookClient extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'book_id',
        'client_id',
        'rent_started_at',
        'rent_ended_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * O livro que está sendo alugado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Book,\App\Models\BookClient>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Cliente que está alugando o livro.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Client,\App\Models\BookClient>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
