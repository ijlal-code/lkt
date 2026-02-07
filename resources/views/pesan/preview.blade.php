@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="height: calc(100vh - 70px); overflow: hidden;">
    <div class="row h-100 g-0">
        
        {{-- KOLOM KIRI: PDF VIEWER --}}
        <div class="col-lg-9 col-md-8 h-100 bg-secondary position-relative">
            <div class="h-100 w-100 d-flex justify-content-center align-items-center">
                {{-- Embed PDF Stream --}}
                <embed 
                    src="{{ route('pesan.show', $sp2a->id) }}#toolbar=0&navpanes=0&scrollbar=1" 
                    type="application/pdf" 
                    width="100%" 
                    height="100%" 
                    style="border: none;"
                />
            </div>
        </div>

        {{-- KOLOM KANAN: ACTION PANEL --}}
        <div class="col-lg-3 col-md-4 h-100 bg-white border-start shadow-sm d-flex flex-column">
            
            {{-- Header Panel --}}
            <div class="p-4 border-bottom bg-light">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <a href="{{ route('pesan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <span class="badge bg-primary text-uppercase">{{ $sp2a->jenis_surat ?? 'SP2A' }}</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ Str::limit($sp2a->perihal, 60) }}</h6>
                <div class="small text-muted">
                    Dari: <span class="fw-semibold">{{ $sp2a->dari_nama }}</span>
                </div>
            </div>

            {{-- Body Panel (Scrollable) --}}
            <div class="p-4 flex-grow-1 overflow-auto custom-scrollbar">
                
                {{-- Tombol Download Selalu Ada --}}
                <div class="mb-4">
                    <a href="{{ route('pesan.show', $sp2a->id) }}?download=true" class="btn btn-outline-primary w-100 fw-bold shadow-sm">
                        <i class="bi bi-download me-2"></i> Unduh / Simpan PDF
                    </a>
                </div>

                {{-- Status Timeline Singkat --}}
                <div class="mb-4">
                    <label class="small text-secondary fw-bold text-uppercase mb-2">Status Dokumen</label>
                    <div class="timeline-simple">
                        <div class="timeline-item {{ $sp2a->approved_sm_at ? 'completed' : 'current' }}">
                            <div class="dot"></div>
                            <div class="content">
                                <span class="d-block fw-bold small">SM Approval</span>
                                <span class="d-block x-small text-muted">
                                    {{ $sp2a->approved_sm_at ? \Carbon\Carbon::parse($sp2a->approved_sm_at)->format('d/m/Y H:i') : 'Menunggu...' }}
                                </span>
                            </div>
                        </div>
                        <div class="timeline-item {{ $sp2a->approved_smqa_at ? 'completed' : ($sp2a->current_step == 'smqa' ? 'current' : '') }}">
                            <div class="dot"></div>
                            <div class="content">
                                <span class="d-block fw-bold small">SM QA Approval</span>
                                <span class="d-block x-small text-muted">
                                    {{ $sp2a->approved_smqa_at ? \Carbon\Carbon::parse($sp2a->approved_smqa_at)->format('d/m/Y H:i') : 'Menunggu...' }}
                                </span>
                            </div>
                        </div>
                        <div class="timeline-item {{ $sp2a->approved_gm_at ? 'completed' : ($sp2a->current_step == 'gm' ? 'current' : '') }}">
                            <div class="dot"></div>
                            <div class="content">
                                <span class="d-block fw-bold small">GM Approval (Final)</span>
                                <span class="d-block x-small text-muted">
                                    {{ $sp2a->approved_gm_at ? \Carbon\Carbon::parse($sp2a->approved_gm_at)->format('d/m/Y H:i') : 'Menunggu...' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM APPROVAL / KOREKSI --}}
                @php
                    $currentUserRole = Auth::user()->role;
                    $isMyTurn = ($currentUserRole == $sp2a->current_step);
                    $isFinal = ($sp2a->status == 'Approved By System');
                @endphp

                @if($isMyTurn)
                    <div class="card bg-warning bg-opacity-10 border-warning border-opacity-25 mb-3">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-exclamation-circle-fill text-warning me-2"></i>Giliran Anda</h6>
                            <p class="small text-muted mb-0">Dokumen ini menunggu persetujuan Anda. Silakan review di sisi kiri sebelum memutuskan.</p>
                        </div>
                    </div>

                    <form action="{{ route('pesan.approve', $sp2a->id) }}" method="POST" id="approvalForm">
                        @csrf
                        
                        {{-- INPUT HIDDEN UTAMA: --}}
                        {{-- Secara default disabled. Akan di-enable via JS jika tombol Koreksi diklik --}}
                        <input type="hidden" name="koreksi" id="input_koreksi" value="true" disabled>

                        {{-- Tombol Approve --}}
                        <button type="button" class="btn btn-success w-100 py-2 fw-bold mb-3 shadow-sm btn-approve-trigger">
                            <i class="bi bi-check-lg me-2"></i> SETUJUI DOKUMEN
                        </button>

                        <hr class="text-muted my-4">

                        {{-- Form Koreksi --}}
                        <div class="mb-3">
                            <label for="catatan_koreksi" class="form-label small fw-bold text-danger">Koreksi / Revisi (Jika ada)</label>
                            <textarea name="catatan_koreksi" id="catatan_koreksi" rows="4" class="form-control form-control-sm" placeholder="Tulis catatan perbaikan untuk Staff..."></textarea>
                        </div>
                        
                        {{-- Tombol Koreksi (Ubah jadi type="button") --}}
                        <button type="button" class="btn btn-outline-danger w-100 btn-sm btn-koreksi-trigger">
                            <i class="bi bi-x-circle me-1"></i> Kembalikan untuk Revisi
                        </button>
                    </form>

                @elseif($isFinal)
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div>
                            <strong>Selesai!</strong> Dokumen ini telah disetujui penuh dan didistribusikan.
                        </div>
                    </div>
                @else
                    <div class="alert alert-secondary text-center">
                        <i class="bi bi-lock-fill d-block fs-3 mb-2 opacity-50"></i>
                        <span class="small">Dokumen sedang berada di tahap: <strong>{{ strtoupper($sp2a->current_step) }}</strong></span>
                    </div>
                @endif
            </div>

            {{-- Footer Panel --}}
            <div class="p-3 bg-light border-top text-center text-muted x-small">
                &copy; {{ date('Y') }} Sistem Approval SP2A
            </div>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.75rem; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #aaa; }

    /* Simple Timeline CSS */
    .timeline-simple { border-left: 2px solid #e9ecef; margin-left: 10px; padding-left: 20px; }
    .timeline-item { position: relative; margin-bottom: 20px; }
    .timeline-item:last-child { margin-bottom: 0; }
    .timeline-item .dot { 
        position: absolute; left: -26px; top: 0; width: 10px; height: 10px; 
        border-radius: 50%; background: #e9ecef; border: 2px solid #fff; 
    }
    .timeline-item.current .dot { background: #ffc107; box-shadow: 0 0 0 3px rgba(255,193,7,0.2); }
    .timeline-item.completed .dot { background: #198754; }
    .timeline-item.completed .content { color: #198754; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('approvalForm');

        // --- LOGIKA APPROVE ---
        const approveBtn = document.querySelector('.btn-approve-trigger');
        if(approveBtn) {
            approveBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi Persetujuan',
                    text: "Apakah Anda yakin dokumen di sebelah kiri sudah benar dan siap disetujui?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Pastikan input koreksi disabled agar controller masuk ke blok Approve
                        document.getElementById('input_koreksi').disabled = true;
                        
                        Swal.fire({title: 'Memproses...', didOpen: () => Swal.showLoading()});
                        form.submit();
                    }
                });
            });
        }

        // --- LOGIKA KOREKSI (BARU) ---
        const koreksiBtn = document.querySelector('.btn-koreksi-trigger');
        if(koreksiBtn) {
            koreksiBtn.addEventListener('click', function() {
                // Validasi: Catatan Koreksi Harus Diisi
                const catatan = document.getElementById('catatan_koreksi').value.trim();
                
                if(!catatan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Catatan Kosong',
                        text: 'Harap isi catatan koreksi agar Staff tahu apa yang perlu diperbaiki!',
                        confirmButtonColor: '#dc3545'
                    });
                    return; 
                }

                // Tampilkan Konfirmasi
                Swal.fire({
                    title: 'Kembalikan Dokumen?',
                    text: "Dokumen akan dikembalikan ke status 'Ditolak/Revisi' dan Staff akan menerima notifikasi.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Ya, Minta Revisi',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enable input koreksi agar Controller mendeteksi $request->has('koreksi')
                        document.getElementById('input_koreksi').disabled = false;
                        
                        Swal.fire({title: 'Mengembalikan...', didOpen: () => Swal.showLoading()});
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endsection