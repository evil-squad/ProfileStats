<?php

    function create_select()//$label,$name,$selected,$items)
    {
    	$label     =func_get_arg(0);
    	$name      =func_get_arg(1);
    	$selected  =func_get_arg(2);
    	$items     =func_get_arg(3);

    	echo '<div class="form-group" style="margin-left: 0px; margin-right: 0px;">';

    	if($label)
    	{
            echo '<label class="control-label col-sm-2">'.$label.'</label>
                <div class="col-sm-10">';
        }

//     	echo '<div class="dropdown">
//                 <button class="btn btn-default dropdown-toggle" type="button" id="'.$label.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
//                     '.$name.'
//                     <span class="caret"></span>
//                 </button>
//                 <ul class="dropdown-menu" aria-labelledby="'.$label.'">';
//
//                 foreach($items as $value=>$text)
//                 {
//                     if($selected==$value)
//                         echo '<option value="'.$value.'" selected="selected">'.$text.'</option>';
//                     else
//                         echo '<li><a href="#">'.$text.'</a></li>';
//                         echo '<option value="'.$value.'">'.$text.'</option>';
//                 }
//                     <li><a href="#">Action</a></li>
//                     <li><a href="#">Another action</a></li>
//                     <li><a href="#">Something else here</a></li>
//         echo '</ul>
//             </div>';

        echo '<select name="'.$name.'">';

            foreach($items as $value=>$text)
            {
                if($selected==$value)
                    echo '<option value="'.$value.'" selected="selected">'.$text.'</option>';
                else
                    echo '<option value="'.$value.'">'.$text.'</option>';
            }

        echo '</select>';

    	if(func_num_args()==5)
    	{
            $right_label=func_get_arg(4);
            echo '<span class="help-block">'.$right_label.'</span>';
        }

        if($label)
            echo '</div>';

        echo '</div>';
    }
?>
