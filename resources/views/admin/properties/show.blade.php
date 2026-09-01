@extends('layouts.admin')

@section('title', $property->title)
@section('subtitle', 'Ref: ' . $property->reference_no)

@section('content')

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.properties.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Properties</a>
        <div class="flex gap-2">
            @if ($property->status === 'pending_review')
                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                    @csrf
                    <button type="submit" class="btn-primary !py-2 !px-4 text-sm">Approve &amp; Publish</button>
                </form>
            @endif
            <a href="{{ route('admin.properties.edit', $property) }}" class="btn-outline !py-2 !px-4 text-sm">Edit</a>
            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" onsubmit="return confirm('Delete this property permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-outline !py-2 !px-4 text-sm !border-brand-600 !text-brand-600 hover:!bg-brand-600 hover:!text-white">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-4">
                    @php $statusColors = ['occupied' => 'bg-emerald-100 text-emerald-700', 'vacant' => 'bg-amber-100 text-amber-700', 'maintenance' => 'bg-blue-100 text-blue-700', 'pending_review' => 'bg-brand-100 text-brand-700']; @endphp
                    <span class="badge {{ $statusColors[$property->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$property->status)) }}</span>
                    @if ($property->is_featured)<span class="badge bg-brand-600 text-white">Featured</span>@endif
                    <span class="badge bg-ink-100 text-ink-600">{{ $property->published_at ? 'Published' : 'Not Published' }}</span>
                </div>
                <h2 class="font-heading font-bold text-xl text-ink-900">{{ $property->title }}</h2>
                <p class="text-sm text-ink-500 mt-1">{{ $property->address }} &middot; Owner: {{ $property->owner?->name ?? 'Unassigned' }}</p>
                <p class="mt-4 text-sm text-ink-600 leading-relaxed">{{ $property->description }}</p>

                @if ($property->images->count())
                    <div class="mt-5 grid grid-cols-4 gap-2">
                        @foreach ($property->images as $img)
                            <img src="{{ \App\Support\Media::url($img->path) }}" class="rounded-lg aspect-square object-cover">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-4">Leases</h3>
                @forelse ($property->leases as $lease)
                    <div class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm">
                        <span>{{ $lease->tenant_name }} &middot; PKR {{ number_format($lease->rent_amount) }}</span>
                        <span class="badge bg-ink-100 text-ink-600">{{ ucfirst($lease->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-ink-500">No lease records.</p>
                @endforelse
                <a href="{{ route('admin.leases.create') }}" class="btn-outline mt-4 text-sm !py-2 !px-4">Add Lease</a>
            </div>

            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-heading font-bold text-ink-900">Renovation Projects</h3>
                    <a href="{{ route('admin.renovations.create', ['property_id' => $property->id]) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">+ New Project</a>
                </div>
                @forelse ($property->renovationProjects as $renovation)
                    <a href="{{ route('admin.renovations.show', $renovation) }}" class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm hover:bg-ink-50/50">
                        <span>{{ $renovation->title }}</span>
                        <span class="badge bg-ink-100 text-ink-600">{{ ucfirst(str_replace('_',' ',$renovation->status)) }}</span>
                    </a>
                @empty
                    <p class="text-sm text-ink-500">No renovation projects.</p>
                @endforelse
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-4">Maintenance History</h3>
                @forelse ($property->maintenanceRequests as $mr)
                    <a href="{{ route('admin.maintenance.show', $mr) }}" class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm hover:bg-ink-50/50">
                        <span>{{ $mr->title }}</span>
                        <span class="badge bg-ink-100 text-ink-600">{{ ucfirst(str_replace('_',' ',$mr->status)) }}</span>
                    </a>
                @empty
                    <p class="text-sm text-ink-500">No maintenance history.</p>
                @endforelse
            </div>
        </div>

        <aside class="space-y-6">
            <div class="card p-6">
                <div class="font-heading font-extrabold text-2xl text-brand-600">PKR {{ number_format($property->price) }}<span class="text-sm font-normal text-ink-400">/{{ $property->price_period }}</span></div>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-ink-500">Type</dt><dd class="text-ink-800 capitalize">{{ str_replace('_',' ',$property->type) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Bedrooms</dt><dd class="text-ink-800">{{ $property->bedrooms ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Bathrooms</dt><dd class="text-ink-800">{{ $property->bathrooms ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Size</dt><dd class="text-ink-800">{{ $property->size_label ?? '—' }}</dd></div>
                </dl>
                @if ($property->published_at)
                    <a href="{{ route('properties.show', $property) }}" target="_blank" class="btn-outline w-full mt-4 justify-center text-sm">View Public Listing</a>
                @endif
            </div>

            <div class="card p-6" x-data="{ editing: false }">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-heading font-bold text-ink-900 text-sm">Package &amp; Billing</h3>
                    @if ($property->activePackage)
                        <button type="button" @click="editing = !editing" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Change</button>
                    @endif
                </div>

                @if ($property->activePackage)
                    <div x-show="!editing">
                        <div class="flex items-center justify-between">
                            <span class="font-heading font-bold text-ink-900">{{ $property->activePackage->package->name }}</span>
                            <span class="badge bg-emerald-100 text-emerald-700 capitalize">{{ $property->activePackage->frequency }}</span>
                        </div>
                        <dl class="mt-3 space-y-1.5 text-sm">
                            <div class="flex justify-between"><dt class="text-ink-500">Billed amount</dt><dd class="text-ink-800 font-semibold">PKR {{ number_format($property->activePackage->final_price, 0) }}</dd></div>
                            @if ($property->activePackage->discount_percent > 0)
                                <div class="flex justify-between"><dt class="text-ink-500">Frequency discount</dt><dd class="text-emerald-600">{{ rtrim(rtrim(number_format($property->activePackage->discount_percent, 2), '0'), '.') }}%</dd></div>
                            @endif
                            <div class="flex justify-between"><dt class="text-ink-500">Rent commission</dt><dd class="text-ink-800 font-semibold">{{ rtrim(rtrim(number_format($property->activePackage->commission_percent, 2), '0'), '.') }}%{{ $property->activePackage->commission_overridden ? ' (override)' : '' }}</dd></div>
                            <div class="flex justify-between"><dt class="text-ink-500">Renews</dt><dd class="text-ink-800">{{ $property->activePackage->renews_at?->format('M j, Y') ?? '—' }}</dd></div>
                        </dl>

                        <form method="POST" action="{{ route('admin.properties.package.update', [$property, $property->activePackage]) }}" class="mt-4 pt-4 border-t border-ink-100 flex items-end gap-2">
                            @csrf @method('PUT')
                            <div class="flex-1">
                                <label class="text-xs font-semibold text-ink-600">Override commission % (max 12%)</label>
                                <input type="number" step="0.01" min="0" max="12" name="commission_percent" value="{{ $property->activePackage->commission_percent }}" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                            </div>
                            <button type="submit" class="btn-outline !py-2 !px-3 text-xs">Save</button>
                        </form>
                        <form method="POST" action="{{ route('admin.properties.package.cancel', [$property, $property->activePackage]) }}" onsubmit="return confirm('Cancel this package subscription?')" class="mt-2">
                            @csrf
                            <button type="submit" class="text-xs text-ink-400 hover:text-brand-600 font-semibold">Cancel subscription</button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('admin.properties.package.store', $property) }}" x-show="editing" x-cloak class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold text-ink-600">Package</label>
                            <select name="package_id" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                                @foreach ($packages as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }} — PKR {{ number_format($pkg->monthly_price, 0) }}/mo</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-ink-600">Frequency</label>
                            <select name="frequency" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly (5% off)</option>
                                <option value="annually">Annually (10% off)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full !py-2 text-sm">Switch Package</button>
                    </form>
                @else
                    <p class="text-sm text-ink-500 mb-3">No package assigned yet.</p>
                    <form method="POST" action="{{ route('admin.properties.package.store', $property) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold text-ink-600">Package</label>
                            <select name="package_id" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                                @foreach ($packages as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }} — PKR {{ number_format($pkg->monthly_price, 0) }}/mo</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-ink-600">Frequency</label>
                            <select name="frequency" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly (5% off)</option>
                                <option value="annually">Annually (10% off)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full !py-2 text-sm">Assign Package</button>
                    </form>
                @endif
            </div>

            <div class="card p-6" x-data="{
                    items: {{ \Illuminate\Support\Js::from($serviceCatalog->map(fn ($i) => ['id' => $i->id, 'name' => $i->name, 'price' => (float) $i->price])) }},
                    selected: '{{ $serviceCatalog->first()?->id }}',
                    get amount() { const item = this.items.find(i => i.id == this.selected); return item ? item.price : 0; }
                }">
                <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Bill a Service</h3>
                @if ($serviceCatalog->isEmpty())
                    <p class="text-sm text-ink-500">No service catalog items configured yet.</p>
                @else
                    <form method="POST" action="{{ route('admin.properties.service-charge', $property) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold text-ink-600">Service</label>
                            <select name="service_catalog_id" x-model="selected" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                                @foreach ($serviceCatalog as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ ucfirst($item->category) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-ink-600">Amount (PKR)</label>
                            <input type="number" step="0.01" min="0" name="amount" x-bind:value="amount" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <button type="submit" class="btn-outline w-full !py-2 text-sm">Bill Client</button>
                    </form>
                @endif
            </div>

            @if (!empty($property->legal_documents))
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Legal Documents</h3>
                    <div class="space-y-2">
                        @foreach ($property->legal_documents as $doc)
                            @php
                                $path = is_array($doc) ? ($doc['path'] ?? null) : $doc;
                                $name = is_array($doc) ? ($doc['name'] ?? 'Document ' . $loop->iteration) : 'Document ' . $loop->iteration;
                                $size = is_array($doc) ? ($doc['size'] ?? null) : null;
                                $ext = strtoupper(pathinfo($name, PATHINFO_EXTENSION)) ?: 'FILE';
                            @endphp
                            <a href="{{ \App\Support\Media::url($path) }}" target="_blank" class="flex items-center gap-3 rounded-lg border border-ink-100 p-3 hover:border-brand-300 hover:bg-brand-50/30 transition">
                                <span class="w-9 h-9 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-[10px] font-bold shrink-0">{{ $ext }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-ink-800 truncate">{{ $name }}</div>
                                    @if ($size)
                                        <div class="text-xs text-ink-400">{{ $size < 1024*1024 ? number_format($size / 1024, 1) . ' KB' : number_format($size / 1024 / 1024, 1) . ' MB' }}</div>
                                    @endif
                                </div>
                                <x-icon name="arrow-right" class="w-4 h-4 text-ink-400 shrink-0" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($property->services_requested))
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Services Requested</h3>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($property->services_requested as $service)
                            <span class="badge bg-ink-100 text-ink-600">{{ ucwords(str_replace('-',' ',$service)) }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>

@endsection
