<?php

namespace App\Models;

use App\Enums\GigStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'freelancer_id', 'category_id', 'sub_category_id', 'title', 'slug',
    'description', 'thumbnail', 'video_url', 'starting_price', 'currency',
    'delivery_days', 'revision_count', 'status', 'rejection_reason',
    'seo_title', 'seo_description', 'is_featured', 'published_at',
])]
class Gig extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status'       => GigStatus::class,
            'is_featured'  => 'boolean',
            'published_at' => 'datetime',
            'avg_rating'   => 'float',
            'starting_price' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Gig $gig) {
            if (empty($gig->slug)) {
                $gig->slug = static::uniqueSlug($gig->title);
            }
        });
    }

    // ── Relations ─────────────────────────────────────────────────

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(GigPackage::class)->orderBy('price');
    }

    public function basicPackage(): ?GigPackage
    {
        return $this->packages->firstWhere('type', 'basic');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(GigGallery::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(GigFaq::class)->orderBy('sort_order');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(GigRequirement::class)->orderBy('sort_order');
    }

    public function extras(): HasMany
    {
        return $this->hasMany(GigExtra::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_hidden', false)->latest();
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(GigLesson::class)->orderBy('position');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', GigStatus::Published);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['rejected', 'paused']);
    }

    // ── Helpers ───────────────────────────────────────────────────

    public function thumbnailUrl(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        $seed = $this->id ?? abs(crc32($this->title ?? 'gig'));
        return "https://picsum.photos/seed/{$seed}/800/450";
    }

    public function formattedPrice(): string
    {
        return number_format($this->starting_price, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->freelancer_id === $user->id;
    }

    public static function uniqueSlug(string $title): string
    {
        $slug  = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->withTrashed()->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
