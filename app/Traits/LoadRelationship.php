<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

trait LoadRelationship{
    public function applyIncludeRelation(Model|Builder|EloquentBuilder|HasMany $instance,
    ?array $acceptedRelations =[]){
        $include = request()->query("include");
        $frontendRequest = $include ? array_map("trim",explode(",",$include)): [];
        foreach($acceptedRelations as $relation){
            $instance->when(
                in_array($relation,$frontendRequest),
                fn($q) => $instance instanceof Model? $instance->load($relation) : $q->with($relation)
            );
        }
        return $instance;
    }
}