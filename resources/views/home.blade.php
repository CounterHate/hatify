@extends('argon')
@section('content')
    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <h5>Witaj na stronie systemu do monitorowania mowy nienawiści. </h5><br>

            Chcesz dowiedzieć się, które toksyczne narracje są najpowszechniejsze? <br><br>

            Sprawdzić, jak zmieniają się w czasie? <br><br>

            Które konta szerzą najwięcej (i jakich) nienawistnych treści? <br><br>

            CounterHate odfiltrowuje z Twittera nienawistne treści i pozwala je monitorować i analizować.
        </div>
    </div>

    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <h3>FUNKCJONALNOŚCI COUNTERHATE </h3>


            <h5>Zakładka „Szukaj” (w wersji okrojonej dostępna bez logowania, w wersji pełnej po zalogowaniu)</h5>
            {{-- (w wersji okrojonej dostępna bez logowania, w wersji pełnej po zalogowaniu) <br> --}}

            <b>Wyszukiwanie treści</b> <br>

            Podstawową dla tej zakładki funkcjonalnością jest możliwość wyszukiwania interesujących nas treści. Wyszukiwać
            można na podstawie:
            <ul>
                <li> „Zawiera treść” – wypowiedzi zawierające konkretne słowa, wyrażenia, itp.</li>
                <li> „Nie zawiera treść” – odwrotność tego powyżej</li>
                <li> „Konto do wyszukiwania” – wypowiedzi publikowane przez użytkownika o konkretnej nazwie</li>
                <li> „Od” i „do” – wypowiedzi publikowane w skonkretyzowanym okresie (można doprecyzować datę, ale również
                    godzinę)</li>
                <li> „Kategorie” – wypowiedzi zakwalifikowane jako obraźliwe w stosunku do jednej z wyróżnionych mniejszości
                </li>
                <li> „Zawiera słowa kluczowe” – wypowiedzi zawierające wybrane słowo-klucz spośród wcześniej wyróżnionych
                </li>
                <li> „Nie zawiera słów kluczowych” - odwrotność tego powyżej </li>
            </ul>

            Różne parametry wyszukiwania można ze sobą mieszać, czyli na przykład można wyszukać wypowiedzi opublikowane
            przez użytkownika @jan_nowak w okresie od 1 stycznia 2023 do 2 lutego 2023 roku zawierające słowo „banan” – i
            tym podobne.<br><br>

            Wyszukane treści można np. pobrać do Excela do dalszych analiz.<br><br>

            Takie wyszukiwanie pozwala na:
            <ul>
                <li> stworzenie prostej bazy wypowiedzi, które są dla nas interesujące ze względu na dany parametr – co
                    pozwala np.
                    na badanie konkretnego wątku w interesującej nas mowie nienawiści</li>
                <li> przeprowadzenie wstępnych obserwacji internetowej mowy nienawiści do dalszych działań (monitoringowych,
                    edukacyjnych)</li>
                <li> zebranie przykładów internetowej nienawiści na potrzeby np. warsztatów, zajęć ze studentami i tym
                    podobnych
                </li>
                <li>obserwację powtarzających się wzorców, czy wręcz kopiowanych wielokrotnie w różnych kontekstach
                    wypowiedzi
                    (co
                    jest cechą charakterystyczną m.in. trolli)
                </li>
            </ul>

            <h5>Linkowanie do Twittera</h5>

            Przy każdej wypowiedzi istnieje możliwość skorzystania z przekierowania do Twittera i sprawdzenie, jak
            prezentuje się ona w szerszym kontekście – czy jest odpowiedzią na coś, czy jest elementem dyskusji, itp. To
            bardzo ważna możliwość w bardziej wnikliwej jakościowej analizie treści, a także jeśli szukamy wzajemnych
            powiązań i dynamik rozprzestrzeniania się nienawistnych treści.<br><br>

            np. w najnowszym raporcie pozwoliła na wyróżnienie taktyki „podczepiania” się trolli i siewców nienawiści pod
            popularniejsze profile celem zwiększenia zasięgów <br><br>

            Ponadto, system może każdorazowo pobrać dane dotyczące liczby reakcji na daną wypowiedź – ale sprawdzać to można
            tylko pojedynczo. To daje możliwość sprawdzenia popularności konkretnych wpisów, ich viralowości.<br><br>



            <h5>Wykresy i rankingi</h5>



            Zakładka szukaj posiada jednak nie tylko opcję „Pokaż wpisy”, ale również „Pokaż statystyki”, która pozwala na
            przyjrzenie się wyszukiwanym treściom także pod względem ilościowym.<br><br>

            Są dostępne cztery rankingi porządkujące najpopularniejsze:
            <ul>
                <li> słowa-klucze (w wersji pełnej deklinacji lub pojedynczych form fleksyjnych) </li>
                <li> czas </li>
                <li> kategorie </li>
                <li> konta </li>
            </ul>

            Dane z rankingów można pobierać do Excela do dalszych analiz.<br><br>

            Dodatkowo, system na podstawie wygenerowanych danych może tworzyć wykresy, które również można pobrać. Możliwa
            jest regulacja liczby recordów pojawiających się na wykresie (np. 10, 20, 50 najpopularniejszych).<br><br>


            <h5>Zakładka „Anotuj” (dostępna po zalogowaniu)</h5>

            Pozwala użytkownikom na ocenę zebranych przez system treści pod kątem tego, czy należy uznać je za mowę
            nienawiści, czy nie – a jeśli tak, to jakiej grupy dotyczy nienawiść. Jest to więc „cegiełka”, którą użytkownik
            może dołożyć do rozwoju systemu J – dzięki temu algorytm ma więcej danych i może lepiej dokonywać
            kategoryzacji.<br><br>

            Dla użytkowników może być o tyle pomocna, że pozwala na:
            <ul>
                <li> poobcowanie z mową nienawiści, obserwację jej tropów i mechanizów, która może być punktem wyjścia do
                    dalszego
                    poszukiwania/badania</li>
                <li> urefleksyjnienie tego, co to znaczy, że coś jest „mową nienawiści”
                </li>
                <li> obserwację własnych schematów i przyzwyczajeń myślowych związanych z tym, co uważamy za „mowę
                    nienawiści”
                </li>
            </ul>
        </div>
    </div>
@endsection
