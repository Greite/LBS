<?php
/**
 *
 */
namespace lbs\model;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;
}