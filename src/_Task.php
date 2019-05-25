<?php

namespace Khronos\Domain;

// use App\Task as TaskModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Support\Responsable;
// use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Task implements ShouldQueue, Responsable
{
    use ValidatesRequests, Dispatchable, InteractsWithQueue, Queueable/*, SerializesModels*/;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    // public $queue = 'tasks';

    protected $container;

    protected $tasks = [];

    protected $actions = [
        'grant',
        'deny'
    ];

    public static $defaultActionsView = 'task.actions';

    public static $defaultView = 'task.show';

    // protected $redirectTo;

    // public $queue = 'tasks';

    // protected $model = Task::class;

    protected function boot()
    {
        foreach ($this->tasks() as $task) {
            $task::dispatch($this->employee);
        }
    }

    protected function tasks()
    {
        return $this->tasks;
    }

    /*public function getIdentifier()
    {
        return $this->entity->_id;
    }*/

    protected function container()
    {
        return $this->container ?: app();
    }

    public function actions($view = null, $data = [])
    {
        return new HtmlString(
            View::make($view ?: static::$defaultActionsView, array_merge($data, [
                'task' => $this,
                'actions' => $this->actions,
            ]))->render()
        );
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $this->handleActions($request);

        return View::make(static::$defaultView, ['task' => $this]);

        /*
        if ($request->isMethod('post')) {
            $this->container()->call([$this, 'handle']);
            // $this->purge();
            // redirect($this->redirectTo());
        }

        return $this->view();
        */
    }

    protected function handleActions($request)
    {
        return false;
    }

    /*protected function redirectTo()
    {
        return $this->redirectTo;
    }*/
}
