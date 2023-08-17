<?php

namespace App\Console\Commands;

use App\Jobs\CreateNewOperationJob;
use App\Models\Operation;
use App\Models\User;
use App\Services\FinanceService\FinanceService;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Person;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Question\ChoiceQuestion;

class AddOperationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operation:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {

        do {
            $dateTime = $this->ask('Введите дату операции и время в формате ДД-ММ-ГГГГ ЧЧ:ММ:СС (или оставьте поле пустым, чтобы операция была записана текущим временем');
            try {
                $dateTime = Carbon::parse($dateTime);
            } catch (\Exception $e) {
                $dateTime = null;
                $this->error('Вы ввели неверное значение, попробуйте еще раз');
            }
        } while (! $dateTime);

        $operationName = $this->choice('Выберите тип операции', ['Начисление', 'Списание'], 'Начисление');
        switch ($operationName) {
            case 'Начисление':
                $operationType = Operation::OPERATION_DEBIT;
                break;
            case 'Списание':
                $operationType = Operation::OPERATION_CREDIT;
                break;
            default:
                throw new \Exception('Неверное значение');
        }
        $usernames = User::all()->pluck('name')->toArray();
        $helper = $this->getHelper('question');
        $usernameQuestion = new ChoiceQuestion('Выберите пользователя', $usernames);
        $username = $helper->ask($this->input, $this->output, $usernameQuestion);
        $user = User::where('name', $username)->firstOrFail();
        $userBalance = FinanceService::getUserBalance($user);
        do {
            $this->info(sprintf('Баланс пользователя: %s', $userBalance->balance));
            $amount = $this->ask('Введите сумму операции (делитель дробной части - точка)');
            if (! is_numeric($amount) || $amount <= 0) {
                $this->error('Необходимо ввести число больше нуля');
            } elseif ($operationType === Operation::OPERATION_CREDIT && $amount > $userBalance->balance) {
                $this->error('Нельзя списывать сумму, превышающую баланс пользователя');
            }
        } while ((! is_numeric($amount) || $amount <= 0) || ($operationType === Operation::OPERATION_CREDIT && $amount > $userBalance->balance));
        $description = $this->ask('Введите примечание (необязательно)');
        $this->info(Str::padBoth(' СОЗДАВАЕМАЯ ОПЕРАЦИЯ ', 100, '*'));
        $this->info(sprintf(
            'ДАТА: %s%sСУММА: %s%sТИП: %s%sПримечание: %s',
            $dateTime,
            PHP_EOL,
            $amount,
            PHP_EOL,
            $operationName,
            PHP_EOL,
            $description
        ));
        $confirm = $this->confirm('Всё верно?', true);
        if (! $confirm) {
            throw new \Exception('Операция прервана, попробуйте начать снова и ввести корректные данные');
        }
        $job = new CreateNewOperationJob($user, $operationType, $dateTime, $amount, $description);
        dispatch($job);
    }
}
