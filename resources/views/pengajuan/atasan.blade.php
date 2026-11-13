@extends('layouts.app')
@section('title', 'Persetujuan Cuti Bawahan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Daftar Pengajuan Cuti Bawahan (Tahap Atasan)</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No. Pengajuan</th>
                    <th>Nama Staf</th>
                    <th>Tanggal Cuti</th>
                    <th>Alasan</th>
                    <th>Aksi Keputusan Atasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($antreanAtasan as $ajuan)
                <tr>
                    <td>{{ $ajuan->nomor_pengajuan }}</td>
                    <td>{{ $ajuan->pegawai->nama ?? '-' }}</td>
                    <td>{{ $ajuan->tanggal_cuti }}</td>
                    <td>{{ $ajuan->alasan }}</td>
                    <td>
                        <form action="{{ route('pengajuan.keputusanAtasan', $ajuan->id) }}" method="POST" class="d-flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan...">
                            <button name="keputusan" value="disetujui" class="btn btn-success btn-sm">Setujui</button>
                            <button name="keputusan" value="ditolak" class="btn btn-danger btn-sm">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Tidak ada pengajuan bawahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection