<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class Filters
{
	protected  Model $builder;
    protected Request $request;

    protected array $filters = [];

    /**
     * Filters constructor.
     * @param Request $request
     */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

    /**
     * @param $builder
     * @return mixed
     */
	public function apply($builder)
	{
		//revist needed
		$this->builder = $builder;

		foreach ($this->getFilters() as $filter => $value) {
			if(method_exists($this, $filter)) {
				$this->$filter($value);
			}
		}

		return $this->builder;
	}

    /**
     * @return array]
     */
	public function getFilters(): array
    {
		return $this->request->only($this->filters);
	}
}


?>
