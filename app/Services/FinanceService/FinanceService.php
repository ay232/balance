<?php

namespace App\Services\FinanceService;

use App\Models\Operation;
use App\Models\User;
use App\Models\UserBalance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * @param User               $user
     * @param float|int          $amount
     * @param string|Carbon|null $dateTime
     * @param string|null        $description
     * @return bool
     * @throws FinanceServiceException
     * @throws \Throwable
     */
    static public function credit(User $user, float|int $amount, string|Carbon $dateTime = null, string $description = null): bool
    {
        $userBalance = self::getUserBalance($user);
        DB::beginTransaction();
        if ($userBalance->balance < $amount) {
            throw new FinanceServiceException('Попытка списания денежных средств сверх имеющихся на балансе пользователя');
        }
        $userBalance->decrement('balance', $amount);

        // Создать запись операции
        Operation::create([
            'user_id'        => $user->id,
            'type'           => Operation::OPERATION_CREDIT,
            'amount'         => $amount,
            'operation_date' => self::getDateTime($dateTime),
            'description'    => $description,
        ]);
        DB::commit();

        return true;
    }

    /**
     * @param User               $user
     * @param float|int          $amount
     * @param string|Carbon|null $dateTime
     * @param string|null        $description
     * @return bool
     * @throws \Throwable
     */
    static public function debit(User $user, float|int $amount, string|Carbon $dateTime = null, string $description = null): bool
    {
        $userBalance = self::getUserBalance($user);
        DB::beginTransaction();
        $userBalance->increment('balance', $amount);

        // Создать запись операции
        Operation::create([
            'user_id'        => $user->id,
            'type'           => Operation::OPERATION_DEBIT,
            'amount'         => $amount,
            'operation_date' => self::getDateTime($dateTime),
            'description'    => $description,
        ]);
        DB::commit();

        return true;
    }

    /**
     * @param User $user
     * @return UserBalance
     */
    static public function getUserBalance(User $user): UserBalance
    {
        return UserBalance::firstOrCreate(['user_id' => $user->getKey()],['balance' => 0]);
    }

    /**
     * @param Carbon|string $dateTime
     * @return Carbon
     */
    static protected function getDateTime(Carbon|string $dateTime): Carbon
    {
        $dateTime = $dateTime ?: now();

        return is_string($dateTime) ? Carbon::parse($dateTime) : $dateTime;
    }
}
