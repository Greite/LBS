<?php
/**
* 
*/
namespace lbs\model;

class TailleSandwich extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'taille_sandwich';
	protected $primaryKey = 'id';
	public $timestamps = false;
    public $hidden = ['tarif'];

	public function sandwichs(){
        return $this->belongsToMany('lbs\model\Sandwich', 'tarif', 'taille_id', 'sand_id')->withPivot("prix");
    }
}