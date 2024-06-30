<x-app>
    <main class="mt-5 mx-10">
        <div class="">
            <h1 class="text-3xl text-center">Hiya, {{ Auth::user()->first_name }}</h1>
            @livewire('list-courses')
        </div>
    </main>
</x-app>
