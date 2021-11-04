<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = ['odnosi się do grupy/osoby w związku jakąś jej cechą', 'zawiera obraźliwe określenia grup/osób', 'nawołuje do agresji, podżega do nienawiści','zawiera groźby','zawiera wyrazy nienawiści','zawiera wyrazy obrzydzenia, pogardy, dehumanizacji, znieważenia','zawiera negatywne stereotypy, tezy oczerniające lub szkalujące','usprawiedliwia agresję, dyskryminację, gorsze traktowanie jakieś osoby/grupy','wyraża agresywny nacjonalizm'];
        foreach ($topics as $topic) {
            DB::table('topics')->insert([
                'name' => $topic
            ]);
        }
    }
}
