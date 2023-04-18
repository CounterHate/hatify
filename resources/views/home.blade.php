@extends('argon')
@section('content')
    <div class="card card-frame" style="margin-bottom: 16px">
        <div class="card-body">
            <div class="row">
                <div class="col-4 d-flex flex-wrap align-items-center">
                    <img src="{{ asset('images/logo ver.png') }}" class="img-fluid">
                </div>
                <div class="col">
                    <h5>Wzdrygasz się na widok nienawiści rozlewającej się na Twitterze? Chcesz włączyć się w
                        przeciwdziałanie jej?
                        Działaj z nami!</h5>

                    CounterHate to system do monitorowania internetowej mowy nienawiści. Jeśli nie wystarcza Ci zgłaszanie
                    nienawistnych komentarzy – chcesz wiedzieć o tym więcej i działać jeszcze więcej przeciwko nim, to może
                    być
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


                    Dzięki zastosowaniu uczenia maszynowego, CounterHate odfiltrowuje z Twittera nienawistne treści – nawet
                    te,
                    które zostały już skasowane. Pozwala je monitorować i analizować, ogólnie i z podziałem na bardziej
                    szczegółowe
                    narracje i kategorie. <br><br>

                    <a href="/search/twitter" class="btn btn-success">Zacznij monitorować!</a>
                </div>
            </div>

        </div>
    </div>
@endsection
