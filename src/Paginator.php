<?php declare(strict_types=1);

namespace Restive;

class Paginator
{

    public function __construct()
    {
        $this->limit = 10;
    }

    public function paginate($query, $request)
    {
        $this->setPaginationParameters($request);
        if ($request->input('paginate', 'yes') === 'no') {
            return $query->get();
        }
        return $query->paginate($this->limit);
    }

    protected function setPaginationParameters($request)
    {
        $this->limit = $request->input('limit', $this->limit);
    }
}