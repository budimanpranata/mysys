@if ($exports->isEmpty())
    <div class="text-center p-3 text-muted">Belum ada data export.</div>
@else
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama File</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($exports as $i => $exp)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $exp->file_name }}</td>
                    <td>
                        @if ($exp->status === 'done')
                            <span class="badge bg-success">Done</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ ucfirst($exp->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $exp->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @if ($exp->status === 'done')
                            <a href="{{ route('buku-besar-kp.download', $exp->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-download"></i> Download
                            </a>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="fas fa-hourglass-half"></i> Proses
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
