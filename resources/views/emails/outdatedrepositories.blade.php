@component('mail::message')
    # Outdated Packages

    <table class="table composer-outdated-table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Vendor</th>
            <th scope="col">Package</th>
            <th scope="col">Version</th>
        </tr>
        </thead>
        <tbody>
        @foreach($composerOutdated as $package)
            <tr>
                {{ $package['vendor'] }}
                {{ $package['package'] }}
                {{ $package['version'] }}
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ config('app.name') }}
@endcomponent
