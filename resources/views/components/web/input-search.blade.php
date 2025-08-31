<div {{ $attributes->merge(['class' => 'input-group my-2']) }}>
    <input type="number" class="form-control" min="1" placeholder="Search hadith..." id="hadith-number" />
    <button type="button" class="btn btn-primary" onclick="searchHadithByNumber()">
        Go To
    </button>
</div>
