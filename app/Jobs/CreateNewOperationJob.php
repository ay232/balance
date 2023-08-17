<?php

namespace App\Jobs;

use App\Models\Operation;
use App\Models\User;
use App\Services\FinanceService\FinanceService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateNewOperationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;

    protected string $type;

    private float $amount;

    private string|null $description;

    private Carbon $datetime;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $type, Carbon $datetime, float $amount, ?string $description = '')
    {
        $this->user = $user;
        $this->type = $type;
        $this->amount = $amount;
        $this->description = $description;
        $this->datetime = $datetime;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        switch ($this->type) {
            case  Operation::OPERATION_DEBIT:
                FinanceService::debit($this->user, $this->amount, $this->datetime, $this->description);
                break;
            case Operation::OPERATION_CREDIT:
                FinanceService::credit($this->user, $this->amount, $this->datetime, $this->description);
                break;
        }
    }
}
