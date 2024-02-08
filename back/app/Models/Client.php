<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory;
    use Searchable;
    // @see https://spatie.be/docs/laravel-activitylog/v4/advanced-usage/logging-model-events
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'cpf',
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
     * Configuração do que será registrado nos logs.
     *
     * @see https://spatie.be/docs/laravel-activitylog/v4/advanced-usage/logging-model-events
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->logFillable();
    }

    /**
     * Os livros que esse cliente alugou.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Book>
     */
    public function books(): BelongsToMany
    {
        $pivot = new BookClient();

        return $this->belongsToMany(Book::class)
            ->using(get_class($pivot))
            ->withPivot([
                'rent_started_at',
                'rent_ended_at',
            ])
            ->withTimestamps();
    }
}
