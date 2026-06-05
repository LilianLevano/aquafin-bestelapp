<?php

use Illuminate\Support\Facades\Route;

Route::get('/manager', function () {
    return view('manager-welkom');
});

Route::get('/manager/bestellingen', function () {
    return view('manager-bestellingen');
});

Route::get('/manager/bestelling-detail', function () {
    return view('manager-bestelling-detail');
});

Route::get('/magazijnier', function () {
    return view('magazijnier-welkom');
});

Route::get('/magazijnier/bestellingen', function () {
    return view('magazijnier-bestellingen');
});

Route::get('/technieker', function () {
    return view('technieker-welkom');
});

Route::get('/technieker/bestellen', function () {
    return view('technieker-bestellen');
});

Route::get('/technieker/catalogus', function () {
    return view('technieker-catalogus');
});

Route::get('/admin/aanvragen', function () {
    return view('admin-aanvragen');
});

Route::get('/admin/antwoord', function () {
    return view('admin-aanvraag-antwoord');
});

Route::get('/admin/catalogus', function () {
    return view('admin-catalogus');
});

Route::get('/admin/catalogus/materiaal', function () {
    return view('admin-catalogus-materiaal');
});

Route::get('/admin/catalogus/edit', function () {
    return view('admin-catalogus-edit');
});