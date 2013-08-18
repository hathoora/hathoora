<?php
namespace hathoora\grid;

class pagination
{
    /**
     * Simle pagination @ http://harishankar.org/blog/entry.php/function-to-generate-a-pagination-link-list-in-php
     */
    public static function simple($arrParams = array())
    {
        $total_pages =  !empty($arrParams['totalPages']) ? $arrParams['totalPages'] : null;
        $current_page =  !empty($arrParams['currentPage']) ? $arrParams['currentPage'] : null;
        $base_url =  !empty($arrParams['baseURL']) ? $arrParams['baseURL'] : null;
        $query_str =  !empty($arrParams['queryString']) ? $arrParams['queryString'] : 'page';
        
        $paginate_limit = 10;
        
        // Array to store page link list
        $page_array = array ();
        
        // Show dots flag - where to show dots?
        $dotshow = true;
        
        // walk through the list of pages
        for ( $i = 1; $i <= $total_pages; $i ++ )
        {
           // If first or last page or the page number falls within the pagination limit
           // generate the links for these pages
           if ($i == 1 || $i == $total_pages || ($i >= $current_page - $paginate_limit && $i <= $current_page + $paginate_limit))
           {
              // reset the show dots flag
              $dotshow = true;
              
              // If it's the current page, leave out the link
              // otherwise set a URL field also
              if ($i != $current_page)
                  $page_array[$i]['url'] = $base_url . "?" . $query_str . "=" . $i;
                  
              $page_array[$i]['text'] = strval ($i);
           }
           
           // If ellipses dots are to be displayed (page navigation skipped)
           else if ($dotshow == true)
           {
               // set it to false, so that more than one 
               // set of ellipses is not displayed
               $dotshow = false;
               $page_array[$i]['text'] = "...";
           }
        }

        // return the navigation array
        return $page_array;
    }
}