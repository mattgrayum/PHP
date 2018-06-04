<?php
/**
 * Recursively searches through a directory and its subdirectories for a given 
 * string pattern.
 *
 * @since 1.00
 * 
 * @param string $folder                The directory in which to begin the 
 *                                      search.
 * @param array as string $words        The search terms to match.
 * @param array as string $extensions   An array of file extesions to filter
 *                                      search results by.
 * 
 * @return array|bool                   Returns an array of matching file paths 
 *                                      or false if no match is found.
 */
function recursive_file_search( $folder, $words, $extensions ) {

    $iti = new RecursiveDirectoryIterator( $folder );
    $file_array = array();

    foreach( new RecursiveIteratorIterator( $iti ) as $file ){

        // if the item is a directory, skip it
        if( is_dir( $file ) ){
            continue;
        }
        else{

            // check to see if any of the key words the user entered are contained within the filename
            $match = true;
            foreach( $words as $word ){
                if( stripos( basename( $file ), $word ) === false ){ 
                    $match = false;
                }
            }

            // if any of the keywords match, add the filename to our array to be returned to the caller
            if( $match ){
                foreach( $extensions as $ext ){
                    if( strpos( $file, $ext ) ){ // filter for acceptable file extensions
                        $is_unique = true;
                        foreach( $file_array as $file_path ){
                            if( ( basename( $file ) == basename( $file_path ) ) ){ // eliminate duplicates
                                $is_unique = false;
                            }
                        }
                        if( $is_unique ){
                            array_push( $file_array, $file );
                        }
                        break;
                    }
                }
            }
        }
    }

    // if our search returned one or more filenames, return the array. Otherwise return false
    if( isset( $file_array ) && !empty( $file_array ) ){
        return $file_array;
    }
    return false;
}