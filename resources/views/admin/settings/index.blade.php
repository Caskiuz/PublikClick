@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">\u2699\ufe0f Configuraciones del Sitio</h1>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 capitalize">{{ ucfirst($group) }}</h2>
                
                @foreach($groupSettings as $setting)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $setting->description ?? $setting->key }}
                    </label>
                    
                    @if($setting->type === 'boolean')
                    <select name="settings[{{ $setting->key }}]" class="w-full px-4 py-2 border rounded-lg">
                        <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>S\u00ed</option>
                        <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @elseif($setting->type === 'url')
                    <input type="url" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="https://...">
                    @elseif($setting->type === 'number')
                    <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" 
                           class="w-full px-4 py-2 border rounded-lg">
                    @else
                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" 
                           class="w-full px-4 py-2 border rounded-lg">
                    @endif
                    
                    <p class="text-xs text-gray-500 mt-1">Clave: {{ $setting->key }}</p>
                </div>
                @endforeach
            </div>
            @endforeach

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                \ud83d\udcbe Guardar Cambios
            </button>
        </form>
    </div>
</div>
@endsection
