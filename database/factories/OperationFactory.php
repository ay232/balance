<?php

namespace Database\Factories;

use App\Models\Operation;
use App\Models\User;
use App\Services\FinanceService\FinanceService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'        => User::factory(),
            'type'           => $this->faker->randomElement([Operation::OPERATION_DEBIT, Operation::OPERATION_CREDIT]),
            'amount'         => $this->faker->numberBetween(10, 1000),
            'operation_date' => Carbon::now(),
            'description'    => $this->faker->text(),
        ];
    }

    public function setType(string $type): self
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
            ];
        });
    }

    public function setAmount(int|float $amount): self
    {
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'amount' => $amount,
            ];
        });
    }

    public function setOperationDate(mixed $operationDate): self
    {
        return $this->state(function (array $attributes) use ($operationDate) {
            return [
                'operation_date' => $operationDate,
            ];
        });
    }

    public function setUser(User $user): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

}
