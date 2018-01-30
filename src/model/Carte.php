<?php
/**
 *
 */
namespace lbs\model;

class Carte extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'carte';
    protected $primaryKey = 'id_carte';
    public $timestamps = false;
}