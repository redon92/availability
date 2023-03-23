<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Calendar
        </h2>
    </x-slot>
    <div id="calendar-app" data-user-id="{{ Auth::user()->id }}"></div>

</x-app-layout>
