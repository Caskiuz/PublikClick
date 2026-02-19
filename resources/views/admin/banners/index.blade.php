@extends('layout')

@section('title', 'Gestión de Banners')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>📢 Gestión de Banners Publicitarios</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Banner
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Título</th>
                        <th>Tamaño</th>
                        <th>Vistas</th>
                        <th>Clicks</th>
                        <th>CTR</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $banner->image_path) }}" 
                                     style="max-width: 100px; height: auto;">
                            </td>
                            <td>{{ $banner->title }}</td>
                            <td><span class="badge bg-info">{{ $banner->size }}</span></td>
                            <td>{{ number_format($banner->views) }}</td>
                            <td>{{ number_format($banner->clicks) }}</td>
                            <td>{{ $banner->views > 0 ? number_format(($banner->clicks / $banner->views) * 100, 2) : 0 }}%</td>
                            <td>
                                <span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">
                                    {{ $banner->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-toggle-{{ $banner->is_active ? 'on' : 'off' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Eliminar banner?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay banners registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $banners->links() }}
        </div>
    </div>
</div>
@endsection
