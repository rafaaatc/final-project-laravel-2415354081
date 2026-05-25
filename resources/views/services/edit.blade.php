@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Edit Service
</h1>

<form action="/services/{{ $service['id'] }}"
      method="POST"
      class="bg-white p-6 rounded shadow w-full max-w-lg">

    @csrf
    @method('PUT')

    <div class="mb-4">

        <label class="block mb-2">
            Name
        </label>

        <input type="text"
               name="name"
               value="{{ $service['name'] }}"
               class="w-full border rounded p-2">

    </div>

    <div class="mb-4">

        <label class="block mb-2">
            Price
        </label>

        <input type="number"
               name="price"
               value="{{ $service['price'] }}"
               class="w-full border rounded p-2">

    </div>

    <div class="mb-4">

        <label class="block mb-2">
            Description
        </label>

        <textarea name="description"
                  class="w-full border rounded p-2">{{ $service['description'] }}</textarea>

    </div>

    <div class="mb-4">

        <label class="block mb-2">
            Status
        </label>

        <select name="status"
                class="w-full border rounded p-2">

            <option value="1"
                {{ $service['status'] ? 'selected' : '' }}>
                Active
            </option>

            <option value="0"
                {{ !$service['status'] ? 'selected' : '' }}>
                Inactive
            </option>

        </select>

    </div>

    <button type="submit"
            class="bg-black text-white px-4 py-2 rounded">

        Update Service

    </button>

</form>

@endsection