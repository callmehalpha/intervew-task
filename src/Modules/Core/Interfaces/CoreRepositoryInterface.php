<?php

namespace Modules\Core\Interfaces;

interface CoreRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update($id, array $data);

    public function updateByAnotherColumn($column, $query, array $data);

    public function updateOrCreate(array $query, array $data);

    public function delete($id);

    /**
     * @param $condition , holds the db field
     * @param $query , hold the query value
     * @param $model
     * @return mixed
     */
    public function queryWithACondition($condition, $query, array $with);

    public function show($id, array $with, $column);

    public function findTheFirstOne($condition, $query, array $with);

    /**
     * Fetch a specific column
     */
    public function find($condition, $query, array $columns = null);
}
