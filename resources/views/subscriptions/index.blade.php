@extends('layouts.app')

@section('content')
<div x-data="{ showModal: false, openDropdown: null }">

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex justify-between items-center text-sm">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-green-500 hover:text-green-700 font-bold">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex justify-between items-center text-sm">
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-medium text-gray-800">Subscriptions</h1>
        <button @click="showModal = true" class="bg-[#2B3441] text-white px-4 py-2.5 rounded-xl inline-flex items-center gap-2 text-sm font-medium hover:bg-gray-700 transition shadow-sm cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Add Data
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-visible">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-sm font-semibold text-gray-600 bg-gray-50/50">
                    <th class="p-4 pl-6 font-medium">Customer Name</th>
                    <th class="p-4 font-medium">Services</th>
                    <th class="p-4 font-medium">Services Period</th>
                    <th class="p-4 font-medium">Status</th>
                    <th class="p-4 pr-6 text-right font-medium w-24">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                @forelse($subscriptions as $sub)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="p-4 pl-6 font-normal text-gray-900">{{ $sub->customer->name ?? 'N/A' }}</td>
                        <td class="p-4 font-normal text-gray-900">{{ $sub->service->name ?? 'N/A' }}</td>
                        <td class="p-4 font-normal text-gray-600">
                            {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                        </td>
                        <td class="p-4">
                            @php $statusLower = strtolower($sub->status); @endphp
                            @if($statusLower === 'active')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-medium">Active</span>
                            @elseif($statusLower === 'deactivate')
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium">Deactivate</span>
                            @elseif($statusLower === 'trial')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Trial</span>
                            @elseif($statusLower === 'isolir')
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-medium">Isolir</span>
                            @elseif($statusLower === 'dismantle')
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-medium">Dismantle</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right relative">
                            {{-- IMPROVEMENT FRONTEND 2: Jika status 'dismantle', KUNCI total action --}}
                            @if(strtolower($sub->status) !== 'dismantle')
                                <button @click="openDropdown = (openDropdown === {{ $sub->id }} ? null : {{ $sub->id }})"
                                        @click.away="if(openDropdown === {{ $sub->id }}) openDropdown = null"
                                        class="text-gray-800 hover:bg-gray-100 p-1.5 rounded-lg inline-flex items-center transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                </button>

                                <div x-show="openDropdown === {{ $sub->id }}" x-transition
                                     class="absolute right-6 mt-1 w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-1.5 text-left text-sm" style="display: none;">
                                    
                                    {{-- Opsi Active: Hanya muncul jika status saat ini BUKAN active --}}
                                    @if(strtolower($sub->status) !== 'active')
                                        <form action="/subscriptions/{{ $sub->id }}/status" method="POST">
                                            @csrf @method('PATCH') <input type="hidden" name="status" value="Active">
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                Active
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Opsi Deactivate: Hanya muncul jika status saat ini BUKAN deactivate --}}
                                    @if(strtolower($sub->status) !== 'deactivate')
                                        <form action="/subscriptions/{{ $sub->id }}/status" method="POST">
                                            @csrf @method('PATCH') <input type="hidden" name="status" value="Deactivate">
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                Deactivate
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Opsi Trial: Hanya muncul jika status saat ini BUKAN trial --}}
                                    @if(strtolower($sub->status) !== 'trial')
                                        <form action="/subscriptions/{{ $sub->id }}/status" method="POST">
                                            @csrf @method('PATCH') <input type="hidden" name="status" value="Trial">
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                Trial
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Opsi Isolir: Hanya muncul jika status saat ini BUKAN isolir --}}
                                    @if(strtolower($sub->status) !== 'isolir')
                                        <form action="/subscriptions/{{ $sub->id }}/status" method="POST">
                                            @csrf @method('PATCH') <input type="hidden" name="status" value="Isolir">
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                Isolir
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Opsi Dismantle --}}
                                    <form action="/subscriptions/{{ $sub->id }}/status" method="POST">
                                        @csrf @method('PATCH') <input type="hidden" name="status" value="Dismantle">
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Dismantle
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic pr-2 select-none">No Action</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-8 text-center text-gray-400">No data found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-[99] flex items-center justify-center overflow-y-auto" x-cloak>
        <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px]"></div>
        <div x-show="showModal" x-transition class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-10 mx-4 z-10">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Add Subscription</h2>
            <form action="/subscriptions" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Customer</label>
                        <select name="customer_id" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer) <option value="{{ $customer->id }}">{{ $customer->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Service</label>
                        <select name="service_id" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                            <option value="">Select Service</option>
                            @foreach($services as $service) <option value="{{ $service->id }}">{{ $service->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Start Date</label>
                            <input type="date" name="start_date" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">End Date</label>
                            <input type="date" name="end_date" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <select name="status" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                            <option value="">Select Status</option>
                            <option value="Active">Active</option>
                            <option value="Deactivate">Deactivate</option>
                            <option value="Trial">Trial</option>
                            <option value="Isolir">Isolir</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-10">
                    <button type="button" @click="showModal = false" class="px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition text-sm cursor-pointer">Cancel</button>
                    <button type="submit" class="bg-[#2B3441] text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-700 transition shadow-md text-sm cursor-pointer">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>[x-cloak] { display: none !important; }</style>
@endsection