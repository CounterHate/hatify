@extends('argon')
@section('content')
<div class="card card-frame" style="margin-bottom: 16px">
    <div class="card-body">
        <h3>Możliwości i ograniczenia systemu</h3>

        <b>Wyszukiwanie treści </b> <br>
        
        Można wyszukiwać mowę nienawiści pod względem jej rodzaju (kategorii), zawartości (treści, konkretnych słów), kont czy zakresu czasu. <br><br>
        
        <b>Wykresy i rankingi </b><br>
        
        Przeszukiwane treści można wizualizować na wykresach i rankingach. Można prześledzić zmiany nasilenia mowy nienawiści w czasie, stworzyć ranking nienawistnych kont lub kluczowych nienawistnych słów. <br><br>
        
        <b>Pobieranie danych </b><br>
        
        Wyszukane treści można pobrać w formie arkusza kalkulacyjnego. <br><br>
        
        <b>Sprawdzenie kontekstu wypowiedzi </b><br>
        
        Każdy wpis można zobaczyć w oryginalnym kontekście na Twitterze i sprawdzić, jak prezentuje się ona w szerszym kontekście – czy jest odpowiedzią na coś, czy jest elementem dyskusji, itp.  <br><br>
        
        <b>Anotowanie treści </b><br>
        
        Pozwala użytkownikom na ocenę zebranych przez system treści pod kątem tego, czy należy uznać je za mowę nienawiści, czy nie – a jeśli tak, to jakiej grupy dotyczy nienawiść. Jest to więc „cegiełka”, którą użytkownik może dołożyć do rozwoju systemu – dzięki temu algorytm ma więcej danych i może lepiej dokonywać kategoryzacji. <br><br>
        
        <b>Ograniczenia </b><br>
        
        System zaciąga dane z Twittera poprzez tzw. „stream”. Twitter nie informuje podmiotów korzystających ze streama o wprowadzanych tam technicznych zmianach. W związku z tym są okresy, w których system nie pobierał danych.
    </div>
</div>
@endsection