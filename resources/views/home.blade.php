@extends('argon')
@section('content')
    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <h3>Powitanie</h3>
            Wzdrygasz się na widok nienawiści rozlewającej się na Twitterze? Chcesz włączyć się w przeciwdziałanie jej?
            Działaj z nami!<br><br>

            CounterHate to system do monitorowania internetowej mowy nienawiści. Jeśli nie wystarcza Ci zgłaszanie
            nienawistnych komentarzy – chcesz wiedzieć o tym więcej i działać jeszcze więcej przeciwko nim, to może być
            narzędzie dla Ciebie. <br><br>

            Chcesz wiedzieć:
            <ul>
                <li> które toksyczne narracje są najpowszechniejsze?
                </li>
                <li> jak zmieniają się w czasie?
                </li>
                <li> które konta szerzą najwięcej (i jakich) nienawistnych treści?
                </li>
            </ul>



            Dzięki zastosowaniu uczenia maszynowego, CounterHate odfiltrowuje z Twittera nienawistne treści – nawet te,
            które zostały już skasowane. Pozwala je monitorować i analizować, ogólnie i z podziałem na bardziej szczegółowe
            narracje i kategorie. <br><br>

            Zacznij monitorować!
        </div>
    </div>

    {{-- <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <h5>Witaj na stronie systemu do monitorowania mowy nienawiści. </h5><br>

            Chcesz dowiedzieć się, które toksyczne narracje są najpowszechniejsze? <br><br>

            Sprawdzić, jak zmieniają się w czasie? <br><br>

            Które konta szerzą najwięcej (i jakich) nienawistnych treści? <br><br>

            CounterHate odfiltrowuje z Twittera nienawistne treści i pozwala je monitorować i analizować.
        </div>
    </div> --}}

    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <h3>Mozliwości i ograniczenia systemu </h3>
            <h5>Wyszukiwanie treści</h5>

            Pozwala na wyszukiwanie w bazie wpisów mowy nienawiści pod względem jej rodzaju (kategorii), zawartości (treści,
            konkretnych słów), kont czy zakresu czasu.<br><br>

            <h5>Wykresy i rankingi</h5>

            Przeszukiwane treści można wizualizować na wykresach i rankingach. Ta opcja pozwala na prześledzenie zmian
            nasilenia mowy nienawiści w czasie, tworzenie rankingów nienawistnych kont lub kluczowych nienawistnych słów.
            Wykresy można pobierać jako pliki graficzne.<br><br>

            <h5>Pobieranie danych</h5>

            Wyszukane treści można pobrać w formie arkusza kalkulacyjnego.<br><br>

            <h5>Sprawdzenie kontekstu wypowiedzi</h5>

            Każdy wpis można zobaczyć w oryginalnym kontekście na Twitterze i sprawdzić, jak prezentuje się ona w szerszym
            kontekście – czy jest odpowiedzią na coś, czy jest elementem dyskusji, itp. Ta opcja ułatwia badanie dynamik
            rozpowszechniania się nienawistnych treści.<br><br>

            <h5>Anotowanie treści</h5>

            Pozwala użytkownikom na ocenę zebranych przez system treści pod kątem tego, czy należy uznać je za mowę
            nienawiści, czy nie – a jeśli tak, to jakiej grupy dotyczy nienawiść. Jest to więc „cegiełka”, którą użytkownik
            może dołożyć do rozwoju systemu – dzięki temu algorytm ma więcej danych i może lepiej dokonywać kategoryzacji.<br><br>

            <h5>Ograniczenia</h5>

            System zaciąga dane z Twittera poprzez tzw. „stream”. Twitter nie informuje podmiotów korzystających ze streama
            o wprowadzanych tam technicznych zmianach. W związku z tym są okresy, w których system nie pobierał danych. Są
            to:
            <ul>
                <li> lipiec-sierpień 2022 r.;
                </li>
                <li> październik 2022 r.
                </li>
            </ul>

        </div>
    </div>

    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <h3>O projekcie</h3><br>
            Powstanie i popularyzacja mediów społecznościowych stworzyły nowy, równoległy do tradycyjnych mediów ekosystem
            obiegu informacji. Przez media społecznościowe dowolne, niezweryfikowane albo szkodliwe treści mogą trafiać do
            setek tysięcy odbiorców. Tworzą się więc warunki, w których mowa nienawiści może szczególnie łatwo się
            rozprzestrzeniać. A to potrafi realnie wpływać na zmiany postaw społecznych na wielką skalę. W Polsce masowe
            kampanie dezinformacyjne doprowadzały w przeszłości choćby do zwiększenia niechęci do niektórych grup
            mniejszościowych – uchodźców, Żydów, społeczności LGBTQ i ogólnie przedstawicieli innych kultur. <br><br>

            W ramach projektu powstały dwa raporty analizujące nienawistne dyskursy w mediach społecznościowych: <br><br>

            <ul>
                <li> <a href="https://geremek.pl/publikacja/granice_nienawisci/">Granice Nienawiści</a> analiza mowy
                    nienawiści towarzyszącą dwóm kryzysom uchodźczym: związanym z inwazją
                    Rosji na Ukrainę oraz z sytuacją na granicy polsko-białoruskiej.
                </li>
                <li> <a href="https://geremek.pl/publikacja/przyjda_i_zabiora/">Przyjdą i zabiorą</a> analiza
                    antyukraińskiej mowy nienawiści.
                </li>
            </ul>


            Grantodawca: Komisja Europejska: Justice Programme (JUST); Rights, Equality and Citizenship Programme (REC)
            <br><br>

            Autorzy systemu:
            <ul>
                <li><a href="https://geremek.pl/">Fundacja Geremka</a></li>

                <li><a href="https://hfhr.pl/">Helsińska Fundacja Praw Człowieka</a></li>

                <li>SecureLex Sp. z o.o.</li>
            </ul>
        </div>
    </div>
@endsection
