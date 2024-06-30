<div>
    @if (session()->has('failed_req'))
        <p class="text-red-600">{{ session('failed_req') }}</p>
    @endif
    <div class=" w-1/2 mx-auto shadow-sm overflow-clip rounded-lg ring-1 ring-gray-600/20">
        <table class="mt-8">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Institution</th>
                    <th>A payer</th>
                    <th>Demarage de cours</th>
                    <th>Specialite</th>
                    <th>Promotion</th>
                    <th>Duree</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach (auth()->user()->courses as $course)
                    <tr>
                        <td>{{ $course->type }}</td>
                        <td>{{ $course->institution->name }}</td>
                        <td>{{ $course->cost }}</td>
                        <td>{{ $course->year }}-S{{ $course->semester }}</td>
                        <td>{{ $course->major->name }}</td>
                        <td>Promo {{ $course->promo }}</td>
                        <td>{{ $course->cycle }}</td>
                        <td class="text-right"><a href="" wire:click.prevent="pay({{ $course->id }})"
                                class="text-blue-600 font-semibold">Payer</a></td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
