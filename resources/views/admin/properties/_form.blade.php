@php
    $property = $property ?? null;
@endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Title</label>
        <input type="text" name="title" value="{{ old('title', $property?->title) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Owner (Client)</label>
        <select name="user_id" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <option value="">Unassigned</option>
            @foreach ($clients as $c)
                <option value="{{ $c->id }}" @selected(old('user_id', $property?->user_id) == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Type</label>
        <select name="type" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach (['house' => 'House', 'apartment' => 'Apartment', 'flat' => 'Flat', 'commercial' => 'Commercial', 'office' => 'Office', 'airbnb' => 'Airbnb', 'vacation_rental' => 'Vacation Rental', 'land' => 'Land'] as $val => $label)
                <option value="{{ $val }}" @selected(old('type', $property?->type) === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Category</label>
        <select name="category" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach (['residential' => 'Residential', 'commercial' => 'Commercial', 'airbnb' => 'Airbnb'] as $val => $label)
                <option value="{{ $val }}" @selected(old('category', $property?->category) === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Listing Type</label>
        <select name="listing_type" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <option value="rent" @selected(old('listing_type', $property?->listing_type) === 'rent')>For Rent</option>
            <option value="sale" @selected(old('listing_type', $property?->listing_type) === 'sale')>For Sale</option>
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Status</label>
        <select name="status" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach (['pending_review' => 'Pending Review', 'vacant' => 'Vacant', 'occupied' => 'Occupied', 'maintenance' => 'Under Maintenance'] as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $property?->status) === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">City</label>
        <input type="text" name="city" value="{{ old('city', $property?->city) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Area / Location</label>
        <input type="text" name="area_location" value="{{ old('area_location', $property?->area_location) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Address</label>
        <input type="text" name="address" value="{{ old('address', $property?->address) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Size Label</label>
        <input type="text" name="size_label" value="{{ old('size_label', $property?->size_label) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Area (Sqft)</label>
        <input type="number" name="area_sqft" value="{{ old('area_sqft', $property?->area_sqft) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Bedrooms</label>
        <input type="number" name="bedrooms" value="{{ old('bedrooms', $property?->bedrooms) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Bathrooms</label>
        <input type="number" name="bathrooms" value="{{ old('bathrooms', $property?->bathrooms) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Price (PKR)</label>
        <input type="number" name="price" value="{{ old('price', $property?->price) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Price Period</label>
        <select name="price_period" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <option value="month" @selected(old('price_period', $property?->price_period) === 'month')>Per Month</option>
            <option value="night" @selected(old('price_period', $property?->price_period) === 'night')>Per Night</option>
            <option value="total" @selected(old('price_period', $property?->price_period) === 'total')>Total (Sale)</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Description</label>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description', $property?->description) }}</textarea>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Virtual Tour URL</label>
        <input type="url" name="virtual_tour_url" value="{{ old('virtual_tour_url', $property?->virtual_tour_url) }}" placeholder="https://..." class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Video Walkthrough URL</label>
        <input type="url" name="video_url" value="{{ old('video_url', $property?->video_url) }}" placeholder="https://youtube.com/..." class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700 block mb-2">Photos</label>
        <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm">
    </div>
    <div class="flex items-center gap-6 sm:col-span-2">
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $property?->is_featured)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Featured on homepage
        </label>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $property?->published_at)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Published (visible publicly)
        </label>
    </div>
</div>
