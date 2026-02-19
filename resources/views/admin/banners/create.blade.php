@extends('layout')

@section('title', 'Crear Banner')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>📢 Crear Nuevo Banner</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tamaño</label>
                            <select name="size" class="form-select" required>
                                <option value="728x90">728x90 (Leaderboard)</option>
                                <option value="300x250">300x250 (Medium Rectangle)</option>
                                <option value="1200x628">1200x628 (Social Media)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Máximo 2MB</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URL de destino (opcional)</label>
                            <input type="url" name="url" class="form-control" placeholder="https://ejemplo.com">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Crear Banner</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
