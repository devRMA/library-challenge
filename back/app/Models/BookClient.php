<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $book_id
 * @property Book        $book
 * @property int         $client_id
 * @property Client      $client
 * @property Carbon      $rent_started_at
 * @property null|Carbon $rent_ended_at
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 */
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
     * @return BelongsTo<Book,BookClient>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Cliente que está alugando o livro.
     *
     * @return BelongsTo<Client,BookClient>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
