<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'reference_no', 'user_id', 'title', 'slug', 'type', 'category', 'listing_type', 'status',
    'price', 'price_period', 'address', 'city', 'area_location', 'size_label',
    'bedrooms', 'bathrooms', 'area_sqft', 'description', 'amenities', 'legal_documents',
    'services_requested', 'is_featured', 'published_at', 'virtual_tour_url', 'video_url',
])]
class Property extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'legal_documents' => 'array',
            'services_requested' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'price' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_cover', true);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function activeLease()
    {
        return $this->hasOne(Lease::class)->where('status', 'active')->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function propertyPackages()
    {
        return $this->hasMany(PropertyPackage::class);
    }

    public function activePackage()
    {
        return $this->hasOne(PropertyPackage::class)->where('status', 'active')->latestOfMany();
    }

    public function renovationProjects()
    {
        return $this->hasMany(RenovationProject::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
