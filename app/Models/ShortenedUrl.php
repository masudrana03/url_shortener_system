<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortenedUrl extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'original_url',
        'short_url',
        'slug',
        'click_count',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = $model->generateSlug();
        });
    }

    /**
     * @return mixed
     */
    private function generateSlug()
    {
        $slug = Str::random(6);

        $count = 1;
        while (self::where('slug', $slug)->exists()) {
            $slug = Str::random(6) . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * @param $query
     * @param $shortUrl
     * @return mixed
     */
    public function scopeExistsShortUrl($query, $shortUrl)
    {
        return $query->where('short_url', $shortUrl)->exists();
    }

    /**
     * @param $baseUrl
     * @return mixed
     */
    public static function generateUniqueShortUrl($baseUrl)
    {
        $shortUrl = $baseUrl;
        $counter  = 1;

        while (self::existsShortUrl($shortUrl)) {
            $shortUrl = $baseUrl . '-' . $counter;
            $counter++;
        }

        return $shortUrl;
    }

    /**
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        $appUrl = url('/');

        $search = str_replace($appUrl, '', $search);
        $search = ltrim($search, '/');

        return $query->where('original_url', 'like', "%{$search}%")
            ->orWhere('short_url', 'like', "%{$search}%");
    }

    public function recordClick()
    {
        $this->increment('click_count');
        $this->clicks()->create(['clicked_at' => now()]);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function clicks()
    {
        return $this->hasMany(UrlClick::class)->orderBy('created_at', 'desc');
    }
}
