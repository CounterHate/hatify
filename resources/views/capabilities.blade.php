@extends('argon')
@section('content')
    <div class="card card-frame" style="margin-bottom: 16px">


        <div class="card card-frame" style="margin-bottom: 16px">
            <div class="card-body">
                <h3>Możliwości i ograniczenia systemu </h3>
                <h5>Wyszukiwanie treści</h5>

                Pozwala na wyszukiwanie w bazie wpisów mowy nienawiści pod względem jej rodzaju (kategorii), zawartości
                (treści,
                konkretnych słów), kont czy zakresu czasu.<br><br>

                <h5>Wykresy i rankingi</h5>

                Przeszukiwane treści można wizualizować na wykresach i rankingach. Ta opcja pozwala na prześledzenie zmian
                nasilenia mowy nienawiści w czasie, tworzenie rankingów nienawistnych kont lub kluczowych nienawistnych
                słów.
                Wykresy można pobierać jako pliki graficzne.<br><br>

                <h5>Pobieranie danych</h5>

                Wyszukane treści można pobrać w formie arkusza kalkulacyjnego.<br><br>

                <h5>Sprawdzenie kontekstu wypowiedzi</h5>

                Każdy wpis można zobaczyć w oryginalnym kontekście na Twitterze i sprawdzić, jak prezentuje się ona w
                szerszym
                kontekście – czy jest odpowiedzią na coś, czy jest elementem dyskusji, itp. Ta opcja ułatwia badanie dynamik
                rozpowszechniania się nienawistnych treści.<br><br>

                <h5>Anotowanie treści</h5>

                Pozwala użytkownikom na ocenę zebranych przez system treści pod kątem tego, czy należy uznać je za mowę
                nienawiści, czy nie – a jeśli tak, to jakiej grupy dotyczy nienawiść. Jest to więc „cegiełka”, którą
                użytkownik
                może dołożyć do rozwoju systemu – dzięki temu algorytm ma więcej danych i może lepiej dokonywać
                kategoryzacji.<br><br>

                <h5>Ograniczenia</h5>

                System zaciąga dane z Twittera poprzez tzw. „stream”. Twitter nie informuje podmiotów korzystających ze
                streama
                o wprowadzanych tam technicznych zmianach. W związku z tym są okresy, w których system nie pobierał danych.
                Są
                to:
                <ul>
                    <li> lipiec-sierpień 2022 r.;
                    </li>
                    <li> październik 2022 r.
                    </li>
                </ul>

            </div>
        </div>
    @endsection
