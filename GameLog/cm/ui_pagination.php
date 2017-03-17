<?php

    function create_pagination($number,$active,$link)
    {
        echo '<ul class="pagination">';

        for($i=0;$i<$number;$i++)
        {
            if($i==$active)
                echo '<li class="active"><a href="'.$link.$i.'">'.($i+1).'</a></li>';
            else
                echo '<li><a href="'.$link.$i.'">'.($i+1).'</a></li>';
        }

        echo '</u>';
    }
