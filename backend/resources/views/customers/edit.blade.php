@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-[32px] shadow-sm border border-gray-200 p-10 mt-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Edit Customer</h2>

    <form action="/customers/{{ $customer->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Customer ID</label>
                <input type="text" name="customer_id" value="{{ $customer->customer_id }}" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Customer Name</label>
                <input type="text" name="name" value="{{ $customer->name }}" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ $customer->email }}" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                <textarea name="address" required rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-600 text-sm focus:ring-2 focus:ring-gray-200 resize-none">{{ $customer->address }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                <select name="status" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 outline-none text-gray-500 text-sm focus:ring-2 focus:ring-gray-200">
                    <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $customer->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-10">
            <a href="/customers" class="px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition text-sm">Cancel</a>
            <button type="submit" class="bg-[#2B3441] text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-700 transition shadow-md text-sm cursor-pointer">Update</button>
        </div>
    </form>
</div>
@endsection