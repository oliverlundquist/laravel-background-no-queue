<?php declare(strict_types=1);

use App\Cache\BackgroundRequestHandler;
use App\Data\BackgroundRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function ()
{
    return view('index');
});

Route::post('some-logic-that-takes-forever-to-process', function ()
{
    $requestId      = str()->ulid()->toBase32();
    $requestHandler = new BackgroundRequestHandler;
    $request        = new BackgroundRequest(requestId: $requestId, progress: 0, completed: false);

    $requestHandler->storeRequest($request);

    if (function_exists('fastcgi_finish_request')) {
        redirect("background-request-progress/{$requestId}")->send();
        fastcgi_finish_request();
    }

    foreach ([25, 50, 75, 100] as $progress) {
        sleep(2); // increment progress by 25% every 2 seconds
        $request->progress  = $progress;
        $request->completed = $progress === 100;
        $requestHandler->storeRequest($request);
    }
});

Route::get('background-request-progress/{request}', function (BackgroundRequest $request)
{
    return $request->completed
        ? redirect('/')
        : view('progress', compact('request'));
});

Route::get('test-timeout', function ()
{
    // to flush output all the way to load balancer, set
    //   fastcgi_buffering off;
    // on the PHP instance or set the following header
    //   header('X-Accel-Buffering: no');

    ini_set('max_execution_time', '20');
    $start = microtime(true);

    $last = 0;
    while (($diff = intval(microtime(true) - $start)) < 75) {
        if ($diff > $last) {
            $last = $diff;
            file_put_contents(storage_path('logs/attempt1.txt'), 'running for: ' . $last . ' sec (' . $_SERVER['REQUEST_URI'] . ')'. PHP_EOL, FILE_APPEND);

            // flush without PHP aborting script when 499 has occured
            //   ignore_user_abort(true);

            // echo something and flushing the output buffer
            // will prevent nginx from timing out before an nginx timeout but will stop the script if the browser or nginx server went away.
            // unless ignore_user_abort(true); is called
            //   echo 'hold the line';
            //   flush();
        }
    }
    echo 'script finished execution';
});

Route::get('fpm-status', function () {
    $fpmStatus = fpm_get_status();

    return 'Max child processes reached '
        . $fpmStatus['max-children-reached']
        . ' times, since: '
        . Carbon::createFromTimestampUTC($fpmStatus['start-time'])
            ->format('Y-m-d H:i:s') . ' UTC';
});
