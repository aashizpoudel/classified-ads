<?php 
namespace App\Extensions ;
use Illuminate\Foundation\Application as LaravalApplication;

class Application extends LaravalApplication{
	public function publicPath(){
		return $this->basePath().DIRECTORY_SEPARATOR.'..';
	}
}