@extends('layouts.app')

@section('content')

<div x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    openDropdown: null, 
    editData: { id: '', name: '', price: '', description: '', status: '' } 
}">

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex justify-between items-center text-sm transition"
             x-cloak>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-green-500 hover:text-green-700 font-bold text-lg">&times;</button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-medium text-gray-800">Services</h1>
        <button @click="showAddModal = true"
                class="bg-[#2B3441] text-white px-4 py-2.5 rounded-xl inline-flex items-center gap-2 text-sm font-medium hover:bg-gray-700 transition shadow-sm cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Data
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm relative">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-sm font-semibold text-gray-600 bg-gray-50/50">
                    <th class="p-4 pl-6 font-medium rounded-tl-xl">Service Name</th>
                    <th class="p-4 font-medium">Price</th>
                    <th class="p-4 font-medium">Status</th>
                    <th class="p-4 pr-6 text-right font-medium w-24 rounded-tr-xl">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                @foreach($services as $service)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="p-4 pl-6 font-normal text-gray-900">{{ $service['name'] }}</td>
                        <td class="p-4 tracking-wide font-normal">Rp{{ number_format($service['price'], 0, ',', '.') }},00</td>
                        <td class="p-4">
                            @if($service['status'])
                                <span class="bg-green-100/70 text-green-600 px-3 py-1 rounded-full text-xs font-medium">Active</span>
                            @else
                                <span class="bg-red-100/70 text-red-600 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right relative">
                            <button @click.stop="openDropdown = (openDropdown === {{ $service['id'] }} ? null : {{ $service['id'] }})"
                                    class="text-gray-800 hover:bg-gray-100 p-1.5 rounded-lg inline-flex items-center transition cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            <div x-show="openDropdown === {{ $service['id'] }}" @click.away="openDropdown = null" x-transition
                                 class="absolute right-6 @if($loop->last && $loop->count > 2) bottom-full mb-2 @else mt-1 @endif w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-1.5 text-left text-sm"
                                 style="display: none;">

                                @if($service['status'])
                                    <form action="/services/{{ $service['id'] }}/deactivate" method="POST" class="m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left cursor-pointer">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form action="/services/{{ $service['id'] }}/activate" method="POST" class="m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left cursor-pointer">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Activate
                                        </button>
                                    </form>
                                @endif

                                <button @click="
                                            editData = { 
                                                id: '{{ $service['id'] }}', 
                                                name: '{{ $service['name'] }}', 
                                                price: '{{ $service['price'] }}', 
                                                description: '{{ $service['description'] ?? '' }}', 
                                                status: '{{ $service['status'] ? 1 : 0 }}' 
                                            }; 
                                            showEditModal = true; 
                                            openDropdown = null;
                                        "
                                        class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left cursor-pointer">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Edit
                                </button>

                                <div class="border-t border-gray-100 my-1"></div>

                                <form action="/services/{{ $service['id'] }}" method="POST" onsubmit="return confirm('Delete this service?')" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 transition text-left cursor-pointer">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="showAddModal" class="fixed inset-0 z-[99] flex items-center justify-center overflow-y-auto" x-cloak>
        <div x-show="showAddModal" @click="showAddModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div x-show="showAddModal" x-transition
             class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-10 mx-4 z-10">
            
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Add Services</h2>

            <form action="/services" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Service Name</label>
                        <input type="text" name="name" required placeholder="Enter service name" 
                               class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price</label>
                        <input type="number" name="price" required placeholder="Enter price" 
                               class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" placeholder="Enter service description" 
                                  class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 resize-none transition"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <div class="relative">
                            <select name="status" required 
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-500 text-sm appearance-none focus:ring-2 focus:ring-gray-200 transition">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <button type="button" @click="showAddModal = false" class="px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition text-sm cursor-pointer">Cancel</button>
                    <button type="submit" class="bg-[#2B3441] text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-700 transition shadow-md text-sm cursor-pointer">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal" class="fixed inset-0 z-[99] flex items-center justify-center overflow-y-auto" x-cloak>
        <div x-show="showEditModal" @click="showEditModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div x-show="showEditModal" x-transition
             class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-10 mx-4 z-10">
            
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Edit Services</h2>

            <form :action="'/services/' + editData.id" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Service Name</label>
                        <input type="text" name="name" x-model="editData.name" required placeholder="Enter service name" 
                               class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price</label>
                        <input type="number" name="price" x-model="editData.price" required placeholder="Enter price" 
                               class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" x-model="editData.description" rows="4" placeholder="Enter service description" 
                                  class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 resize-none transition"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <div class="relative">
                            <select name="status" x-model="editData.status" required 
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-500 text-sm appearance-none focus:ring-2 focus:ring-gray-200 transition">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <button type="button" @click="showEditModal = false" class="px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition text-sm cursor-pointer">Cancel</button>
                    <button type="submit" class="bg-[#2B3441] text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-700 transition shadow-md text-sm cursor-pointer">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection