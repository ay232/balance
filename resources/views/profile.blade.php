@extends('layouts.fin')
@section('content')
    <h1>Test</h1>
    <h2 class="text-secondary">Приветствую, {{ $currentUser->name }}</h2>
    <h4><strong>Ваш баланс:</strong> {{ $balance }}</h4>
    @include('table')
@endsection
@section('js')
    <script lang="text/javascript">
        $(document).ready(function () {
            let table = $('#operations').DataTable({
                ajax: {
                    url: '/operations/table',
                    type: 'get',
                    data: {
                        limit: 5
                    },
                    dataSrc: '',
                },
                paging: false,
                searching: false,
                createdRow: function (row, data, dataIndex) {
                    console.log(row);
                    $(row).addClass('border-bottom border-2 border-danger') ;
                }
            });
            setInterval(function () {
                table.ajax.reload(); // Перезагрузка данных таблицы через AJAX
            }, 10000);
        });
    </script>
@endsection
