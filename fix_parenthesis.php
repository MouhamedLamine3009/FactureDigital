<?php
$file = 'resources/views/livewire/document-show.blade.php';
$content = file_get_contents($file);

// Count the closing parentheses in the status badge line
$old = "'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300'))))))))";
$new = "'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300')))))))";

$content = str_replace($old, $new, $content);

file_put_contents($file, $content);
echo "Fixed!\n";

