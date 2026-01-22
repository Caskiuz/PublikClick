<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'currency',
        'referral_code',
        'referred_by',
        'current_package_id',
        'current_rank_id',
        'package_purchased_at',
        'bank_info',
        'is_active',
        'is_admin',
        'nequi_phone'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'bank_info' => 'array',
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
            'package_purchased_at' => 'datetime'
        ];
    }

    // Relaciones
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function activeReferrals()
    {
        return $this->referrals()->where('is_active', true)->whereNotNull('current_package_id');
    }

    public function currentPackage()
    {
        return $this->belongsTo(Package::class, 'current_package_id');
    }

    public function currentRank()
    {
        return $this->belongsTo(Rank::class, 'current_rank_id');
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function withdrawalWallet()
    {
        return $this->hasOne(Wallet::class)->where('type', Wallet::TYPE_WITHDRAWAL);
    }

    public function donationWallet()
    {
        return $this->hasOne(Wallet::class)->where('type', Wallet::TYPE_DONATION);
    }

    public function adClicks()
    {
        return $this->hasMany(UserAdClick::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function megaAds()
    {
        return $this->hasMany(MegaAd::class);
    }

    // Métodos de negocio para clicks
    public function getTodayClicksCount()
    {
        return \Cache::remember('user_clicks_today_' . $this->id, 300, function() {
            return $this->adClicks()->whereDate('clicked_at', today())->count();
        });
    }

    public function getTodayMiniAdsClicks()
    {
        return \Cache::remember('user_mini_clicks_today_' . $this->id, 300, function() {
            return $this->adClicks()->whereDate('clicked_at', today())
                       ->where('ad_type', 'mini')->count();
        });
    }

    public function canClickMainAds()
    {
        $mainClicks = $this->adClicks()->whereDate('clicked_at', today())
                          ->where('ad_type', 'main')->count();
        return $mainClicks < 5;
    }

    public function canClickMiniAds()
    {
        if (!$this->currentRank) return false;
        
        $miniClicks = $this->getTodayMiniAdsClicks();
        return $miniClicks < $this->currentRank->mini_ads_daily;
    }

    // Métodos de rangos
    public function updateRank()
    {
        $activeReferralsCount = $this->activeReferrals()->count();
        $newRank = Rank::getRankByReferrals($activeReferralsCount);
        
        if ($newRank && $this->current_rank_id !== $newRank->id) {
            $oldRank = $this->currentRank;
            $this->current_rank_id = $newRank->id;
            $this->save();
            
            // Crear mega ads para el nuevo mes si es necesario
            MegaAd::getOrCreateForUser($this->id);
            
            return [
                'old_rank' => $oldRank,
                'new_rank' => $newRank,
                'promoted' => true
            ];
        }
        
        return ['promoted' => false];
    }

    public function getActiveReferralsCount()
    {
        return $this->activeReferrals()->count();
    }

    // Métodos de ganancias
    public function calculateMainAdEarnings()
    {
        if (!$this->currentPackage) return 0;
        
        // Usar valores de la base de datos si existen
        if (isset($this->currentPackage->main_ad_value)) {
            return $this->currentPackage->main_ad_value;
        }
        
        // Fallback a valores según el ROADMAP
        $packageEarnings = [
            25 => 400,
            50 => 600,
            100 => 1120,
            150 => 1600,
            200 => 1800  // Elite según ROADMAP
        ];
        
        return $packageEarnings[$this->currentPackage->price_usd] ?? 0;
    }

    public function calculateMiniAdEarnings()
    {
        if (!$this->currentPackage) return 0;
        
        // Usar valores de la base de datos si existen
        if (isset($this->currentPackage->mini_ad_value)) {
            return $this->currentPackage->mini_ad_value;
        }
        
        // Fallback a valores según el ROADMAP
        $packageEarnings = [
            25 => 83.33,
            50 => 425,
            100 => 100,
            150 => 600,
            200 => 800  // Elite según ROADMAP
        ];
        
        return $packageEarnings[$this->currentPackage->price_usd] ?? 0;
    }

    public function clickMainAd($adId)
    {
        if (!$this->canClickMainAds()) {
            throw new \Exception('Ya has completado tus 5 clicks diarios');
        }
        
        $earnings = $this->calculateMainAdEarnings();
        $donationAmount = 10; // Fijo $10 para donación
        $withdrawalAmount = $earnings - $donationAmount;
        
        // Crear click
        $click = $this->adClicks()->create([
            'ad_id' => $adId,
            'ad_type' => 'main',
            'clicked_at' => now(),
            'earnings' => $earnings,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        // Distribuir ganancias
        $this->withdrawalWallet->addFunds($withdrawalAmount, "Click en anuncio principal");
        $this->donationWallet->addFunds($donationAmount, "Donación por click principal");
        
        // Pagar comisión al referidor
        if ($this->referrer && $this->referrer->currentRank) {
            $commission = $this->referrer->currentRank->referral_commission;
            $this->referrer->withdrawalWallet->addFunds($commission, "Comisión por click de referido");
        }
        
        return $click;
    }

    public function clickMiniAd()
    {
        if (!$this->canClickMiniAds()) {
            throw new \Exception('No tienes mini-anuncios disponibles');
        }
        
        $earnings = $this->calculateMiniAdEarnings();
        
        // Crear click
        $click = $this->adClicks()->create([
            'ad_id' => null,
            'ad_type' => 'mini',
            'clicked_at' => now(),
            'earnings' => $earnings,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        // Agregar a cartera de retiro
        $this->withdrawalWallet->addFunds($earnings, "Click en mini-anuncio");
        
        return $click;
    }

    public function getCurrentMegaAd()
    {
        return MegaAd::getOrCreateForUser($this->id);
    }

    // Métodos de utilidad
    public function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('referral_code', $code)->exists());
        
        return $code;
    }

    public function getTotalEarnings()
    {
        $withdrawal = $this->withdrawalWallet->total_earned ?? 0;
        $donation = $this->donationWallet->total_earned ?? 0;
        return $withdrawal + $donation;
    }

    public function getAvailableBalance()
    {
        return $this->withdrawalWallet->balance ?? 0;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($user) {
            // Generar código de referido
            if (!$user->referral_code) {
                $user->referral_code = $user->generateReferralCode();
                $user->save();
            }
            
            // Crear carteras
            Wallet::createUserWallets($user->id);
            
            // Asignar rango inicial
            $initialRank = Rank::getRankByReferrals(0);
            if ($initialRank) {
                $user->current_rank_id = $initialRank->id;
                $user->save();
            }
            
            // Enviar email de bienvenida
            try {
                \Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user));
            } catch (\Exception $e) {
                \Log::info('Email no enviado: ' . $e->getMessage());
            }
        });
    }
}
