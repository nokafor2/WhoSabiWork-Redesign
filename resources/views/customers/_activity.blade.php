<div class="container">
    <div class="row">
        {{-- <div class="card" style="width: 100%;">
            <div class="card-body">
                <h5 class="card-title">Most Talked About User</h5>
                <h6 class="card-subtitle text-muted">
                    Who people are currently talking about
                </h6>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostCommented as $user)
                    <li class="list-group-item">
                        <a href="{{ route('artisans.show', ['artisan' => $user->id]) }}">
                            {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}

        @card(['title' => 'Most Talked About User'])
            @slot('subtitle')
                Who people are currently talking about
            @endslot
            @slot('first_names', collect($mostCommented)->pluck('first_name'))
            @slot('last_names', collect($mostCommented)->pluck('last_name'))
            @slot('ids', collect($mostCommented)->pluck('id'))
        @endcard    
    </div>

    <div class="row mt-4">
        {{-- <div class="card" style="width: 100%;">
            <div class="card-body">
                <h5 class="card-title">Most Active Users Last Month</h5>
                <h6 class="card-subtitle text-muted">
                    Users with most posts writeen in the month
                </h6>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostActiveLastMonth as $user)
                    <li class="list-group-item">
                        <a href="{{ route('artisans.show', ['artisan' => $user->id]) }}">
                            {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}

        @card(['title' => 'Most Active Users Last Month'])
            @slot('subtitle')
                Users with most posts writeen in the month
            @endslot
            @slot('first_names', collect($mostActiveLastMonth)->pluck('first_name'))
            @slot('last_names', collect($mostActiveLastMonth)->pluck('last_name'))
            @slot('ids', collect($mostActiveLastMonth)->pluck('id'))
        @endcard
    </div>
</div>