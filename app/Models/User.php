<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp',
        'city',
        'state',
        'address',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Role Check Helpers
     */
    public function isAdminUser(): bool
    {
        return $this->is_active && in_array($this->role, [
            'super_admin',
            'admin',
            'booking_manager',
            'content_manager',
        ]);
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_active && $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->is_active && in_array($this->role, ['super_admin', 'admin']);
    }

    public function canManageBookings(): bool
    {
        return $this->is_active && in_array($this->role, [
            'super_admin',
            'admin',
            'booking_manager',
        ]);
    }

    public function canManageContent(): bool
    {
        return $this->is_active && in_array($this->role, [
            'super_admin',
            'admin',
            'content_manager',
        ]);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Administrator',
            'admin' => 'Administrator',
            'booking_manager' => 'Booking Operations Manager',
            'content_manager' => 'Content & Catalog Manager',
            'customer' => 'Customer',
            default => ucfirst(str_replace('_', ' ', $this->role ?? 'Customer')),
        };
    }

    /**
     * Relationships
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class)->orderBy('created_at', 'desc');
    }

    public function cancellationRequests()
    {
        return $this->hasMany(BookingCancellationRequest::class);
    }

    public function rescheduleRequests()
    {
        return $this->hasMany(BookingRescheduleRequest::class);
    }

    public function quoteRequests()
    {
        return $this->hasMany(QuoteRequest::class)->orderBy('created_at', 'desc');
    }

    public function activityLogs()
    {
        return $this->hasMany(AdminActivityLog::class)->orderBy('created_at', 'desc');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class)->orderBy('created_at', 'desc');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('payment_date', 'desc');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->orderBy('issued_at', 'desc');
    }
}
