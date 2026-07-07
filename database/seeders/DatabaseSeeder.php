<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\BlogPost;
use App\Models\ContactSubmission;
use App\Models\Document;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceUpdate;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Service;
use App\Models\Task;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ------------------------------------------------------------------
        // Users
        // ------------------------------------------------------------------
        $admin = User::factory()->create([
            'name' => 'GATED Admin',
            'email' => 'admin@gatedpropertyservices.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+92 300 1234567',
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
                'city' => 'Lahore',
                'area_location' => 'Bahria Town',
                'size_label' => '1 Kanal',
                'bedrooms' => 5,
                'bathrooms' => 6,
                'area_sqft' => 4500,
                'owner' => $client,
                'featured' => true,
                'description' => 'A spacious 1 Kanal house in Bahria Town, Lahore, fully managed by GATED with a long-term tenant in place.',
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
                'city' => 'Lahore',
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
                'city' => 'Lahore',
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
                'city' => 'Lahore',
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
                'city' => 'Lahore',
                'area_location' => 'Bahria Town',
                'size_label' => '3 Bed',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqft' => 1600,
                'owner' => $overseasClient,
                'featured' => false,
                'description' => 'Elegant, fully-furnished luxury apartment in Bahria Town Lahore.',
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
                'city' => 'Lahore',
                'area_location' => 'Lake City',
                'size_label' => '10 Marla',
                'bedrooms' => null,
                'bathrooms' => null,
                'area_sqft' => 2250,
                'owner' => $overseasClient,
                'featured' => false,
                'description' => 'A prime residential plot in Lake City, Lahore, ideal for construction or investment.',
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
        // Payments (rent history for client dashboard)
        // ------------------------------------------------------------------
        $invoiceSeq = 1;
        foreach ([$lease1, $lease2] as $lease) {
            for ($m = 5; $m >= 0; $m--) {
                $due = now()->subMonths($m)->startOfMonth()->addDays(4);
                $isCurrentMonth = $m === 0;
                Payment::create([
                    'invoice_no' => 'INV-' . str_pad($invoiceSeq++, 5, '0', STR_PAD_LEFT),
                    'user_id' => $lease->property->user_id,
                    'property_id' => $lease->property_id,
                    'lease_id' => $lease->id,
                    'type' => 'rent',
                    'amount' => $lease->rent_amount,
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
            'amount' => 15000,
            'status' => 'due',
            'due_date' => now()->addDays(10),
            'notes' => 'Marketing & advertising service fee',
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
            ['title' => 'Lahore Property Market Update: Q2 2026', 'category' => 'Market Updates', 'excerpt' => 'A look at pricing trends across DHA, Bahria Town, and Gulberg.', 'resource_type' => 'video'],
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
