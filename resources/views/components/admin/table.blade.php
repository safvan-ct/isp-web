@props(['headers' => []])

<div class="table-responsive">
    <table class="table table-hover table-striped text-center" id="dataTable">
        <thead class="table-light">
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody id="sortableBody">
            {{ $slot }}
        </tbody>
    </table>
</div>
