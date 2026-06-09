@extends('layouts.app')

@section('content')
    <h2 class="mb-4"> Bestelformulier</h2>

    <div class="card shadow p-4">
        <form action="#" method="POST">
            @csrf

            <!-- Naam -->
            <div class="mb-3">
                <label class="form-label fw-bold">Naam</label>
                <input type="text" name="naam" class="form-control" placeholder="Jouw naam" required>
            </div>

            <!-- E-mail -->
            <div class="mb-3">
                <label class="form-label fw-bold">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="jouw@email.be" required>
            </div>

            <!-- Afdeling -->
            <div class="mb-3">
                <label class="form-label fw-bold">Afdeling</label>
                <select name="afdeling" class="form-select" required>
                    <option value="">-- Kies een afdeling --</option>
                    <option value="technisch">Technisch</option>
                    <option value="administratie">Administratie</option>
                    <option value="onderhoud">Onderhoud</option>
                </select>
            </div>

            <!-- Materiaal -->
            <div class="mb-3">
                <label class="form-label fw-bold">Materiaal</label>
                <input type="text" name="materiaal" class="form-control" placeholder="Wat wil je bestellen?" required>
            </div>

            <!-- Aantal -->
            <div class="mb-3">
                <label class="form-label fw-bold">Aantal</label>
                <input type="number" name="aantal" class="form-control" min="1" value="1" required>
            </div>

            <!-- Opmerking -->
            <div class="mb-3">
                <label class="form-label fw-bold">Opmerking</label>
                <textarea name="opmerking" class="form-control" rows="3" placeholder="Extra informatie..."></textarea>
            </div>

            <!-- Knop -->
            <button type="submit" class="btn btn-primary w-100">
                 Bestelling plaatsen
            </button>

        </form>
    </div>
@endsection
