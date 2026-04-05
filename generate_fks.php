<?php

$fks = DB::select("
    SELECT tc.TABLE_NAME as table_name,
           tc.CONSTRAINT_NAME as constraint_name,
           kcu.COLUMN_NAME as column_name,
           kcu.REFERENCED_TABLE_NAME as referenced_table,
           kcu.REFERENCED_COLUMN_NAME as referenced_column
    FROM information_schema.TABLE_CONSTRAINTS tc 
    JOIN information_schema.KEY_COLUMN_USAGE kcu 
      ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME AND tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA 
    JOIN information_schema.REFERENTIAL_CONSTRAINTS rc 
      ON tc.CONSTRAINT_NAME = rc.CONSTRAINT_NAME AND tc.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA 
    WHERE tc.TABLE_SCHEMA = DATABASE() 
      AND tc.CONSTRAINT_TYPE = 'FOREIGN KEY' 
      AND rc.DELETE_RULE = 'CASCADE'
");

$sql = '';
foreach ($fks as $fk) {
    if ($fk->table_name === 'migrations' || $fk->table_name === 'personal_access_tokens') {
        continue;
    }

    $sql .= "-- 1. Remove the existing cascading constraint for {$fk->table_name}\n";
    $sql .= "ALTER TABLE `{$fk->table_name}` \n";
    $sql .= "DROP FOREIGN KEY `{$fk->constraint_name}`;\n\n";

    $sql .= "-- 2. Add the new restrictive constraint for {$fk->table_name}\n";
    $sql .= "ALTER TABLE `{$fk->table_name}` \n";
    $sql .= "ADD CONSTRAINT `{$fk->constraint_name}` \n";
    $sql .= "FOREIGN KEY (`{$fk->column_name}`) REFERENCES `{$fk->referenced_table}`(`{$fk->referenced_column}`) \n";
    $sql .= "ON DELETE RESTRICT;\n\n";
}

file_put_contents('fk_restrict_queries.sql', $sql);
echo "SQL written to fk_restrict_queries.sql\n";



//php artisan tinker generate_fks.php