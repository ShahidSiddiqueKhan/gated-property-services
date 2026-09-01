<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\BlogPost;
use App\Models\ContactSubmission;
use App\Models\Document;
use App\Models\FeeTier;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceUpdate;
use App\Models\Message;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Promotion;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyPackage;
use App\Models\RenovationProject;
use App\Models\Service;
use App\Models\ServiceCatalogItem;
use App\Models\Task;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\Billing\FeeCalculator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $fees = new FeeCalculator();

        // ------------------------------------------------------------------
        // Users
        // ------------------------------------------------------------------
        $admin = User::factory()->create([
            'name' => 'GATED Admin',
            'email' => 'admin@gatedpropertyservices.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+92 321 5381128',
        ]);

        $client = User::factory()->create([
            'name' => 'Talha',
            'email' => 'thetechies804@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'client',
            'phone' => '+92 321 9876543',
            'is_overseas' => false,
        ]);

        $overseasClient = User::factory()->create([
            'name' => 'Ali Raza',
            'email' => 'ali.raza@example.com',
            'password' => bcrypt('password'),
            'role' => 'client',
            'phone' => '+44 7911 123456',
            'country' => 'United Kingdom',
            'is_overseas' => true,
        ]);

        // ------------------------------------------------------------------
        // Services
        // ------------------------------------------------------------------
        $services = [
            [
                'name' => 'Residential Management',
                'icon' => 'home',
                'short_description' => 'End-to-end management for houses, apartments and flats.',
                'description' => 'We handle tenant placement, rent collection, inspections and maintenance coordination so residential owners enjoy hands-off, reliable income.',
                'features' => ['Tenant management', 'Rent collection', 'Property inspections', 'Maintenance coordination'],
            ],
            [
                'name' => 'Commercial Management',
                'icon' => 'building-office',
                'short_description' => 'Full oversight of offices and retail spaces.',
                'description' => 'From lease administration to facilities management, we keep commercial properties fully leased and running smoothly.',
                'features' => ['Office management', 'Retail space management', 'Lease administration', 'Facility maintenance'],
            ],
            [
                'name' => 'Airbnb Management',
                'icon' => 'building-storefront',
                'short_description' => 'Maximise short-let revenue with hands-off hosting.',
                'description' => 'We manage guest communication, listing optimisation, cleaning and turnover, and revenue pricing to keep your short-let performing.',
                'features' => ['Guest communication', 'Listing optimisation', 'Cleaning management', 'Revenue optimisation'],
            ],
            [
                'name' => 'Rent Collection & Reporting',
                'icon' => 'banknotes',
                'short_description' => 'Reliable collection with transparent monthly reporting.',
                'description' => 'Automated reminders, secure collection and detailed statements so you always know where your income stands.',
                'features' => ['Tenant placement', 'Background checks', 'Lease agreements', 'Monthly statements'],
            ],
            [
                'name' => 'Maintenance Management',
                'icon' => 'wrench-screwdriver',
                'short_description' => 'Rapid response repairs and vetted contractors.',
                'description' => 'From emergency repairs to routine upkeep, our contractor network and tracking system keep your property in top condition.',
                'features' => ['Emergency repairs', 'Contractor management', 'Service tracking', '24/7 support'],
            ],
            [
                'name' => 'Overseas Owner Services',
                'icon' => 'globe-alt',
                'short_description' => 'Complete peace of mind for owners abroad.',
                'description' => 'Full property oversight, monthly reporting and financial tracking designed specifically for overseas investors.',
                'features' => ['Full property oversight', 'Monthly reporting', 'Financial tracking', 'Video consultations'],
            ],
            [
                'name' => 'Property Marketing',
                'icon' => 'megaphone',
                'short_description' => 'Professional presentation to attract quality tenants.',
                'description' => 'Professional photography, compelling listings, social promotion and paid advertising to minimise vacancy.',
                'features' => ['Professional photography', 'Listing creation', 'Social media promotion', 'Paid advertising'],
            ],
            [
                'name' => 'Tenant Management',
                'icon' => 'users',
                'short_description' => 'Screening, onboarding and ongoing tenant relations.',
                'description' => 'We screen, onboard and support tenants throughout their stay, keeping relationships professional and issues resolved fast.',
                'features' => ['Tenant screening', 'Onboarding', 'Renewals', 'Dispute resolution'],
            ],
        ];

        foreach ($services as $i => $service) {
            Service::create([
                ...$service,
                'slug' => Str::slug($service['name']),
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        // ------------------------------------------------------------------
        // Revenue engine catalog — packages, payment methods, fee tiers &
        // standalone service pricing (all admin-editable via the Packages /
        // Payment Methods / Fee Tiers / Service Catalog admin screens).
        // ------------------------------------------------------------------
        $packages = [
            'basic' => Package::create([
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Core management for owners who just need reliable rent collection and upkeep.',
                'features' => ['Tenant placement', 'Maintenance coordination', 'Monthly statement'],
                'monthly_price' => 15000,
                'rent_commission_percent' => 0,
                'sort_order' => 1,
                'is_active' => true,
            ]),
            'premium' => Package::create([
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Full-service management with active rent collection and priority support.',
                'features' => ['Everything in Basic', 'Rent collection & disbursement', 'Priority maintenance response', 'Quarterly inspection report'],
                'monthly_price' => 25000,
                'rent_commission_percent' => 8,
                'sort_order' => 2,
                'is_active' => true,
            ]),
            'full_valet' => Package::create([
                'name' => 'Full Valet',
                'slug' => 'full-valet',
                'description' => 'White-glove, hands-off management for owners who want zero involvement.',
                'features' => ['Everything in Premium', 'Concierge-level tenant support', '24/7 emergency response', 'Renovation project oversight'],
                'monthly_price' => 40000,
                'rent_commission_percent' => 10,
                'sort_order' => 3,
                'is_active' => true,
            ]),
        ];

        $paymentMethodsData = [
            ['name' => 'Meezan Bank Transfer', 'code' => 'meezan_bank', 'type' => 'manual', 'region' => 'local', 'icon' => 'banknotes', 'instructions' => 'Account Title: GATED Property Services. IBAN: PK00 MEZN 0000 0123 4567 8901. Include your invoice number as the transfer reference.'],
            ['name' => 'HBL Bank Transfer', 'code' => 'hbl_bank', 'type' => 'manual', 'region' => 'local', 'icon' => 'banknotes', 'instructions' => 'Account Title: GATED Property Services. IBAN: PK00 HABB 0000 1122 3344 5566. Include your invoice number as the transfer reference.'],
            ['name' => 'UBL Bank Transfer', 'code' => 'ubl_bank', 'type' => 'manual', 'region' => 'local', 'icon' => 'banknotes', 'instructions' => 'Account Title: GATED Property Services. IBAN: PK00 UNIL 0000 9988 7766 5544. Include your invoice number as the transfer reference.'],
            ['name' => 'Raast QR', 'code' => 'raast_qr', 'type' => 'manual', 'region' => 'local', 'icon' => 'banknotes', 'instructions' => 'Scan the Raast QR code shared by our finance team and enter the invoice amount exactly.'],
            ['name' => 'Easypaisa', 'code' => 'easypaisa', 'type' => 'manual', 'region' => 'local', 'icon' => 'banknotes', 'instructions' => 'Send to Easypaisa account 0321-5381128 (GATED Property Services) and share the transaction ID with our finance team.'],
            ['name' => 'International Bank Transfer', 'code' => 'intl_bank', 'type' => 'manual', 'region' => 'overseas', 'icon' => 'globe-alt', 'instructions' => 'SWIFT/IBAN details are sent by our finance team on request — please contact support before sending an international wire.'],
            ['name' => 'Wise', 'code' => 'wise', 'type' => 'manual', 'region' => 'overseas', 'icon' => 'globe-alt', 'instructions' => 'Send via Wise to gated-payments@wise.com, using your invoice number as the reference.'],
            ['name' => 'Payoneer', 'code' => 'payoneer', 'type' => 'manual', 'region' => 'overseas', 'icon' => 'globe-alt', 'instructions' => 'Send via Payoneer to the account details shared by our finance team, using your invoice number as the reference.'],
            ['name' => 'Card (Stripe)', 'code' => 'stripe', 'type' => 'gateway', 'region' => 'overseas', 'icon' => 'credit-card', 'instructions' => null],
            ['name' => 'JazzCash', 'code' => 'jazzcash', 'type' => 'gateway', 'region' => 'local', 'icon' => 'banknotes', 'instructions' => null],
            ['name' => 'Safepay', 'code' => 'safepay', 'type' => 'gateway', 'region' => 'both', 'icon' => 'shield-check', 'instructions' => null],
        ];
        foreach ($paymentMethodsData as $i => $pm) {
            PaymentMethod::create([...$pm, 'sort_order' => $i, 'is_active' => true]);
        }

        $feeTiersData = [
            ['category' => 'maintenance', 'min_amount' => 0, 'max_amount' => 50000, 'fee_percent' => 10, 'sort_order' => 1],
            ['category' => 'maintenance', 'min_amount' => 50000, 'max_amount' => 200000, 'fee_percent' => 8, 'sort_order' => 2],
            ['category' => 'maintenance', 'min_amount' => 200000, 'max_amount' => null, 'fee_percent' => 5, 'sort_order' => 3],
            ['category' => 'renovation', 'min_amount' => 0, 'max_amount' => 500000, 'fee_percent' => 12, 'sort_order' => 1],
            ['category' => 'renovation', 'min_amount' => 500000, 'max_amount' => 1500000, 'fee_percent' => 10, 'sort_order' => 2],
            ['category' => 'renovation', 'min_amount' => 1500000, 'max_amount' => null, 'fee_percent' => 8, 'sort_order' => 3],
        ];
        foreach ($feeTiersData as $tier) {
            FeeTier::create($tier);
        }

        $serviceCatalogData = [
            ['category' => 'advertising', 'name' => 'Standard Listing', 'description' => 'Listing creation across GATED and partner property portals.', 'price' => 15000, 'price_max' => 20000, 'sort_order' => 1],
            ['category' => 'advertising', 'name' => 'Professional Photography', 'description' => 'Full property photo shoot for marketing and listings.', 'price' => 10000, 'price_max' => 15000, 'sort_order' => 2],
            ['category' => 'advertising', 'name' => 'Drone Video', 'description' => 'Aerial drone video walkthrough of the property.', 'price' => 20000, 'price_max' => null, 'sort_order' => 3],
            ['category' => 'advertising', 'name' => 'Premium Marketing Package', 'description' => 'Photography, drone video, listing boosts and social promotion bundled.', 'price' => 35000, 'price_max' => 50000, 'sort_order' => 4],
            ['category' => 'emergency', 'name' => 'Lockout Assistance', 'description' => 'Emergency locksmith dispatch for tenant lockouts.', 'price' => 5000, 'price_max' => null, 'sort_order' => 1],
            ['category' => 'emergency', 'name' => 'Emergency Inspection', 'description' => 'Same-day inspection for urgent property issues.', 'price' => 7500, 'price_max' => null, 'sort_order' => 2],
            ['category' => 'emergency', 'name' => 'Night Visit', 'description' => 'After-hours emergency call-out visit.', 'price' => 10000, 'price_max' => null, 'sort_order' => 3],
        ];
        foreach ($serviceCatalogData as $item) {
            ServiceCatalogItem::create([...$item, 'is_active' => true]);
        }

        // ------------------------------------------------------------------
        // Properties
        // ------------------------------------------------------------------
        $propertiesData = [
            [
                'title' => 'Bahria Town House',
                'type' => 'house',
                'category' => 'residential',
                'listing_type' => 'rent',
                'status' => 'occupied',
                'price' => 250000,
                'price_period' => 'month',
                'city' => 'Islamabad',
                'area_location' => 'Bahria Town',
                'size_label' => '1 Kanal',
                'bedrooms' => 5,
                'bathrooms' => 6,
                'area_sqft' => 4500,
                'owner' => $client,
                'featured' => true,
                'description' => 'A spacious 1 Kanal house in Bahria Town, Islamabad, fully managed by GATED with a long-term tenant in place.',
                'image' => 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=1200&q=80',
                'video_url' => 'https://youtu.be/demo-bahria-town-house-walkthrough',
            ],
            [
                'title' => 'DHA Apartment',
                'type' => 'apartment',
                'category' => 'residential',
                'listing_type' => 'rent',
                'status' => 'occupied',
                'price' => 120000,
                'price_period' => 'month',
                'city' => 'Islamabad',
                'area_location' => 'DHA Phase 6',
                'size_label' => '3 Bed',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqft' => 1800,
                'owner' => $client,
                'featured' => true,
                'description' => 'Modern 3-bedroom apartment in DHA Phase 6 with 24/7 security and full GATED management.',
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
                'virtual_tour_url' => 'https://my.matterport.com/show/?m=demo-dha-apartment-tour',
            ],
            [
                'title' => 'Commercial Office',
                'type' => 'office',
                'category' => 'commercial',
                'listing_type' => 'rent',
                'status' => 'vacant',
                'price' => 80000,
                'price_period' => 'month',
                'city' => 'Islamabad',
                'area_location' => 'Gulberg',
                'size_label' => '1200 Sqft',
                'bedrooms' => null,
                'bathrooms' => 1,
                'area_sqft' => 1200,
                'owner' => $client,
                'featured' => true,
                'description' => 'Prime commercial office space in Gulberg, ready for fit-out, currently marketed for lease.',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => '10 Marla House',
                'type' => 'house',
                'category' => 'residential',
                'listing_type' => 'rent',
                'status' => 'vacant',
                'price' => 120000,
                'price_period' => 'month',
                'city' => 'Islamabad',
                'area_location' => 'DHA Phase 6',
                'size_label' => '10 Marla',
                'bedrooms' => 4,
                'bathrooms' => 5,
                'area_sqft' => 2700,
                'owner' => $overseasClient,
                'featured' => true,
                'description' => 'Beautifully maintained 10 Marla house in DHA Phase 6, available for rent with GATED full management.',
                'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Luxury Apartment',
                'type' => 'apartment',
                'category' => 'residential',
                'listing_type' => 'rent',
                'status' => 'vacant',
                'price' => 85000,
                'price_period' => 'month',
                'city' => 'Islamabad',
                'area_location' => 'Bahria Town',
                'size_label' => '3 Bed',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqft' => 1600,
                'owner' => $overseasClient,
                'featured' => false,
                'description' => 'Elegant, fully-furnished luxury apartment in Bahria Town Islamabad.',
                'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Skyline Airbnb Suite',
                'type' => 'airbnb',
                'category' => 'airbnb',
                'listing_type' => 'rent',
                'status' => 'occupied',
                'price' => 15000,
                'price_period' => 'night',
                'city' => 'Islamabad',
                'area_location' => 'Gulberg Greens',
                'size_label' => '2 Bed',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqft' => 1100,
                'owner' => $client,
                'featured' => false,
                'description' => 'Fully managed short-let suite in Islamabad, optimised for Airbnb performance.',
                'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Lake City Residential Plot',
                'type' => 'land',
                'category' => 'residential',
                'listing_type' => 'sale',
                'status' => 'vacant',
                'price' => 25000000,
                'price_period' => 'total',
                'city' => 'Islamabad',
                'area_location' => 'Lake City',
                'size_label' => '10 Marla',
                'bedrooms' => null,
                'bathrooms' => null,
                'area_sqft' => 2250,
                'owner' => $overseasClient,
                'featured' => false,
                'description' => 'A prime residential plot in Lake City, Islamabad, ideal for construction or investment.',
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        $properties = [];

        foreach ($propertiesData as $i => $data) {
            $property = Property::create([
                'reference_no' => 'GPS-' . (1001 + $i),
                'user_id' => $data['owner']->id,
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . (1001 + $i),
                'type' => $data['type'],
                'category' => $data['category'],
                'listing_type' => $data['listing_type'],
                'status' => $data['status'],
                'price' => $data['price'],
                'price_period' => $data['price_period'],
                'address' => $data['area_location'] . ', ' . $data['city'],
                'city' => $data['city'],
                'area_location' => $data['area_location'],
                'size_label' => $data['size_label'],
                'bedrooms' => $data['bedrooms'],
                'bathrooms' => $data['bathrooms'],
                'area_sqft' => $data['area_sqft'],
                'description' => $data['description'],
                'amenities' => ['24/7 Security', 'Backup Power', 'Parking', 'Maintenance Included'],
                'is_featured' => $data['featured'],
                'published_at' => now()->subDays(30 - $i),
                'video_url' => $data['video_url'] ?? null,
                'virtual_tour_url' => $data['virtual_tour_url'] ?? null,
            ]);

            PropertyImage::create([
                'property_id' => $property->id,
                'path' => $data['image'],
                'is_cover' => true,
                'sort_order' => 0,
            ]);

            $properties[$data['title']] = $property;
        }

        // A freshly-submitted registration awaiting admin approval (demonstrates the review workflow).
        Property::create([
            'reference_no' => 'GPS-1099',
            'user_id' => $overseasClient->id,
            'title' => 'Gulberg Greens Villa',
            'slug' => 'gulberg-greens-villa-1099',
            'type' => 'house',
            'category' => 'residential',
            'listing_type' => 'rent',
            'status' => 'pending_review',
            'price' => 180000,
            'price_period' => 'month',
            'address' => 'Gulberg Greens, Islamabad',
            'city' => 'Islamabad',
            'area_location' => 'Gulberg Greens',
            'size_label' => '1 Kanal',
            'bedrooms' => 4,
            'bathrooms' => 4,
            'area_sqft' => 3600,
            'description' => 'Newly submitted property awaiting GATED admin review and approval before going live.',
            'amenities' => ['Backup Power', 'Parking'],
            'is_featured' => false,
            'published_at' => null,
        ]);

        // ------------------------------------------------------------------
        // Property package subscriptions — demonstrates the admin-assigned
        // package/frequency/commission model that drives all downstream fee
        // calculations. Each subscription also raises a paid package-fee
        // invoice, matching the live PropertyPackageController flow.
        // ------------------------------------------------------------------
        $propertyPackageAssignments = [
            ['property' => 'Bahria Town House', 'package' => $packages['full_valet'], 'frequency' => 'quarterly'],
            ['property' => 'DHA Apartment', 'package' => $packages['premium'], 'frequency' => 'monthly'],
            ['property' => 'Skyline Airbnb Suite', 'package' => $packages['premium'], 'frequency' => 'monthly'],
            ['property' => 'Commercial Office', 'package' => $packages['basic'], 'frequency' => 'annually'],
        ];

        $propertyPackages = [];

        // Bahria Town House's quarterly cycle is timed so its renewal falls
        // inside the next 30 days, giving the admin "Upcoming Renewals" report
        // something to show out of the box.
        $packageStartDates = [
            'Bahria Town House' => now()->subMonths(3)->addDays(12),
            'DHA Apartment' => now()->subMonths(5)->startOfMonth(),
            'Skyline Airbnb Suite' => now()->subMonths(4)->startOfMonth(),
            'Commercial Office' => now()->subMonths(6)->startOfMonth(),
        ];

        foreach ($propertyPackageAssignments as $assignment) {
            $property = $properties[$assignment['property']];
            $package = $assignment['package'];
            $pricing = $fees->frequencyPrice((float) $package->monthly_price, $assignment['frequency']);
            $startedAt = $packageStartDates[$assignment['property']];

            $propertyPackage = PropertyPackage::create([
                'property_id' => $property->id,
                'package_id' => $package->id,
                'frequency' => $assignment['frequency'],
                'base_price' => $pricing['base_price'],
                'discount_percent' => $pricing['discount_percent'],
                'final_price' => $pricing['final_price'],
                'commission_percent' => $package->rent_commission_percent,
                'commission_overridden' => false,
                'status' => 'active',
                'started_at' => $startedAt,
                'renews_at' => $startedAt->copy()->addMonths($pricing['months']),
                'created_by' => $admin->id,
            ]);

            Payment::create([
                'invoice_no' => 'INV-PKG-' . str_pad($propertyPackage->id, 4, '0', STR_PAD_LEFT),
                'user_id' => $property->user_id,
                'property_id' => $property->id,
                'type' => 'service',
                'revenue_stream' => Payment::STREAM_PACKAGE_FEE,
                'amount' => $pricing['final_price'],
                'base_amount' => $pricing['gross_price'],
                'fee_percent' => $pricing['discount_percent'],
                'status' => 'paid',
                'due_date' => $startedAt,
                'paid_date' => $startedAt->copy()->addDays(2),
                'method' => 'bank_transfer',
                'property_package_id' => $propertyPackage->id,
                'notes' => "{$package->name} package — " . ucfirst($assignment['frequency']) . ' billing',
            ]);

            $propertyPackages[$assignment['property']] = $propertyPackage;
        }

        // ------------------------------------------------------------------
        // Leases (for occupied properties)
        // ------------------------------------------------------------------
        $lease1 = Lease::create([
            'property_id' => $properties['Bahria Town House']->id,
            'tenant_name' => 'Ahmed Khan',
            'tenant_email' => 'ahmed.khan@example.com',
            'tenant_phone' => '+92 333 1112233',
            'rent_amount' => 250000,
            'start_date' => now()->subMonths(8),
            'status' => 'active',
        ]);

        $lease2 = Lease::create([
            'property_id' => $properties['DHA Apartment']->id,
            'tenant_name' => 'Sara Malik',
            'tenant_email' => 'sara.malik@example.com',
            'tenant_phone' => '+92 300 4445566',
            'rent_amount' => 120000,
            'start_date' => now()->subMonths(5),
            'status' => 'active',
        ]);

        Lease::create([
            'property_id' => $properties['Skyline Airbnb Suite']->id,
            'tenant_name' => 'Short-let Guests (Rolling)',
            'tenant_email' => null,
            'tenant_phone' => null,
            'rent_amount' => 15000,
            'start_date' => now()->subMonths(2),
            'status' => 'active',
        ]);

        // ------------------------------------------------------------------
        // Payments (rent history for client dashboard) — each rent invoice
        // is split into GATED's commission and the owner's net using the
        // property's assigned package commission, via FeeCalculator.
        // ------------------------------------------------------------------
        $invoiceSeq = 1;
        foreach ([$lease1, $lease2] as $lease) {
            $commissionPercent = (float) ($propertyPackages[$lease->property->title]->commission_percent ?? 0);

            for ($m = 5; $m >= 0; $m--) {
                $due = now()->subMonths($m)->startOfMonth()->addDays(4);
                $isCurrentMonth = $m === 0;
                $split = $fees->rentCommission((float) $lease->rent_amount, $commissionPercent);

                Payment::create([
                    'invoice_no' => 'INV-' . str_pad($invoiceSeq++, 5, '0', STR_PAD_LEFT),
                    'user_id' => $lease->property->user_id,
                    'property_id' => $lease->property_id,
                    'lease_id' => $lease->id,
                    'type' => 'rent',
                    'revenue_stream' => $commissionPercent > 0 ? Payment::STREAM_RENT_COMMISSION : null,
                    'amount' => $lease->rent_amount,
                    'base_amount' => $commissionPercent > 0 ? $split['rent_amount'] : null,
                    'fee_percent' => $commissionPercent > 0 ? $split['commission_percent'] : null,
                    'owner_amount' => $commissionPercent > 0 ? $split['owner_amount'] : null,
                    'status' => $isCurrentMonth ? 'due' : 'paid',
                    'method' => $isCurrentMonth ? null : 'bank_transfer',
                    'due_date' => $due,
                    'paid_date' => $isCurrentMonth ? null : $due->copy()->addDays(2),
                ]);
            }
        }

        Payment::create([
            'invoice_no' => 'INV-' . str_pad($invoiceSeq++, 5, '0', STR_PAD_LEFT),
            'user_id' => $client->id,
            'property_id' => $properties['Commercial Office']->id,
            'type' => 'service',
            'revenue_stream' => Payment::STREAM_ADVERTISING,
            'amount' => 15000,
            'status' => 'due',
            'due_date' => now()->addDays(10),
            'notes' => 'Marketing & advertising service fee — Professional Photography',
        ]);

        // Emergency call-out charge, fully GATED revenue.
        Payment::create([
            'invoice_no' => 'INV-' . str_pad($invoiceSeq++, 5, '0', STR_PAD_LEFT),
            'user_id' => $client->id,
            'property_id' => $properties['Bahria Town House']->id,
            'type' => 'service',
            'revenue_stream' => Payment::STREAM_EMERGENCY_SERVICE,
            'amount' => 5000,
            'status' => 'paid',
            'due_date' => now()->subDays(6),
            'paid_date' => now()->subDays(5),
            'method' => 'jazzcash',
            'notes' => 'Lockout Assistance — after-hours call-out',
        ]);

        // Tenant placement fee for Sara Malik's lease, 50% of one month's rent.
        $placementFee = $fees->tenantPlacementFee((float) $lease2->rent_amount, 50);
        Payment::create([
            'invoice_no' => 'INV-' . str_pad($invoiceSeq++, 5, '0', STR_PAD_LEFT),
            'user_id' => $lease2->property->user_id,
            'property_id' => $lease2->property_id,
            'lease_id' => $lease2->id,
            'type' => 'service',
            'revenue_stream' => Payment::STREAM_TENANT_PLACEMENT,
            'amount' => $placementFee['fee_amount'],
            'base_amount' => $placementFee['monthly_rent'],
            'fee_percent' => $placementFee['fee_percent'],
            'status' => 'paid',
            'due_date' => $lease2->start_date,
            'paid_date' => $lease2->start_date->copy()->addDays(3),
            'method' => 'bank_transfer',
            'notes' => "Tenant placement fee — {$lease2->tenant_name}",
        ]);

        // ------------------------------------------------------------------
        // Maintenance requests
        // ------------------------------------------------------------------
        $mr1 = MaintenanceRequest::create([
            'ticket_no' => 'MNT-1001',
            'property_id' => $properties['Bahria Town House']->id,
            'user_id' => $client->id,
            'title' => 'Kitchen tap leaking',
            'category' => 'plumbing',
            'description' => 'The kitchen sink tap has been leaking steadily for two days.',
            'priority' => 'medium',
            'status' => 'in_progress',
            'assigned_to' => 'Bilal (Plumbing Team)',
            'estimated_completion' => now()->addDays(2),
        ]);
        MaintenanceUpdate::create(['maintenance_request_id' => $mr1->id, 'status' => 'submitted', 'note' => 'Request submitted by tenant.', 'created_by' => 'System', 'created_at' => now()->subDays(3)]);
        MaintenanceUpdate::create(['maintenance_request_id' => $mr1->id, 'status' => 'acknowledged', 'note' => 'Maintenance team notified.', 'created_by' => 'GATED Support', 'created_at' => now()->subDays(2)]);
        MaintenanceUpdate::create(['maintenance_request_id' => $mr1->id, 'status' => 'in_progress', 'note' => 'Plumber scheduled for site visit.', 'created_by' => 'Bilal', 'created_at' => now()->subDay()]);

        $mr2 = MaintenanceRequest::create([
            'ticket_no' => 'MNT-1002',
            'property_id' => $properties['DHA Apartment']->id,
            'user_id' => $client->id,
            'title' => 'AC not cooling in living room',
            'category' => 'electrical',
            'description' => 'Living room AC unit is running but not cooling effectively.',
            'priority' => 'high',
            'status' => 'completed',
            'assigned_to' => 'CoolFix Technicians',
            'completed_at' => now()->subDays(4),
        ]);
        MaintenanceUpdate::create(['maintenance_request_id' => $mr2->id, 'status' => 'submitted', 'note' => 'Request submitted.', 'created_by' => 'System', 'created_at' => now()->subDays(10)]);
        MaintenanceUpdate::create(['maintenance_request_id' => $mr2->id, 'status' => 'in_progress', 'note' => 'Technician dispatched, gas refill required.', 'created_by' => 'CoolFix Technicians', 'created_at' => now()->subDays(7)]);
        MaintenanceUpdate::create(['maintenance_request_id' => $mr2->id, 'status' => 'completed', 'note' => 'Gas refilled and unit tested. Issue resolved.', 'created_by' => 'CoolFix Technicians', 'created_at' => now()->subDays(4)]);

        // Contractor invoice logged for the completed job — demonstrates the
        // transparent contractor-cost/GATED-fee/total breakdown.
        $maintenanceFee = $fees->maintenanceFee(35000);
        $maintenancePayment = Payment::create([
            'invoice_no' => 'INV-' . str_pad($invoiceSeq++, 5, '0', STR_PAD_LEFT),
            'user_id' => $mr2->user_id,
            'property_id' => $mr2->property_id,
            'type' => 'maintenance',
            'revenue_stream' => Payment::STREAM_MAINTENANCE_FEE,
            'amount' => $maintenanceFee['total'],
            'base_amount' => $maintenanceFee['contractor_cost'],
            'fee_percent' => $maintenanceFee['fee_percent'],
            'status' => 'paid',
            'due_date' => now()->subDays(4),
            'paid_date' => now()->subDays(2),
            'method' => 'bank_transfer',
            'notes' => "Maintenance coordination — {$mr2->ticket_no}: {$mr2->title}",
        ]);
        $mr2->update([
            'contractor_cost' => $maintenanceFee['contractor_cost'],
            'gated_fee_percent' => $maintenanceFee['fee_percent'],
            'gated_fee_amount' => $maintenanceFee['fee_amount'],
            'total_cost' => $maintenanceFee['total'],
            'payment_id' => $maintenancePayment->id,
        ]);

        MaintenanceRequest::create([
            'ticket_no' => 'MNT-1003',
            'property_id' => $properties['Commercial Office']->id,
            'user_id' => $client->id,
            'title' => 'Main entrance door lock jammed',
            'category' => 'other',
            'description' => 'The main door lock is jammed and needs replacement before new tenants view the office.',
            'priority' => 'emergency',
            'status' => 'submitted',
        ]);

        // ------------------------------------------------------------------
        // Renovation project — full lifecycle example with milestones and a
        // linked GATED management-fee invoice.
        // ------------------------------------------------------------------
        $renovationFee = $fees->renovationFee(1200000);
        $renovation = RenovationProject::create([
            'property_id' => $properties['Bahria Town House']->id,
            'title' => 'Kitchen & Bathroom Renovation',
            'description' => 'Full kitchen remodel and two bathroom upgrades, including fixtures, cabinetry and tiling.',
            'contractor_name' => 'Al-Hamd Construction & Interiors',
            'contractor_contact' => '+92 321 4455667',
            'project_value' => $renovationFee['contractor_cost'],
            'fee_percent' => $renovationFee['fee_percent'],
            'fee_amount' => $renovationFee['fee_amount'],
            'status' => 'in_progress',
            'approval_status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now()->subDays(20),
            'start_date' => now()->subDays(14),
            'expected_completion_date' => now()->addDays(21),
        ]);

        $renovation->milestones()->createMany([
            ['title' => 'Demolition & site prep', 'status' => 'completed', 'due_date' => now()->subDays(7), 'completed_at' => now()->subDays(8), 'sort_order' => 0],
            ['title' => 'Plumbing & electrical rough-in', 'status' => 'in_progress', 'due_date' => now()->addDays(3), 'sort_order' => 1],
            ['title' => 'Cabinetry & fixture install', 'status' => 'pending', 'due_date' => now()->addDays(14), 'sort_order' => 2],
            ['title' => 'Final finishing & walkthrough', 'status' => 'pending', 'due_date' => now()->addDays(21), 'sort_order' => 3],
        ]);

        Payment::create([
            'invoice_no' => 'INV-' . str_pad($invoiceSeq++, 5, '0', STR_PAD_LEFT),
            'user_id' => $properties['Bahria Town House']->user_id,
            'property_id' => $properties['Bahria Town House']->id,
            'type' => 'service',
            'revenue_stream' => Payment::STREAM_RENOVATION_FEE,
            'amount' => $renovation->totalWithFee(),
            'base_amount' => $renovation->project_value,
            'fee_percent' => $renovation->fee_percent,
            'renovation_project_id' => $renovation->id,
            'status' => 'due',
            'due_date' => now()->addDays(5),
            'notes' => "Renovation project management — {$renovation->title}",
        ]);

        // ------------------------------------------------------------------
        // Documents
        // ------------------------------------------------------------------
        Document::create(['user_id' => $client->id, 'property_id' => $properties['Bahria Town House']->id, 'title' => 'Lease Agreement - Bahria Town House', 'type' => 'lease_agreement', 'file_path' => '#', 'uploaded_by' => 'staff']);
        Document::create(['user_id' => $client->id, 'property_id' => $properties['DHA Apartment']->id, 'title' => 'Lease Agreement - DHA Apartment', 'type' => 'lease_agreement', 'file_path' => '#', 'uploaded_by' => 'staff']);
        Document::create(['user_id' => $client->id, 'property_id' => $properties['Bahria Town House']->id, 'title' => 'Property Inspection Report - Q2', 'type' => 'inspection_report', 'file_path' => '#', 'uploaded_by' => 'staff']);
        Document::create(['user_id' => $client->id, 'property_id' => null, 'title' => 'Annual Tax Statement 2025', 'type' => 'tax_document', 'file_path' => '#', 'uploaded_by' => 'staff']);
        Document::create(['user_id' => $client->id, 'property_id' => $properties['Commercial Office']->id, 'title' => 'Invoice - Marketing Service Fee', 'type' => 'invoice', 'file_path' => '#', 'uploaded_by' => 'staff']);

        // ------------------------------------------------------------------
        // Messages / support hub
        // ------------------------------------------------------------------
        Message::create(['user_id' => $client->id, 'subject' => 'Question about rent due date', 'body' => 'Hi, can you confirm the exact due date for this month\'s rent on the DHA Apartment?', 'sender' => 'client', 'status' => 'open', 'is_read' => true]);
        Message::create(['user_id' => $client->id, 'subject' => 'Question about rent due date', 'body' => 'Hi Talha, rent for DHA Apartment is due on the 5th of each month. Let us know if you need anything else!', 'sender' => 'staff', 'status' => 'open', 'is_read' => false, 'parent_id' => 1]);
        Message::create(['user_id' => $client->id, 'subject' => 'New inspection scheduled', 'body' => 'We have scheduled a routine inspection for Bahria Town House next week.', 'sender' => 'staff', 'status' => 'open', 'is_read' => false]);
        Message::create(['user_id' => $client->id, 'subject' => 'Marketing photos ready', 'body' => 'Professional photography for the Commercial Office listing is complete and now live.', 'sender' => 'staff', 'status' => 'open', 'is_read' => false]);

        // ------------------------------------------------------------------
        // Tasks
        // ------------------------------------------------------------------
        Task::create(['user_id' => $client->id, 'property_id' => $properties['Bahria Town House']->id, 'title' => 'Kitchen tap repair', 'description' => 'Fix leaking kitchen tap.', 'status' => 'in_progress', 'assigned_to' => 'Bilal (Plumbing Team)', 'due_date' => now()->addDays(2)]);
        Task::create(['user_id' => $client->id, 'property_id' => $properties['Commercial Office']->id, 'title' => 'Marketing photos & listing', 'description' => 'Professional photography and listing creation for Commercial Office.', 'status' => 'completed', 'assigned_to' => 'Marketing Team', 'due_date' => now()->subDays(3)]);
        Task::create(['user_id' => $client->id, 'property_id' => $properties['DHA Apartment']->id, 'title' => 'Lease renewal review', 'description' => 'Review lease renewal terms with tenant Sara Malik.', 'status' => 'pending', 'assigned_to' => 'Leasing Team', 'due_date' => now()->addDays(20)]);

        // ------------------------------------------------------------------
        // Testimonials
        // ------------------------------------------------------------------
        $testimonials = [
            ['name' => 'Adeel R.', 'role' => 'Overseas Client', 'content' => 'GATED has been a blessing. They manage everything professionally and I get updates regularly.', 'rating' => 5],
            ['name' => 'Sana K.', 'role' => 'Property Owner', 'content' => 'Excellent team, very responsive and trustworthy. Highly recommended.', 'rating' => 4],
            ['name' => 'Harris M.', 'role' => 'Property Investor', 'content' => 'My property is in safe hands with GATED. Great communication and service!', 'rating' => 4],
            ['name' => 'Nida F.', 'role' => 'Property Owner', 'content' => 'Best property management company in Pakistan!', 'rating' => 5],
        ];
        foreach ($testimonials as $t) {
            Testimonial::create([...$t, 'is_featured' => true]);
        }

        // ------------------------------------------------------------------
        // Blog posts
        // ------------------------------------------------------------------
        $posts = [
            ['title' => 'Top Real Estate Trends in Pakistan for 2026', 'category' => 'Real Estate Trends', 'excerpt' => 'What property owners and investors need to know about the shifting market this year.', 'resource_type' => 'article'],
            ['title' => 'A Practical Guide to Property Investment for Overseas Pakistanis', 'category' => 'Property Investment', 'excerpt' => 'How to invest confidently in Pakistani real estate while living abroad.', 'resource_type' => 'guide'],
            ['title' => 'Rental Management 101: Getting the Most from Your Property', 'category' => 'Rental Management', 'excerpt' => 'Practical tips to reduce vacancy and increase rental yield.', 'resource_type' => 'article'],
            ['title' => 'Islamabad Property Market Update: Q2 2026', 'category' => 'Market Updates', 'excerpt' => 'A look at pricing trends across DHA, Bahria Town, and Gulberg.', 'resource_type' => 'video'],
            ['title' => 'Legal Guidance: What Every Landlord in Pakistan Should Know', 'category' => 'Legal Guidance', 'excerpt' => 'Understanding tenancy law, documentation, and your rights as an owner.', 'resource_type' => 'guide'],
            ['title' => 'The GATED Property Owner Handbook', 'category' => 'Owner Resources', 'excerpt' => 'A complete downloadable handbook covering everything new owners need to know.', 'resource_type' => 'download'],
        ];
        foreach ($posts as $i => $p) {
            BlogPost::create([
                'title' => $p['title'],
                'slug' => Str::slug($p['title']),
                'category' => $p['category'],
                'resource_type' => $p['resource_type'],
                'excerpt' => $p['excerpt'],
                'body' => "<p>{$p['excerpt']}</p><p>This article covers key insights GATED Property Services shares with our clients to help them make informed, confident decisions about their property portfolio.</p><p>For a tailored consultation, reach out to our team any time through the Client Portal or our contact page.</p>",
                'author' => 'GATED Property Services',
                'published_at' => now()->subDays(($i + 1) * 6),
            ]);
        }

        // ------------------------------------------------------------------
        // Contact submissions (sample)
        // ------------------------------------------------------------------
        ContactSubmission::create(['name' => 'Fatima Noor', 'email' => 'fatima.noor@example.com', 'phone' => '+92 301 2223344', 'subject' => 'Property management inquiry', 'message' => 'I would like to know more about your overseas owner services.', 'type' => 'consultation']);
        ContactSubmission::create(['name' => 'Usman Tariq', 'email' => 'usman.tariq@example.com', 'phone' => '+92 333 5556677', 'subject' => 'Call back request', 'message' => 'Please call me back regarding commercial property management rates.', 'type' => 'callback']);

        // ------------------------------------------------------------------
        // Promotions
        // ------------------------------------------------------------------
        Promotion::create([
            'title' => 'New Client Offer',
            'discount_label' => '20% Off First Month\'s Management Fee',
            'description' => 'Sign up for full property management before the end of the month and save on your first invoice.',
            'valid_until' => now()->addMonths(2),
            'is_active' => true,
        ]);

        // ------------------------------------------------------------------
        // Audit log (sample admin activity)
        // ------------------------------------------------------------------
        AuditLog::record($admin, 'Approved property', $properties['Bahria Town House'], 'Approved & published Bahria Town House');
        AuditLog::record($admin, 'Confirmed payment', null, 'Confirmed invoice INV-00001 as paid');
        AuditLog::record($admin, 'Updated maintenance request', $mr2, 'Set MNT-1002 to completed');
        AuditLog::record($admin, 'Replied to message', null, 'Replied to thread: Question about rent due date');
    }
}
