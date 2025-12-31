<x-layouts.app title="Periksa Pasien">
    {{-- ALERT FLASH MESSAGE --}}
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Periksa Pasien</h1>

                 <!-- Alert Error Validation -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('periksa-pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar" value="{{ $id }}">

                            <div class="form-group mb-3">
                                <label for="obat" class="form-label">Pilih Obat</label>
                                <select id="select-obat" class="form-select">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                            data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok }}">
                                            {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>
                                <textarea name="biaya_periksa" id="biaya_periksa" style="display:none;"></textarea>
                                <textarea name="obat_json" id="obat_json" style="display:none;"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Total Harga</label>
                                <p id="total-harga" class="fw-bold">Rp 0</p>
                            </div>

                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('periksa-pasien.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectObat = document.getElementById('select-obat');
            const listObat = document.getElementById('obat-terpilih');
            const inputBiaya = document.getElementById('biaya_periksa');
            const inputObatJson = document.getElementById('obat_json');
            const totalHargaEl = document.getElementById('total-harga');

            let daftarObat = [];

            selectObat.addEventListener('change', function() {
                const selectedOption = selectObat.options[selectObat.selectedIndex];
                const id = selectedOption.value;
                const nama = selectedOption.dataset.nama;
                const harga = parseInt(selectedOption.dataset.harga || 0);
                const stok = parseInt(selectedOption.dataset.stok || 0);

                if (!id) return;

                if (stok <= 0) {
                    alert(`${nama} Habis Tuan! Stok tidak cukup.`);
                    selectObat.selectedIndex = 0;
                    return;
                }
                const exists = daftarObat.find(o => o.id == id);
                if (exists) {
                    alert(`${nama} sudah dipilih!`);
                    selectObat.selectedIndex = 0;
                    return;
                }

                daftarObat.push({
                    id: id,
                    nama: nama,
                    harga: harga,
                    qty: 1
                });
                
                renderObat();
                selectObat.selectedIndex = 0;
            });

            function renderObat() {
                listObat.innerHTML = '';
                let total = 0;

                daftarObat.forEach((obat, index) => {
                    total += obat.harga;

                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';
                    item.innerHTML = `
                        <div>
                            ${obat.nama} - Rp ${obat.harga.toLocaleString()}
                        </div>
                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusObat(${index})">Hapus</button>
                    `;
                    listObat.appendChild(item);
                });

                inputBiaya.value = total;
                totalHargaEl.textContent = `Rp ${total.toLocaleString()}`;
                inputObatJson.value = JSON.stringify(daftarObat.map(o => ({ 
                    id: o.id, 
                    qty: 1
                })));
            }

            window.hapusObat = function(index) {
                daftarObat.splice(index, 1);
                renderObat();
            };
        });
    </script>
</x-layouts.app>