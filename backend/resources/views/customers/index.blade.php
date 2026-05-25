@extends('layouts.app')

@section('content')
<div x-data="{ showModal: false, editModal: false, editData: { id: '', customer_id: '', name: '', email: '', address: '', status: '' } }">

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex justify-between items-center text-sm transition">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-green-500 hover:text-green-700 font-bold text-lg">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex justify-between items-center text-sm transition">
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="text-red-500 hover:text-red-700 font-bold text-lg">&times;</button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-medium text-gray-800">Customers</h1>
        <button @click="showModal = true"
                class="bg-[#2B3441] text-white px-4 py-2.5 rounded-xl inline-flex items-center gap-2 text-sm font-medium hover:bg-gray-700 transition shadow-sm cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Data
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm relative" x-data="{ openDropdown: null }">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-sm font-semibold text-gray-600 bg-gray-50/50">
                    <th class="p-4 pl-6 font-medium rounded-tl-xl">Customer ID</th>
                    <th class="p-4 font-medium">Customer Name</th>
                    <th class="p-4 font-medium">Email</th>
                    <th class="p-4 font-medium">Address</th>
                    <th class="p-4 font-medium">Status</th>
                    <th class="p-4 pr-6 text-right font-medium w-24 rounded-tr-xl">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="p-4 pl-6 font-normal text-gray-900">{{ $customer->customer_id }}</td>
                        <td class="p-4 font-normal text-gray-900">{{ $customer->name }}</td>
                        <td class="p-4 font-normal text-gray-600">{{ $customer->email }}</td>
                        <td class="p-4 font-normal text-gray-600">{{ $customer->address }}</td>
                        <td class="p-4">
                            @if($customer->status)
                                <span class="bg-green-100/70 text-green-600 px-3 py-1 rounded-full text-xs font-medium">Active</span>
                            @else
                                <span class="bg-red-100/70 text-red-600 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right relative">
                            <button @click="openDropdown = (openDropdown === {{ $customer->id }} ? null : {{ $customer->id }})"
                                    @click.away="if(openDropdown === {{ $customer->id }}) openDropdown = null"
                                    class="text-gray-800 hover:bg-gray-100 p-1.5 rounded-lg inline-flex items-center transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            <div x-show="openDropdown === {{ $customer->id }}" x-transition
                                 class="absolute right-6 @if($loop->last && $loop->count > 2) bottom-full mb-2 @else mt-1 @endif w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-1.5 text-left text-sm"
                                 style="display: none;">
                                
                                {{-- 
                                  IMPROVEMENT FRONTEND 1: Pembatasan Action Status Customer
                                  Karena status Active tidak bisa diubah ke Inactive (dan sebaliknya), 
                                  maka form/tombol toggle status di bawah ini dihapus/dikomentari 
                                  agar user tidak dapat memicu perubahan status dari tabel.
                                --}}
                                {{-- 
                                @if($customer->status)
                                    <form action="/customers/{{ $customer->id }}/deactivate" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left cursor-pointer">
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form action="/customers/{{ $customer->id }}/activate" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left cursor-pointer">
                                            Active
                                        </button>
                                    </form>
                                @endif 
                                --}}

                                <button type="button" 
                                        @click="
                                            editData = { 
                                                id: '{{ $customer->id }}', 
                                                customer_id: '{{ $customer->customer_id }}', 
                                                name: '{{ $customer->name }}', 
                                                email: '{{ $customer->email }}', 
                                                address: '{{ \Str::slug($customer->address, ' ') }}', 
                                                status: '{{ $customer->status }}' 
                                            }; 
                                            editModal = true; 
                                            openDropdown = null;
                                        " 
                                        class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left cursor-pointer">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit
                                </button>

                                <div class="border-t border-gray-100 my-1"></div>

                                <form action="/customers/{{ $customer->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 transition text-left cursor-pointer">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">No customers found. Click "Add Data" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-[99] flex items-center justify-center overflow-y-auto" x-cloak>
        <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px]"></div>
        <div x-show="showModal" x-transition class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-10 mx-4 z-10">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Add Customer</h2>
            
            <form action="/customers" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Customer ID</label>
                        <input type="text" name="customer_id" required placeholder="Enter customer ID" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Customer Name</label>
                        <input type="text" name="name" required placeholder="Enter customer name" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required placeholder="Enter email address" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                        <textarea name="address" required rows="3" placeholder="Enter address" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <select name="status" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-500 text-sm focus:ring-2 focus:ring-gray-200">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
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

    <div x-show="editModal" class="fixed inset-0 z-[99] flex items-center justify-center overflow-y-auto" x-cloak>
        <div x-show="editModal" @click="editModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-[2px]"></div>
        <div x-show="editModal" x-transition class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-10 mx-4 z-10">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Edit Customer</h2>
            
            <form :action="'/customers/' + editData.id" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Customer ID</label>
                        <input type="text" name="customer_id" required x-model="editData.customer_id" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Customer Name</label>
                        <input type="text" name="name" required x-model="editData.name" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required x-model="editData.email" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                        <textarea name="address" required rows="3" x-model="editData.address" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <select name="status" disabled x-model="editData.status" class="w-full bg-gray-100 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-400 text-sm cursor-not-allowed">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <input type="hidden" name="status" x-model="editData.status">
                        <p class="text-xs text-gray-400 mt-1">* Status customer yang sudah tersimpan tidak dapat diubah.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-10">
                    <button type="button" @click="editModal = false" class="px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition text-sm cursor-pointer">Cancel</button>
                    <button type="submit" class="bg-[#2B3441] text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-700 transition shadow-md text-sm cursor-pointer">Update</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>[x-cloak] { display: none !important; }</style>
@endsection