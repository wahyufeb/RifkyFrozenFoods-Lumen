<?php

namespace App\Services;

class DAOService
{
  public function saveData($table, $data)
  {
    return $table::create($data);
  }

  public function getAllData($table)
  {
    return $table::all();
  }

  public function getDataId($table, $fieldId)
  {
    return $table::where($fieldId)->first();
  }

  public function countData($table)
  {
    return $table::all()->count();
  }

  public function updateData($table, $fieldId, $data)
  {
    return $table::where($fieldId)->update($data);
  }

  public function updateSingleField($table, $fieldId, $data, $fieldUpdated)
  {
    return $table::where($fieldId)->update([$fieldUpdated => $data]);
  }

  public function deleteData($table, $fieldId)
  {
    return $table::where($fieldId)->delete();
  }
}
