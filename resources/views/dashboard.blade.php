@extends('layouts.master')

@section('title', 'Tulis Pengaduan')

@section('content')
<div class="row">

    {{-- ALERT BERJALAN --}}
    <div class="col-12">
        <div class="alert alert-info">
            <marquee direction="left" scrollamount="8">
                <strong>Selamat datang di aplikasi SIGAP! {{ Auth::user()->name }}</strong>
                Gunakan fitur laporan pengaduan untuk menyampaikan keluhan
                terkait layanan publik di wilayah Anda.
            </marquee>
        </div>
    </div>

    {{-- ================= LEFT SIDE : FORM ================= --}}
    <div class="col-md-5">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                Tulis Laporan Baru
            </div>

            <div class="card-body">
                <form action="{{ route('user.lapor.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf

                    {{-- Judul --}}
                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="Contoh: Jalan Berlubang"
                               required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label class="form-label">Isi Keluhan</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4"
                                  required></textarea>
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-3">
                        <label class="form-label">Lokasi Kejadian</label>

                        <input type="text"
                               name="location"
                               id="location_text"
                               class="form-control mb-2"
                               placeholder="Geser marker di peta..."
                               required>

                        <div id="map" 
                             style="height:300px; border-radius:10px; border:1px solid #ccc;">
                        </div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3">
                        <label class="form-label">Bukti Foto</label>
                        <input type="file"
                               name="image"
                               class="form-control">
                        <small class="text-muted">
                            Format JPG/PNG, Maks 2MB
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        KIRIM LAPORAN
                    </button>

                </form>
            </div>
        </div>
    </div>

    {{-- ================= RIGHT SIDE : TABLE ================= --}}
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                Riwayat Laporan Saya
            </div>

            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Judul & Tanggal</th>
                            <th>Status & Balasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $item)
                            <tr>

                                {{-- DATA LAPORAN --}}
                                <td>
                                    <strong>{{ $item->title }}</strong><br>
                                    <small class="text-muted">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </small>

                                    @if ($item->image)
                                        <br>
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                             width="80"
                                             class="mt-2 rounded">
                                    @endif
                                </td>

                                {{-- STATUS & RESPON --}}
                                <td style="min-width: 300px;">

                                    {{-- Status Badge --}}
                                    <div class="mb-3">
                                        @if ($item->status == '0')
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                Menunggu
                                            </span>
                                        @elseif($item->status == 'proses')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                Sedang Diproses
                                            </span>
                                        @else
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                Selesai
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Timeline --}}
                                    @if($item->responses->count() > 0)
                                        <div class="ps-3 mt-2" style="border-left:3px solid #dee2e6;">
                                            @foreach($item->responses as $resp)
                                                <div class="position-relative mb-3">

                                                    <span class="position-absolute bg-primary rounded-circle"
                                                          style="width:12px;height:12px;left:-23px;top:4px;border:2px solid white;">
                                                    </span>

                                                    <div class="bg-light p-3 rounded-3 border shadow-sm">
                                                        <small class="text-primary fw-bold d-block mb-1">
                                                            {{ $resp->created_at->format('d M Y, H:i') }}
                                                        </small>

                                                        <p class="mb-2 text-dark small">
                                                            <strong>Petugas:</strong>
                                                            {{ $resp->response_text }}
                                                        </p>

                                                        @if($resp->image)
                                                            <img src="{{ asset('storage/' . $resp->image) }}"
                                                                 class="img-fluid rounded border"
                                                                 style="max-height:100px;">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small mt-2">
                                            <em>Belum ada tindakan dari petugas.</em>
                                        </p>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Belum ada laporan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ================= MAP SCRIPT ================= --}}
<script>
    var defaultLat = -6.200000;
    var defaultLng = 106.816666;

    var map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    function getAddress(lat, lng) {
        document.getElementById("location_text").value = "Sedang mencari lokasi...";

        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("location_text").value =
                    data.display_name ?? "Alamat tidak ditemukan";
            })
            .catch(() => {
                document.getElementById("location_text").value = "Alamat tidak ditemukan";
            });
    }

    marker.on('dragend', function () {
        var coord = marker.getLatLng();
        document.getElementById("latitude").value = coord.lat;
        document.getElementById("longitude").value = coord.lng;
        getAddress(coord.lat, coord.lng);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        document.getElementById("latitude").value = e.latlng.lat;
        document.getElementById("longitude").value = e.latlng.lng;
        getAddress(e.latlng.lat, e.latlng.lng);
    });
</script>

@endsection