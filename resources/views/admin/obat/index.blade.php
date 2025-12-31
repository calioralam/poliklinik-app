<x-layouts.app title="Data Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Alert flash message --}}
                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h1 class="mb-4">Data Obat</h1>
                <!-- alert untuk obat stok abis -->
                    @if($obatsHabis->count() > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading">🚨 Stok Obat Habis</h5>
                            <ul class="mb-0">
                                @foreach($obatsHabis as $obat)
                                    <li>
                                        <strong>{{ $obat->nama_obat }}</strong>
                                        <span class="badge bg-danger ms-2">0 Unit</span>
                                    </li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- alert untuk stok obat menipis -->
                    @if($obatsMenunipis->count() > 0)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading">⚠️ Stok Obat Menipis</h5>
                            <ul class="mb-0">
                                @foreach($obatsMenunipis as $obat)
                                    <li>
                                        <strong>{{ $obat->nama_obat }}</strong>
                                        <span class="badge bg-warning text-dark ms-2">{{ $obat->stok }} Unit</span>
                                    </li>
                                @endforeach
                            </ul>
                            <hr>
                            <small class="text-muted">Segera lakukan restock untuk menghindari kehabisan stok!</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                <a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Obat
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Obat</th>
                                <th>Kemasan</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse ($obats as $obat)
                                <tr>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->kemasan }}</td>
                                    <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>

                                    <td>
                                        @if ($obat->stok <= 5)
                                            <span class="badge bg-danger">{{ $obat->stok }}</span>
                                        @elseif ($obat->stok <= 10)
                                            <span class="badge bg-warning">{{ $obat->stok }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $obat->stok }}</span>
                                        @endif
                                    </td>

                                    <td>
                                         <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                         </a>
                                         <form action = "{{ route('obat.destroy', $obat->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                             </button>
                                            </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data obat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const flashAlert = document.querySelector('.alert-success, .alert-error, .alert-info');
            if (flashAlert) {
                flashAlert.classList.remove('show');
                flashAlert.classList.add('fade');
                setTimeout(() => flashAlert.remove(), 500);
            }
        }, 2000);
    </script>

</x-layouts.app>