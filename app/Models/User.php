<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    use HasFactory;

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
        'nome',
        'cpf',
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
     * Os livros que o usuário alugou.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Book>
     */
    public function books(): BelongsToMany
    {
        $pivot = new BookUser();

        return $this->belongsToMany(
            Book::class,
            $pivot->getTable(),
            'usuario_id',
            'livro_id',
        )->using(BookUser::class)
            ->withPivot([
                'dt_aluguel_ini',
                'dt_aluguel_fim',
                'dt_inclusao',
                'dt_alteracao',
            ]);
    }
}
