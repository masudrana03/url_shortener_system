<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UrlClick extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'shortened_url_id',
    ];

    /**
     * @return mixed
     */
    public function shortenedUrl()
    {
        return $this->belongsTo(ShortenedUrl::class);
    }
}
