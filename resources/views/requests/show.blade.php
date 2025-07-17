@extends('layouts.app')

@section('title', 'Lihat Isi File Excel')

<script>
    function previewExcel(requestId) {
        fetch(`/requests/preview/${requestId}`)
            .then(response => response.json())
            .then(data => {
                let html = `<table class="table table-bordered table-striped table-sm">`;

                data.rows.forEach((row, rowIndex) => {
                    html += '<tr>';
                    row.forEach(cell => {
                        html += rowIndex === 0
                            ? `<th>${cell}</th>`
                            : `<td>${cell}</td>`;
                    });
                    html += '</tr>';
                });

                html += `</table>`;
                document.getElementById('excelPreview').innerHTML = html;
                new bootstrap.Modal(document.getElementById('excelModal')).show();
            })
            .catch(error => {
                alert('Gagal memuat isi file Excel.');
                console.error(error);
            });
    }
</script>


@section('content')
    <div class="container">
        <h2 class="mb-4">Isi File Excel - Permintaan Cabang</h2>

        <a href="{{ route('requests.index') }}" class="btn btn-secondary mb-3">Kembali</a>

        <div class="table-responsive">
            <table class="table table-bordered">
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
