@extends('layouts.app')

@section('content')
    <div style="padding: 40px; text-align: left;">

        <h2 style="margin-bottom: 24px;">Materiaal Catalogus</h2>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">

            <!-- Product 1 -->
            <div style="background:white; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="padding:20px;">
                    <h5>🔧 Veiligheidshelm</h5>
                    <p>Beschermhelm voor op de werf.</p>
                    <span style="background:#198754; color:white; padding:4px 10px; border-radius:5px; font-size:13px;">Beschikbaar</span>
                </div>
                <div style="padding:12px 20px; border-top:1px solid #eee;">
                    <a href="#" class="btn-primary" style="display:block; text-align:center;">Bestellen</a>
                </div>
            </div>

            <!-- Product 2 -->
            <div style="background:white; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="padding:20px;">
                    <h5>Veiligheidsvest</h5>
                    <p>Fluoriserend vest voor zichtbaarheid.</p>
                    <span style="background:#198754; color:white; padding:4px 10px; border-radius:5px; font-size:13px;">Beschikbaar</span>
                </div>
                <div style="padding:12px 20px; border-top:1px solid #eee;">
                    <a href="#" class="btn-primary" style="display:block; text-align:center;">Bestellen</a>
                </div>
            </div>

            <!-- Product 3 -->
            <div style="background:white; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="padding:20px;">
                    <h5>Werkhandschoenen</h5>
                    <p>Stevige handschoenen voor zwaar werk.</p>
                    <span style="background:#ffc107; color:#000; padding:4px 10px; border-radius:5px; font-size:13px;">Beperkte voorraad</span>
                </div>
                <div style="padding:12px 20px; border-top:1px solid #eee;">
                    <a href="#" class="btn-primary" style="display:block; text-align:center;">Bestellen</a>
                </div>
            </div>

            <!-- Product 4 -->
            <div style="background:white; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="padding:20px;">
                    <h5>Veiligheidsbril</h5>
                    <p>Beschermt de ogen tegen spatten.</p>
                    <span style="background:#198754; color:white; padding:4px 10px; border-radius:5px; font-size:13px;">Beschikbaar</span>
                </div>
                <div style="padding:12px 20px; border-top:1px solid #eee;">
                    <a href="#" class="btn-primary" style="display:block; text-align:center;">Bestellen</a>
                </div>
            </div>

            <!-- Product 5 -->
            <div style="background:white; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="padding:20px;">
                    <h5>Veiligheidsschoenen</h5>
                    <p>Stevige schoenen met stalen neus.</p>
                    <span style="background:#dc3545; color:white; padding:4px 10px; border-radius:5px; font-size:13px;">Niet beschikbaar</span>
                </div>
                <div style="padding:12px 20px; border-top:1px solid #eee;">
                    <a href="#" class="btn-primary" style="display:block; text-align:center; opacity:0.6; pointer-events:none;">Bestellen</a>
                </div>
            </div>

        </div>
    </div>
@endsection
