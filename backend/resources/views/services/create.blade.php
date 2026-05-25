@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Add Service
</h1>

<form action="/services/store" method="POST"
      class="bg-white p-6 rounded shadow w-full max-w-lg">

    @csrf

    <div class="mb-4">
        <label class="block mb-2">Name</label>

        <input type="text"
               name="name"
               class="w-full border rounded p-2">
    </div>

    <div class="mb-4">
        <label class="block mb-2">Price</label>

        <input type="number"
               name="price"
               class="w-full border rounded p-2">
    </div>

    <div class="mb-4">
        <label class="block mb-2">Description</label>

        <textarea name="description"
                  class="w-full border rounded p-2"></textarea>
    </div>

    <button class="bg-black text-white px-4 py-2 rounded">
        Save Service
    </button>

</form>

@endsection