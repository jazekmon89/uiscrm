<?php

namespace App\Helpers;

use Illuminate\Support\Support\Collection;
use Illuminate\Http\Request;
use App\Providers\Facades\Entity;

class PaginationHelper {

	protected $max_records_to_display = 0, $default_number_of_pages_display;

	public function __construct($max_display = 50, $number_of_pages_display = 5) {
		$max_records_to_display = $max_display;
		$default_number_of_pages_display = $number_of_pages_display;
	}

    private function paginate_records($data, $page = 1){
        $collection = collect($data);
        return $collection->forPage($page, $this->max_records_to_display)->all();
    }

    public function getRenderedPagination($data, $page){
    	$chunked_records = $this->paginate_records($data, $page);
    	$pagination = $this->
    	return [$chunked_records, $pagination];
    }

    private function generatePagination(){
    	
    }
}
?>