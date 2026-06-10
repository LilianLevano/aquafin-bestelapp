<x-layouts.site-layout>

    <div style="text-align:center; margin-top:100px;">

        <h1>Welkom Technieker</h1>

        <p>Welkom op de Aquafin bestelapp.</p>

    </div>

</x-layouts.site-layout><x-layouts.site-layout>

    <div style="text-align:center; margin-top:100px;">

        <h1>Welkom Technieker</h1>

        <p>Welkom op de Aquafin bestelapp.</p>

        {{-- ✅ ADD THIS BUTTON --}}
        <a href="{{ route('technieker.weersomstandigheden') }}" class="weer-btn">
            Voorspelling weersomstandigheden
        </a>

    </div>

</x-layouts.site-layout>