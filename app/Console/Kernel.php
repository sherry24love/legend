<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reward;
use App\Models\Order;
use App\User;
use App\Models\Finance;
use function Carbon\minute;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call( function(){
        	Reward::where('status' , 0 )->chunk( 100 , function( $reward ) {
	    		foreach( $reward as $v ) {
		    		$cash = $v->cash ;
		    		$order = Order::findOrFail( $v->order_id );
		    		if( strtotime( $v->expect ) < time() ) {
		    			$v->status = 1 ;
		    			$v->save();
		    			$recUser = User::findOrFail( $v->user_id );
		    			$finance = new Finance() ;
		    			$finance->user_id = $v->user_id ;
		    			$finance->cash = $cash ;
		    			$finance->act = 'in' ;
		    			$finance->orgin_cash = $recUser->money ;
		    			$finance->result_cash = $recUser->money + $cash  ;
                        $finance->type = "推广返利，推广单号为:{$v->id}，订单编号为:{$order->id}运单号为:" . $order->waybill ;
		    			$finance->target_id = $v->id ;
		    			$finance->save();
		    			User::where('id' , $recUser->id )->where('money' , $recUser->money )->update(['money' => $finance->result_cash ] ) ;
		    		}
	    		}
	    	});
        })->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
