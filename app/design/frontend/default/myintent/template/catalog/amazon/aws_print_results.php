<?php

function printSearchResults($parsed_xml){

    foreach($parsed_xml->Items->Item as $current)
    {
        print("<div class='book'>");

            print("<div class='img-container'>");

                // Image
                print("<a href=".$current->DetailPageURL." target='_blank' >"); // url to product on Amazon

                    if (isset($current->LargeImage->URL)) 
                    {    
                        print("<img src=".$current->LargeImage->URL.">");
                    }
                    else
                    {
                        print("<center>No image</center>");
                    }

                print("</a>");

            print("</div>");

            // Book details
            print("<div class='book-info'>");

                // Title
                if (isset($current->ItemAttributes->Title)) 
                {    
                    print("<a href=".$current->DetailPageURL." class='title' target='_blank' >".$current->ItemAttributes->Title."</a>");
                }

                // Price: Use lowest new price if available; otherwise, list price
                if (isset($current->OfferSummary->LowestNewPrice->FormattedPrice)) 
                {
                    print("<div class='price'>".$current->OfferSummary->LowestNewPrice->FormattedPrice."</div>");
                } 
                elseif (isset($current->ItemAttributes->FormattedPrice)) 
                {
                    print("<div class='price'>".$current->ItemAttributes->FormattedPrice."</div>");
                } 

                // Author
                if(isset($current->ItemAttributes->Author)) 
                {
                    print("<div class='author'>By: ".$current->ItemAttributes->Author."</div>");
                } 

            print("</div>");

        print("</div>");            
    }
}

?>
