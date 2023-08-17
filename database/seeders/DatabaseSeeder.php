<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserBalance;
use App\Services\FinanceService\FinanceService;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Throwable
     */
    public function run()
    {
        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '123123'
            ]);
        $faker = Factory::create('ru_RU');
        if (User::all()->count() < 10) {
            User::factory(10)->create();
        }
        DB::table('user_balances')->delete();
        DB::table('operations')->delete();

        User::all()->each(function (User $user) use ($faker){
            $dates = collect();
            for ($i=1;$i<30;$i++){
                $date = Carbon::now()->subMonths($i)->addDays($faker->numberBetween(1,5))
                ->subDays($faker->numberBetween(1,5));
                $dates->add($date);
            }

            $dates->each(function (Carbon $date) use ($user, $faker){
                $amount = $faker->numberBetween(1000,100000)/100;
                $debit = $faker->boolean(70);
                $userBalanceSum = FinanceService::getUserBalance($user)->balance;
                if ($userBalanceSum < $amount || $debit) {
                    FinanceService::debit($user, $amount, $date, $faker->text(100));
                } else {
                    FinanceService::credit($user, $amount, $date, $faker->text(100));
                }
            });
        });

    }
}
