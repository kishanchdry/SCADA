<?php

// if ($handle = opendir('../../logs/LogFiles/W3SVC1')) {
//     echo "Directory handle: $handle\n";
//     echo "Entries:\n";

//     /* This is the correct way to loop over the directory. */
//     while (false !== ($entry = readdir($handle))) {
//         echo "$entry\n";
//     }

//     /* This is the WRONG way to loop over the directory. */
//     // while ($entry = readdir($handle)) {
//     //     echo "$entry\n";
//     // }

//     closedir($handle);
// }


if ($handle = opendir('../../../logs/LogFiles/W3SVC1/')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

            echo "$entry\n";
        }
    }

    closedir($handle);
}
?>