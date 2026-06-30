<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ──────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Store Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Super Admin');

        // ── Categories ──────────────────────────────────────────────────
        $categoryData = [
            ['name' => 'Burgers',  'description' => 'Juicy grilled burgers stacked your way.'],
            ['name' => 'Pizza',    'description' => 'Hand-tossed pizzas baked fresh to order.'],
            ['name' => 'Sides',    'description' => 'Fries, wings, and shareable bites.'],
            ['name' => 'Drinks',   'description' => 'Sodas, shakes, and fresh juices.'],
            ['name' => 'Desserts', 'description' => 'Sweet treats to finish your meal.'],
            ['name' => 'Salads',   'description' => 'Light, fresh, and full of flavor.'],
        ];

        $categories = collect($categoryData)->map(
            fn ($cat) => Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'status' => true,
                ]
            )
        );

        // ── Products ────────────────────────────────────────────────────
        $productsByCategory = [
            'Burgers' => [
                ['Classic Cheeseburger', 8.99, 'Beef patty, cheddar, lettuce, tomato, house sauce.'],
                ['Double Bacon Burger', 12.49, 'Two patties, crispy bacon, smoked cheddar, BBQ sauce.'],
                ['Spicy Jalapeño Burger', 10.99, 'Pepper-jack cheese, fresh jalapeños, chipotle mayo.'],
                ['Mushroom Swiss Burger', 11.49, 'Sautéed mushrooms, melted Swiss, garlic aioli.'],
                ['Veggie Burger', 9.49, 'Plant-based patty, avocado, sprouts, vegan mayo.'],
            ],
            'Pizza' => [
                ['Margherita Pizza', 13.99, 'San Marzano tomato, fresh mozzarella, basil.'],
                ['Pepperoni Pizza', 15.49, 'Double pepperoni, mozzarella, oregano.'],
                ['BBQ Chicken Pizza', 16.99, 'Grilled chicken, red onion, smoky BBQ sauce.'],
                ['Four Cheese Pizza', 15.99, 'Mozzarella, gorgonzola, parmesan, provolone.'],
            ],
            'Sides' => [
                ['Crispy French Fries', 4.49, 'Golden fries, sea salt.'],
                ['Buffalo Wings (8pc)', 9.99, 'Crispy wings tossed in buffalo sauce.'],
                ['Onion Rings', 5.49, 'Beer-battered onion rings, ranch dip.'],
                ['Mozzarella Sticks', 6.49, 'Breaded mozzarella, marinara sauce.'],
            ],
            'Drinks' => [
                ['Classic Cola', 2.99, 'Ice-cold cola, 16oz.'],
                ['Strawberry Milkshake', 5.49, 'Creamy shake topped with whipped cream.'],
                ['Fresh Orange Juice', 3.99, 'Cold-pressed, no added sugar.'],
                ['Iced Lemon Tea', 3.49, 'Refreshing house-brewed iced tea.'],
            ],
            'Desserts' => [
                ['Chocolate Brownie', 4.99, 'Warm fudge brownie, vanilla drizzle.'],
                ['New York Cheesecake', 6.49, 'Classic creamy cheesecake, berry compote.'],
                ['Apple Pie Slice', 4.49, 'Cinnamon-spiced apple pie, flaky crust.'],
            ],
            'Salads' => [
                ['Caesar Salad', 7.99, 'Romaine, parmesan, croutons, Caesar dressing.'],
                ['Grilled Chicken Salad', 9.99, 'Mixed greens, grilled chicken, balsamic vinaigrette.'],
            ],
        ];

        $allProducts = collect();

        foreach ($categories as $category) {
            $items = $productsByCategory[$category->name] ?? [];

            foreach ($items as [$name, $price, $description]) {
                $hasDiscount = fake()->boolean(30);

                $product = Product::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'sku' => 'SKU-' . strtoupper(Str::random(6)),
                        'description' => $description,
                        'price' => $price,
                        'discount_price' => $hasDiscount ? round($price * 0.8, 2) : null,
                        'stock' => fake()->numberBetween(0, 80),
                        'featured' => fake()->boolean(25),
                        'status' => true,
                    ]
                );

                $allProducts->push($product);
            }
        }

        // ── Sample customers ────────────────────────────────────────────
        $customerNames = [
            'Alice Johnson', 'Marcus Lee', 'Priya Patel', 'Diego Fernandez',
            'Sofia Rossi', 'James Walker', 'Mei Tanaka', 'Olivia Brown',
        ];

        $customers = collect($customerNames)->map(function ($name, $i) {
            $email = Str::slug($name, '.') . '@example.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            if (!$user->hasAnyRole(['Super Admin', 'Admin', 'Manager', 'Staff'])) {
                $user->assignRole('Customer');
            }
            return $user;
        });

        // ── Reviews ──────────────────────────────────────────────────────
        $allProducts->random(min(12, $allProducts->count()))->each(function ($product) use ($customers) {
            Review::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $customers->random()->id,
                ],
                [
                    'rating' => fake()->numberBetween(3, 5),
                    'title' => fake()->sentence(4),
                    'body' => fake()->paragraph(2),
                    'is_approved' => true,
                ]
            );
        });

        // ── Wishlists ────────────────────────────────────────────────────
        $customers->each(function ($customer) use ($allProducts) {
            $picks = $allProducts->random(min(3, $allProducts->count()));
            foreach ($picks as $product) {
                Wishlist::firstOrCreate([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                ]);
            }
        });

        // ── Orders ───────────────────────────────────────────────────────
        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        foreach ($customers as $customer) {
            $orderCount = fake()->numberBetween(1, 4);

            for ($i = 0; $i < $orderCount; $i++) {
                $items = $allProducts->random(fake()->numberBetween(1, 4));
                $subtotal = 0;

                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'user_id' => $customer->id,
                    'subtotal' => 0,
                    'tax' => 0,
                    'shipping_fee' => 5,
                    'total' => 0,
                    'status' => fake()->randomElement($statuses),
                    'payment_status' => fake()->randomElement($paymentStatuses),
                    'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'cash_on_delivery']),
                    'shipping_address' => fake()->address(),
                ]);

                // created_at isn't mass-assignable; backdate it directly so
                // demo orders are spread across the last 45 days.
                $order->created_at = now()->subDays(fake()->numberBetween(0, 45));
                $order->save();

                foreach ($items as $product) {
                    $unitPrice = $product->discount_price ?? $product->price;
                    $quantity = fake()->numberBetween(1, 3);
                    $lineTotal = $unitPrice * $quantity;
                    $subtotal += $lineTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $lineTotal,
                    ]);
                }

                $tax = round($subtotal * 0.1, 2);
                $order->update([
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $subtotal + $tax + $order->shipping_fee,
                ]);
            }
        }

        $this->command?->info('Demo data seeded: '
            . $categories->count() . ' categories, '
            . $allProducts->count() . ' products, '
            . $customers->count() . ' customers, plus orders, reviews & wishlists.');
        $this->command?->warn('Admin login: admin@example.com / password');
        $this->command?->warn('Customer login (e.g.): alice.johnson@example.com / password');
    }
}
