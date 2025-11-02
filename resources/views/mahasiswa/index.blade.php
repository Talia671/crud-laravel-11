<!DOCTYPE html>
<html>
<head>
    <title>Daftar Mahasiswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Header dan Tombol Aksi (sejajar kanan-kiri) -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-primary mb-0">
                <i class="bi bi-journal-text me-2"></i> Daftar Mahasiswa
            </h1>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('mahasiswa.cetakPDF') }}" target="_blank" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a href="{{ route('mahasiswa.exportExcel') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
                <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Tambah Mahasiswa
                </a>
            </div>
        </div>

        <!-- Form Pencarian -->
        <form action="{{ route('mahasiswa.index') }}" method="GET" class="d-flex flex-wrap justify-content-start gap-2 mb-4">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIM, atau email..."
                value="{{ $keyword ?? '' }}" style="width: 250px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>

        <!-- Tabel Data -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Email</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswa as $m)
                        <tr>
                            <td>{{ $m->nama }}</td>
                            <td>{{ $m->nim }}</td>
                            <td>{{ $m->email }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('mahasiswa.edit', $m->id) }}" class="btn btn-warning btn-sm">
                                        ✏ Edit
                                    </a>
                                    <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus?')">🗑 Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data mahasiswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $mahasiswa->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
