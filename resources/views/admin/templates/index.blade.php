@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>📸 Daftar Template Photobooth</h2>

        <a href="{{ url('/templates/create') }}" class="btn btn-success mb-3">
            ➕ Tambah Template
        </a>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Preview</th>
                    <th>Nama</th>
                    <th>Ukuran Kertas</th>
                    <th>Frame</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $i => $t)
                    <tr>
                        <td>{{ $i + 1 }}</td>

                        <td width="120">
                            <img src="{{ asset('storage/' . $t->template_image) }}"
                                style="max-width:100px;border:1px solid #ddd">
                        </td>

                        <td>{{ $t->name }}</td>

                        <td>
                            {{ $t->paperSize->name ?? '-' }}<br>
                            <small class="text-muted">
                                {{ $t->paperSize->width_mm ?? '' }} × {{ $t->paperSize->height_mm ?? '' }} mm
                            </small>
                        </td>

                        <td>
                            <span class="badge bg-primary">
                                {{ $t->frame_count }} frame
                            </span>
                        </td>

                        <td>
                            @if ($t->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>

                        <td>
                            {{ $t->created_at->format('d M Y') }}
                        </td>

                        <td>
                            <a href="#" class="btn btn-sm btn-info">👁</a>
                            <a href="/templates/{{ $t->id }}/edit" class="btn btn-sm btn-warning">✏️</a>
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus template?')">
                                    🗑
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Belum ada template
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
