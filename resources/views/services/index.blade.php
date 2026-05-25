@extends('layouts.app')

@section('content')

<div x-data="{ showModal: false }">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-medium text-gray-800">
            Services
        </h1>

        <button
            @click="showModal = true"
            class="bg-[#2B3441] text-white px-4 py-2.5 rounded-xl inline-flex items-center gap-2 text-sm font-medium hover:bg-gray-700 transition shadow-sm cursor-pointer">

            <svg class="w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2.5"
                      d="M12 4v16m8-8H4"/>

            </svg>

            Add Data

        </button>

    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm relative"
         x-data="{ openDropdown: null }">

        <table class="w-full text-left border-collapse">

            <thead>

                <tr class="border-b border-gray-200 text-sm font-semibold text-gray-600 bg-gray-50/50">

                    <th class="p-4 pl-6 font-medium rounded-tl-xl">
                        Service Name
                    </th>

                    <th class="p-4 font-medium">
                        Price
                    </th>

                    <th class="p-4 font-medium">
                        Status
                    </th>

                    <th class="p-4 pr-6 text-right font-medium w-24 rounded-tr-xl">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">

                @foreach($services as $service)

                    <tr class="hover:bg-gray-50/50 transition duration-150">

                        <td class="p-4 pl-6 font-normal text-gray-900">
                            {{ $service['name'] }}
                        </td>

                        <td class="p-4 tracking-wide font-normal">
                            Rp{{ number_format($service['price'], 0, ',', '.') }},00
                        </td>

                        <td class="p-4">

                            @if($service['status'])

                                <span class="bg-green-100/70 text-green-600 px-3 py-1 rounded-full text-xs font-medium">
                                    Active
                                </span>

                            @else

                                <span class="bg-red-100/70 text-red-600 px-3 py-1 rounded-full text-xs font-medium">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td class="p-4 pr-6 text-right relative">

                            <button
                                @click="openDropdown = (openDropdown === {{ $service['id'] }} ? null : {{ $service['id'] }})"
                                @click.away="if(openDropdown === {{ $service['id'] }}) openDropdown = null"
                                class="text-gray-800 hover:bg-gray-100 p-1.5 rounded-lg inline-flex items-center transition">

                                <svg class="w-5 h-5"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h16"/>

                                </svg>

                            </button>

                            <div
                                x-show="openDropdown === {{ $service['id'] }}"
                                x-transition
                                class="absolute right-6 @if($loop->last) bottom-full mb-2 @else mt-1 @endif w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-1.5 text-left text-sm"
                                style="display: none;">

                                @if($service['status'])

                                    <form action="/services/{{ $service['id'] }}/deactivate"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left">

                                            Deactivate

                                        </button>

                                    </form>

                                @else

                                    <form action="/services/{{ $service['id'] }}/activate"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition text-left">

                                            Activate

                                        </button>

                                    </form>

                                @endif

                                <a href="/services/{{ $service['id'] }}/edit"
                                   class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">

                                    Edit

                                </a>

                                <div class="border-t border-gray-100 my-1"></div>

                                <form action="/services/{{ $service['id'] }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this service?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 transition text-left">

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

    <div x-show="showModal"
         class="fixed inset-0 z-[99] flex items-center justify-center overflow-y-auto"
         x-cloak>

        <div
            x-show="showModal"
            @click="showModal = false"
            class="fixed inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div
            x-show="showModal"
            x-transition
            class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-10 mx-4 z-10">

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">
                Add Services
            </h2>

            <form action="/services" method="POST">

                @csrf

                <div class="space-y-5">

                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Service Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            required
                            class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5">

                    </div>

                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Price
                        </label>

                        <input
                            type="number"
                            name="price"
                            required
                            class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5">

                    </div>

                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 resize-none"></textarea>

                    </div>

                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            required
                            class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5">

                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>

                        </select>

                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-10">

                    <button
                        type="button"
                        @click="showModal = false"
                        class="px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition text-sm cursor-pointer">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="bg-[#2B3441] text-white px-8 py-2.5 rounded-xl font-bold hover:bg-gray-700 transition shadow-md text-sm cursor-pointer">

                        Submit

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@endsection