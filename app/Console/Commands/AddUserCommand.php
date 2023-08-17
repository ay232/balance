<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AddUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить пользователя';

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
     */
    public function handle()
    {
        $userName = $this->ask('Введите имя пользователя (логин)');
        $email = $this->ask('Введите email пользователя:');
        do {
            $password = $this->secret('Введите пароль');
            $password_confirm = $this->secret('Введите тот же пароль еще раз');
            if ($password !== $password_confirm) {
                $this->error('Вы ввели разные пароли, попробуйте еще раз');
            }
        }while($password_confirm !== $password);

        $user = User::create([
            'name' => $userName,
            'email' => $email,
            'password' => $password,
        ]);

        $this->info(sprintf('Пользователь %s успешно создан', $user->getDisplayName()));
    }
}
