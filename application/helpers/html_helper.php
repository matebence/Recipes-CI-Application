<?php defined('BASEPATH') OR exit('No direct script access allowed');

function paginate_links($per_page, $total_results, $controller)
{
    $output = "<ul class='pagination'>";
    $number_of_pages = 0;

    for($i = 0; $i<$total_results; $i+=$per_page){
        $number_of_pages++;
        $output .= "<li class='page-item'><a href=".site_url()."/".$controller."?offset=".$i.">".$number_of_pages." </a></li>";
    }
    $output .= "</ul>";

    return $output;
}