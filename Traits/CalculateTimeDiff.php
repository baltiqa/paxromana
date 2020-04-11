<?php
namespace App\Traits;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use jpmurray\LaravelCountdown\Countdown;
trait CalculateTimeDiff
{
    /**
     * Return elapsed time based in model attribite
     *
     * @param  string $attribute
     * @return jpmurray\LaravelCountdown\Countdown $countdown
     */
    public function elapsed($attribute)
    {
        $countdown = app('jpmurray.countdown');
        $attribute = $this->{$attribute};
        $now = Carbon::now();

        return $countdown->from($now)
                         ->to($attribute)->get();
    }

    public function until($attribute)
    {
        $countdown = app('jpmurray.countdown');
        $attribute = $this->{$attribute};
        $now = Carbon::now();

        return $countdown->from($now)
                         ->to($attribute)->get();
    }

    public function finalize($attribute)
    {
        $countdown = app('jpmurray.countdown');
        $fuck = $this->{$attribute}->addDay(14);
        // $dategood = $this->{$attribute};

        // dd("date later:".$fuck. "  Date bought:". $dategood);
        return $countdown->from($fuck->toDateTimeString())
                         ->to(Carbon::now())->get();
    }

    public function autocancel($attribute)
    {
        $countdown = app('jpmurray.countdown');
        $fuck = $this->{$attribute}->addDay(3);
        // $dategood = $this->{$attribute};

        // dd("date later:".$fuck. "  Date bought:". $dategood);
        return $countdown->from($fuck->toDateTimeString())
                         ->to(Carbon::now())->get();
    }

}