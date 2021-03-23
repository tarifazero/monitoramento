<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Chart extends Component
{
    /**
     * The chart labels (X axis)
     *
     * @var iterable
     */
    public $labels;

    /**
     * The chart values (Y axis)
     *
     * @var iterable
     */
    public $values;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(iterable $labels, iterable $values)
    {
        $this->labels = $labels;
        $this->values = $values;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.chart');
    }
}
