<?php
/**
* 
*/
namespace lbs\model;

class Sandwich extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'sandwich';
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $hidden = ['pivot'];

    public function categories(){
        return $this->belongsToMany('lbs\model\Categorie', 'sand2cat', 'sand_id', 'cat_id');
    }

    public function tailleSandwichs(){
        return $this->belongsToMany('lbs\model\TailleSandwich', 'tarif', 'sand_id', 'taille_id')->as('tarif')->withPivot('prix');
    }

}