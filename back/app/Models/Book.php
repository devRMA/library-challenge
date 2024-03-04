<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int                  $id
 * @property string               $name
 * @property string               $cpf
 * @property Collection<int,Book> $books
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property null|Pivot           $pivot
 */
class Book extends Model
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
     * Os clientes que alugaram este livro.
     *
     * @return BelongsToMany<Client>
     */
    public function clients(): BelongsToMany
    {
        $pivot = new BookClient();

        return $this->belongsToMany(Client::class)
            ->using(get_class($pivot))
            ->withPivot([
                'rent_started_at',
                'rent_ended_at',
            ])
            ->withTimestamps();
    }
}
