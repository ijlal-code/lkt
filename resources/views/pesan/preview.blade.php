@extends('layouts.app')

@section('content')
<div class="container-fluid px-0 d-flex flex-column" style="height: calc(100vh - 70px); overflow: hidden;">
    
    {{-- ================= HEADER / CONTROL PANEL (ATAS) ================= --}}
    <div class="bg-white border-bottom shadow-sm z-index-1 flex-shrink-0">
        <div class="container-fluid py-2 py-lg-3">
            <div class="row align-items-center g-3">

                {{-- BAGIAN 1: INFO DOKUMEN (KIRI) --}}
                <div class="col-lg-7 col-md-12">
                    <div class="d-flex align-items-start gap-3">
                        <a href="{{ route('pesan.index') }}" class="btn btn-outline-secondary btn-sm flex-shrink-0" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge bg-primary text-uppercase">{{ $sp2a->jenis_surat ?? 'SP2A' }}</span>
                                <span class="text-muted x-small border-start ps-2">
                                    Dari: <strong>{{ $sp2a->dari_nama }}</strong>
                                </span>
                                {{-- Tombol Toggle Timeline di HP --}}
                                <button class="btn btn-link btn-sm p-0 text-decoration-none x-small d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileTimeline">
                                    Lihat Status <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                            <h6 class="fw-bold mb-0 text-truncate-2-lines" title="{{ $sp2a->perihal }}">
                                {{ Str::limit($sp2a->perihal, 100) }}
                            </h6>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN 2: STATUS & AKSI (KANAN) --}}
                <div class="col-lg-5 col-md-12">
                    @php
                        $currentUserRole = Auth::user()->role;
                        $isMyTurn = ($currentUserRole == $sp2a->current_step);
                        $isFinal = ($sp2a->status == 'Approved By System');
                    @endphp

                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-end gap-2 h-100">
                        
                        {{-- INFO STATUS (Desktop View: Horizontal Pill) --}}
                        <div class="d-none d-lg-block me-2">
                            @if($isFinal)
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> FINAL</span>
                            @else
                                <div class="d-flex align-items-center text-end flex-column">
                                    <span class="x-small text-muted text-uppercase fw-bold">Posisi Dokumen</span>
                                    <span class="badge bg-secondary text-dark bg-opacity-10 border text-uppercase">
                                        {{ $sp2a->current_step }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="d-flex gap-2 w-100 w-lg-auto">
                            {{-- Tombol Download --}}
                            <a href="{{ route('pesan.show', $sp2a->id) }}?download=true" class="btn btn-light border btn-sm flex-fill flex-lg-grow-0" title="Unduh PDF">
                                <i class="bi bi-download"></i> <span class="d-lg-none d-xl-inline">Unduh</span>
                            </a>

                            @if($isMyTurn)
                                {{-- FORM APPROVAL --}}
                                <form action="{{ route('pesan.approve', $sp2a->id) }}" method="POST" id="approvalForm" class="d-flex gap-2 w-100 w-lg-auto">
                                    @csrf
                                    <input type="hidden" name="koreksi" id="input_koreksi" value="true" disabled>
                                    
                                    {{-- Input Koreksi (Hidden by default, triggered by JS) --}}
                                    <input type="hidden" name="catatan_koreksi" id="hidden_catatan_koreksi">

                                    {{-- Tombol Revisi --}}
                                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill flex-lg-grow-0 btn-koreksi-trigger">
                                        <i class="bi bi-x-circle"></i> Revisi
                                    </button>

                                    {{-- Tombol Approve --}}
                                    <button type="button" class="btn btn-success btn-sm flex-fill flex-lg-grow-0 btn-approve-trigger fw-bold px-4">
                                        <i class="bi bi-check-lg"></i> Setujui
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- TIMELINE COLLAPSE (Mobile Only) --}}
            <div class="collapse mt-2 border-top pt-2" id="mobileTimeline">
                <div class="d-flex justify-content-between x-small text-muted">
                    <span class="{{ $sp2a->approved_sm_at ? 'text-success fw-bold' : '' }}">
                        SM: {{ $sp2a->approved_sm_at ? 'OK' : '...' }}
                    </span>
                    <span class="{{ $sp2a->approved_smqa_at ? 'text-success fw-bold' : '' }}">
                        QA: {{ $sp2a->approved_smqa_at ? 'OK' : '...' }}
                    </span>
                    <span class="{{ $sp2a->approved_gm_at ? 'text-success fw-bold' : '' }}">
                        GM: {{ $sp2a->approved_gm_at ? 'OK' : '...' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= PDF VIEWER (BAWAH) ================= --}}
    {{-- Flex Grow 1 memakan sisa tinggi layar --}}
    <div class="flex-grow-1 bg-secondary position-relative overflow-hidden">
        {{-- Loader sederhana saat PDF memuat --}}
        <div class="position-absolute top-50 start-50 translate-middle text-white z-index-0">
            <div class="spinner-border spinner-border-sm" role="status"></div> Memuat Dokumen...
        </div>

        <embed 
            src="{{ route('pesan.show', $sp2a->id) }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH" 
            type="application/pdf" 
            width="100%" 
            height="100%" 
            class="position-relative z-index-1"
            style="border: none;"
        />
    </div>
</div>

{{-- ================= STYLE ================= --}}
<style>
    .x-small { font-size: 0.75rem; }
    .text-truncate-2-lines {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .z-index-0 { z-index: 0; }
    .z-index-1 { z-index: 10; }
</style>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('approvalForm');

    // --- LOGIKA APPROVE ---
    const approveBtns = document.querySelectorAll('.btn-approve-trigger');
    approveBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: "Pastikan Anda telah membaca dokumen dengan teliti.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Matikan mode koreksi
                    const inputKoreksi = document.getElementById('input_koreksi');
                    if(inputKoreksi) inputKoreksi.disabled = true;
                    
                    Swal.fire({
                        title: 'Memproses...', 
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => Swal.showLoading()
                    });
                    form.submit();
                }
            });
        });
    });

    // --- LOGIKA KOREKSI DENGAN POPUP TEXTAREA ---
    // Menggunakan SweetAlert Input agar tampilan UI bersih (tidak ada textarea besar di header)
    const koreksiBtns = document.querySelectorAll('.btn-koreksi-trigger');
    koreksiBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Kembalikan untuk Revisi',
                input: 'textarea',
                inputLabel: 'Tuliskan catatan perbaikan untuk Staff:',
                inputPlaceholder: 'Contoh: Tabel pada halaman 2 angkanya salah...',
                inputAttributes: {
                    'aria-label': 'Tulis catatan koreksi disini'
                },
                showCancelButton: true,
                confirmButtonText: 'Kirim Revisi',
                confirmButtonColor: '#dc3545',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Anda harus menuliskan alasan penolakan!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Isi input hidden dengan nilai dari SweetAlert
                    document.getElementById('hidden_catatan_koreksi').value = result.value;
                    
                    // Aktifkan mode koreksi
                    const inputKoreksi = document.getElementById('input_koreksi');
                    if(inputKoreksi) inputKoreksi.disabled = false;

                    Swal.fire({title: 'Mengembalikan...', didOpen: () => Swal.showLoading()});
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection