<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Pack;
use App\Models\Media;
use App\Models\Purchase;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ──
        User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ── Categories ──
        $categories = [
            ['name' => 'Ensaio Sensual', 'slug' => 'ensaio-sensual', 'icon' => '🔥', 'sort_order' => 1],
            ['name' => 'Fitness', 'slug' => 'fitness', 'icon' => '💪', 'sort_order' => 2],
            ['name' => 'Cosplay', 'slug' => 'cosplay', 'icon' => '🎭', 'sort_order' => 3],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'icon' => '✨', 'sort_order' => 4],
            ['name' => 'Arte & Fotografia', 'slug' => 'arte-fotografia', 'icon' => '📸', 'sort_order' => 5],
            ['name' => 'Moda', 'slug' => 'moda', 'icon' => '👗', 'sort_order' => 6],
            ['name' => 'Beleza', 'slug' => 'beleza', 'icon' => '💄', 'sort_order' => 7],
            ['name' => 'Exclusivo', 'slug' => 'exclusivo', 'icon' => '💎', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ── Demo Creators ──
        $creators = [
            [
                'name' => 'Isabella Santos',
                'username' => 'isabellasantos',
                'email' => 'isabella@demo.com',
                'password' => Hash::make('demo123'),
                'role' => 'creator',
                'bio' => 'Fotógrafa e modelo profissional. Conteúdo exclusivo de ensaios sensuais e lifestyle. 📸✨',
                'subscription_price' => 29.90,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carolina Mendes',
                'username' => 'carolmendes',
                'email' => 'carol@demo.com',
                'password' => Hash::make('demo123'),
                'role' => 'creator',
                'bio' => 'Personal trainer e influenciadora fitness. Dietas, treinos e ensaios exclusivos. 💪🔥',
                'subscription_price' => 39.90,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Valentina Costa',
                'username' => 'valcosta',
                'email' => 'valentina@demo.com',
                'password' => Hash::make('demo123'),
                'role' => 'creator',
                'bio' => 'Cosplayer profissional e artista. Os melhores cosplays e ensaios temáticos! 🎭💜',
                'subscription_price' => 24.90,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fernanda Lima',
                'username' => 'fernadalima',
                'email' => 'fernanda@demo.com',
                'password' => Hash::make('demo123'),
                'role' => 'creator',
                'bio' => 'Modelo e criadora de conteúdo. Moda, beleza e muito mais. 👗💄',
                'subscription_price' => 34.90,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($creators as $creatorData) {
            $creator = User::create($creatorData);

            // Create packs for each creator
            $packTitles = [
                ['Ensaio Verão 2024', 'ensaio-sensual', 'Ensaio fotográfico exclusivo de verão com 25 fotos em alta resolução.', 49.90],
                ['Pack Premium Exclusivo', 'exclusivo', 'Conteúdo premium com fotos e vídeos exclusivos nunca publicados.', 79.90],
                ['Behind the Scenes', 'lifestyle', 'Bastidores dos meus ensaios mais icônicos com 30+ fotos.', 39.90],
                ['Ensaio Noturno', 'ensaio-sensual', 'Fotos artísticas em ambientação noturna. Iluminação cinematográfica.', 59.90],
                ['Coleção Gold', 'exclusivo', 'Minha coleção mais especial com conteúdos inéditos.', 89.90],
                ['Lifestyle Diary', 'lifestyle', 'Acompanhe meu dia a dia com fotos espontâneas e íntimas.', 29.90],
            ];

            foreach ($packTitles as $index => $packInfo) {
                $category = Category::where('slug', $packInfo[1])->first();
                Pack::create([
                    'user_id' => $creator->id,
                    'category_id' => $category->id,
                    'title' => $packInfo[0],
                    'slug' => Str::slug($packInfo[0]) . '-' . Str::random(6),
                    'description' => $packInfo[2],
                    'price' => $packInfo[3],
                    'is_active' => true,
                    'is_featured' => $index < 2,
                    'views_count' => rand(50, 500),
                    'downloads_count' => rand(10, 100),
                    'media_count' => rand(10, 30),
                ]);
            }
        }

        // ── Demo Customer ──
        $customer = User::create([
            'name' => 'João Cliente',
            'username' => 'joaocliente',
            'email' => 'cliente@demo.com',
            'password' => Hash::make('demo123'),
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create some demo purchases
        $somePacks = Pack::inRandomOrder()->limit(3)->get();
        foreach ($somePacks as $pack) {
            Purchase::create([
                'user_id' => $customer->id,
                'pack_id' => $pack->id,
                'amount_paid' => $pack->price,
                'status' => 'confirmed',
            ]);
        }

        // Create a demo subscription
        $firstCreator = User::where('role', 'creator')->first();
        if ($firstCreator) {
            Subscription::create([
                'subscriber_id' => $customer->id,
                'creator_id' => $firstCreator->id,
                'status' => 'active',
                'amount' => $firstCreator->subscription_price,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ]);
        }
    }
}
