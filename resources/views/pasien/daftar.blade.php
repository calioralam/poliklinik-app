<x-layouts.app title="Daftar Poli">
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

                <h1 class="mb-4">Daftar Poli</h1>

                <section class="content">
                    <div class="row">
                        <div class="col-md-6 col-lg-5">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    Form Pendaftaran Poli
                                </div>
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <strong>Terjadi Kesalahan:</strong>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                                        <div class="mb-3">
                                            <label>No. Rekam Medis</label>
                                            <input type="text" class="form-control" name="no_rm" value="{{ $user->no_rm }}" readonly>
                                        </div>


                                        <div class="mb-3">
                                            <label>Pilih Poli</label>
                                            <select id="selectPoli" class="form-control">
                                                <option value="">-- Pilih Poli --</option>
                                                @foreach ($polis as $poli)
                                                    <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Pilih Jadwal</label>
                                            <select name="id_jadwal" id="selectJadwal" class="form-control" required>
                                            <option value="">-- Pilih Jadwal --</option>
                                            @foreach ($jadwals as $jadwal)
                                                @php
                                                    $dokter = $jadwal->dokter;
                                                    $poli = $dokter && $dokter->poli ? $dokter->poli : null;
                                                @endphp
                                                <option 
                                                    value="{{ $jadwal->id }}" 
                                                    data-poli="{{ $poli ? $poli->id : '' }}">
                                                    {{ $poli ? $poli->nama_poli : 'N/A' }} |
                                                    {{ $jadwal->hari }} ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}) |
                                                    {{ $dokter ? $dokter->nama : 'N/A' }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Keluhan</label>
                                            <textarea name="keluhan" class="form-control" rows="3"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Daftar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectPoli = document.getElementById('selectPoli');
        const selectJadwal = document.getElementById('selectJadwal');

        selectPoli.addEventListener('change', async () => {
            const poliId = selectPoli.value;
            selectJadwal.innerHTML = '<option value="">-- Pilih Jadwal --</option>';

            if (!poliId) return;

            const response = await fetch(`/pasien/get-jadwal/${poliId}`);
            const jadwals = await response.json();

            if (jadwals.length === 0) {
                const option = document.createElement('option');
                option.textContent = '⚠️ Tidak ada jadwal untuk poli ini';
                option.disabled = true;
                selectJadwal.appendChild(option);
                return;
            }

            jadwals.forEach(jadwal => {
                const option = document.createElement('option');
                option.value = jadwal.id;
                option.textContent = `${jadwal.dokter.poli.nama_poli} | ${jadwal.hari} (${jadwal.jam_mulai} - ${jadwal.jam_selesai}) | ${jadwal.dokter.nama}`;
                selectJadwal.appendChild(option);
            });
        });
    });
</script>
