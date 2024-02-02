<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookUser extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'livro_id',
        'usuario_id',
        'dt_aluguel_ini',
        'dt_aluguel_fim',
        'dt_inclusao',
        'dt_alteracao',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'dt_inclusao',
        'dt_alteracao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dt_inclusao' => 'datetime',
        'dt_alteracao' => 'datetime',
    ];

    /**
     * O livro que está sendo alugado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Book,\App\Models\BookUser>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'livro_id');
    }

    /**
     * Usuário que está alugando o livro.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User,\App\Models\BookUser>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
